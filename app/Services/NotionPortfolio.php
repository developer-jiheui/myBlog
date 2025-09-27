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
}
