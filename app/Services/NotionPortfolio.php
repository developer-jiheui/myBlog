<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class NotionPortfolio
{
    private Client $http;
    private string $token;
    private string $dbId;

    public function __construct()
    {
        $this->token = config('services.notion.token');
        $this->dbId = config('services.notion.portfolio_db');
        $this->http = new Client([
            'base_uri' => 'https://api.notion.com/v1/',
            'headers' => [
                'Authorization' => "Bearer {$this->token}",
                'Notion-Version' => '2022-06-28',
                'Content-Type' => 'application/json',
            ],
            'timeout' => 10,
        ]);
    }

    public function listProjects(int $limit = 50): array
    {
        return Cache::remember('notion.portfolio.projects', 600, function () use ($limit) {
            $payload = [
                'filter' => [
                    'property' => 'Published',
                    'checkbox' => ['equals' => true],
                ],
                'sorts' => [
                    ['property' => 'Date', 'direction' => 'descending'],
                ],
                'page_size' => $limit,
            ];

            $resp = $this->http->post("databases/{$this->dbId}/query", ['json' => $payload]);
            $data = json_decode($resp->getBody()->getContents(), true);

            $items = [];
            foreach ($data['results'] ?? [] as $page) {
                $props = $page['properties'] ?? [];

                $name = $this->plainTitle($props['Name']['title'] ?? []);
                $slug = $this->plainText($props['Slug']['rich_text'] ?? []) ?: Str::slug($name);
                $summary = $this->plainText($props['Summary']['rich_text'] ?? []);
                $url = $props['URL']['url'] ?? null;
                $github = $props['GitHub']['url'] ?? null;
                $date = $props['Date']['date']['start'] ?? null;
                $tech = array_map(fn($t) => $t['name'], $props['Tech']['multi_select'] ?? []);

                // Prefer Files property "Cover" if present; else use page cover
                $cover = $this->firstFileUrl($props['Cover']['files'] ?? [])
                    ?: ($page['cover']['external']['url'] ?? $page['cover']['file']['url'] ?? null);

                $items[] = [
                    'id' => $page['id'],
                    'name' => $name,
                    'slug' => $slug,
                    'summary' => $summary,
                    'url' => $url,
                    'github' => $github,
                    'date' => $date,
                    'tech' => $tech,
                    'cover' => $cover,
                ];
            }

            return $items;
        });
    }

    private function plainTitle(array $title): string
    {
        return collect($title)->pluck('plain_text')->join('');
    }

    private function plainText(array $rich): string
    {
        return collect($rich)->pluck('plain_text')->join('');
    }

    private function firstFileUrl(array $files): ?string
    {
        foreach ($files as $f) {
            if (isset($f['file']['url'])) return $f['file']['url'];
            if (isset($f['external']['url'])) return $f['external']['url'];
        }
        return null;
    }

    public function findBySlugOrId(string $key): ?array
    {
        // 1. Try to find by Slug
        $resp = $this->http->post("databases/{$this->dbId}/query", [
            'json' => [
                'filter' => [
                    'property' => 'Slug',
                    'rich_text' => ['equals' => $key],
                ],
                'page_size' => 1,
            ]
        ]);

        $data = json_decode($resp->getBody()->getContents(), true);
        if (!empty($data['results'])) {
            return $this->mapPage($data['results'][0]);
        }

        // 2. Try to get directly by page ID
        try {
            $resp = $this->http->get("pages/{$key}");
            $page = json_decode($resp->getBody()->getContents(), true);
            return $this->mapPage($page);
        } catch (\Throwable $e) {
            return null;
        }
    }

// --- helpers ---
    private function mapPage(array $page): ?array
    {
        $props = $page['properties'] ?? [];

        $name = $this->plainTitle($props['Name']['title'] ?? []);
        if ($name === '') return null;

        $slug = $this->plainText($props['Slug']['rich_text'] ?? []) ?: \Illuminate\Support\Str::slug($name);
        $summary = $this->plainText($props['Summary']['rich_text'] ?? []);
        $url = $props['URL']['url'] ?? null;
        $github = $props['GitHub']['url'] ?? null;
        $date = $props['Date']['date']['start'] ?? null;
        $tech = array_map(fn($t) => $t['name'], $props['Tech']['multi_select'] ?? []);

        $cover = $this->firstFileUrl($props['Cover']['files'] ?? [])
            ?: ($page['cover']['external']['url'] ?? $page['cover']['file']['url'] ?? null);

        return [
            'id' => $page['id'],
            'name' => $name,
            'slug' => $slug,
            'summary' => $summary,
            'url' => $url,
            'github' => $github,
            'date' => $date,
            'tech' => $tech,
            'cover' => $cover,
        ];
    }

    public function renderPageHtml(string $pageId): string
    {
        $blocks = $this->getAllBlocks($pageId);
        return $this->blocksToHtml($blocks);
    }

// Fetch ALL child blocks (recursively, handles pagination)
    private function getAllBlocks(string $blockId): array
    {
        $out = [];
        $cursor = null;
        do {
            $resp = $this->http->get("blocks/{$blockId}/children", [
                'query' => $cursor ? ['start_cursor' => $cursor] : []
            ]);
            $data = json_decode($resp->getBody()->getContents(), true);
            foreach ($data['results'] ?? [] as $b) {
                // If block has children, fetch them too
                if (!empty($b['has_children'])) {
                    $b['_children'] = $this->getAllBlocks($b['id']);
                } else {
                    $b['_children'] = [];
                }
                $out[] = $b;
            }
            $cursor = $data['next_cursor'] ?? null;
        } while ($data['has_more'] ?? false);

        return $out;
    }

// Minimal rich-text to HTML (handles annotations + links)
    private function richTextToHtml(array $rich): string
    {
        $html = '';
        foreach ($rich as $r) {
            $text = htmlspecialchars($r['plain_text'] ?? '', ENT_QUOTES, 'UTF-8');
            if ($text === '') continue;

            // link
            if (!empty($r['href'])) {
                $text = '<a href="' . htmlspecialchars($r['href'], ENT_QUOTES, 'UTF-8') . '" target="_blank" rel="noopener">' . $text . '</a>';
            }

            // annotations
            $ann = $r['annotations'] ?? [];
            if (!empty($ann['code'])) $text = '<code>' . $text . '</code>';
            if (!empty($ann['bold'])) $text = '<strong>' . $text . '</strong>';
            if (!empty($ann['italic'])) $text = '<em>' . $text . '</em>';
            if (!empty($ann['strikethrough'])) $text = '<s>' . $text . '</s>';
            if (!empty($ann['underline'])) $text = '<u>' . $text . '</u>';

            $html .= $text;
        }
        return $html;
    }

// Convert blocks to HTML (common types)
    private function blocksToHtml(array $blocks): string
    {
        $html = '';
        foreach ($blocks as $b) {
            $type = $b['type'] ?? null;
            $c = $b[$type] ?? [];

            switch ($type) {
                case 'paragraph':
                    $html .= '<p>' . $this->richTextToHtml($c['rich_text'] ?? []) . '</p>';
                    // children (e.g., nested toggles inside paragraphs are rare, but safe to render)
                    if (!empty($b['_children'])) $html .= $this->blocksToHtml($b['_children']);
                    break;

                case 'heading_1':
                    $html .= '<h1>' . $this->richTextToHtml($c['rich_text'] ?? []) . '</h1>';
                    break;
                case 'heading_2':
                    $html .= '<h2>' . $this->richTextToHtml($c['rich_text'] ?? []) . '</h2>';
                    break;
                case 'heading_3':
                    $html .= '<h3>' . $this->richTextToHtml($c['rich_text'] ?? []) . '</h3>';
                    break;

                case 'bulleted_list_item':
                    $html .= '<ul><li>' . $this->richTextToHtml($c['rich_text'] ?? []);
                    if (!empty($b['_children'])) $html .= $this->blocksToHtml($b['_children']);
                    $html .= '</li></ul>';
                    break;

                case 'numbered_list_item':
                    $html .= '<ol><li>' . $this->richTextToHtml($c['rich_text'] ?? []);
                    if (!empty($b['_children'])) $html .= $this->blocksToHtml($b['_children']);
                    $html .= '</li></ol>';
                    break;

                case 'to_do':
                    $checked = !empty($c['checked']) ? ' checked' : '';
                    $html .= '<div><label><input type="checkbox" disabled' . $checked . '> ' . $this->richTextToHtml($c['rich_text'] ?? []) . '</label></div>';
                    if (!empty($b['_children'])) $html .= $this->blocksToHtml($b['_children']);
                    break;

                case 'quote':
                    $html .= '<blockquote>' . $this->richTextToHtml($c['rich_text'] ?? []) . '</blockquote>';
                    break;

                case 'callout':
                    $html .= '<div class="notion-callout" style="border:1px solid var(--jet);padding:.75rem;border-radius:10px;">'
                        . $this->richTextToHtml($c['rich_text'] ?? [])
                        . '</div>';
                    if (!empty($b['_children'])) $html .= $this->blocksToHtml($b['_children']);
                    break;

                case 'code':
                    $lang = htmlspecialchars($c['language'] ?? 'plain', ENT_QUOTES, 'UTF-8');
                    $code = '';
                    foreach (($c['rich_text'] ?? []) as $r) {
                        $code .= $r['plain_text'] ?? '';
                    }
                    $html .= '<pre><code class="language-' . $lang . '">' . htmlspecialchars($code, ENT_QUOTES, 'UTF-8') . '</code></pre>';
                    break;

                case 'divider':
                    $html .= '<hr />';
                    break;

                case 'image':
                    $src = $c['type'] === 'external'
                        ? ($c['external']['url'] ?? null)
                        : ($c['file']['url'] ?? null);
                    if ($src) {
                        $html .= '<figure><img src="' . htmlspecialchars($src, ENT_QUOTES, 'UTF-8') . '" alt="" style="max-width:100%;height:auto;">';
                        if (!empty($c['caption'])) {
                            $html .= '<figcaption>' . $this->richTextToHtml($c['caption']) . '</figcaption>';
                        }
                        $html .= '</figure>';
                    }
                    break;

                case 'bookmark':
                    $url = $c['url'] ?? null;
                    if ($url) $html .= '<p><a href="' . htmlspecialchars($url, ENT_QUOTES, 'UTF-8') . '" target="_blank" rel="noopener">' . $url . '</a></p>';
                    break;

                case 'toggle':
                    $summary = $this->richTextToHtml($c['rich_text'] ?? []);
                    $html .= '<details><summary>' . $summary . '</summary>';
                    if (!empty($b['_children'])) $html .= $this->blocksToHtml($b['_children']);
                    $html .= '</details>';
                    break;

                // add more types as needed: table, equation, video, pdf, etc.
                default:
                    // fallback: ignore unknown types instead of breaking
                    break;
            }
        }

        // naive list merge (turn <ul><li>..</li></ul><ul><li>..</li></ul> into one list) – optional
        $html = preg_replace('#</ul>\s*<ul>#', '', $html);
        $html = preg_replace('#</ol>\s*<ol>#', '', $html);

        return $html;
    }
}
