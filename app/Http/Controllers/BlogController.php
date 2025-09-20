<?php
namespace App\Http\Controllers;

use \Illuminate\Support\Facades\Auth;
use \Illuminate\Support\Facades\Storage;
use \Illuminate\Support\Facades\DB;
use \Illuminate\Http\Request;
use \App\Models\Blog;
use DOMDocument;
use Illuminate\Support\Str;

class BlogController extends Controller
{

    public function create(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'contents' => 'required|string',
        ]);

        //$u = Auth::user();

        $blogItem = new Blog;
        self::blogItemFormRequest($blogItem, $request);

        return view('pages.blog');
//        $blog = Blog::create([
//            'user_id' => $u->id,
//            'title' => $data['title'],
//            'contents' => $data['contents'],
//        ]);
//
//        return view('pages.blog', ['id' => $blog->id])
//            ->with('status', 'Blog created!');
    }

    public function edit(Request $request)
    {
        $blogItem = Blog::find($_GET['id']);
        self::blogItemFormRequest($blogItem, $request);
        return view('pages.blogfull');
    }


    public static function extractFirstImageSrc(string $html): ?string
    {
        if (!preg_match('/<img[^>]+src="([^">]+)"/i', $html, $m)) {
            return null;
        }

        $src = $m[1];

        // Case 1: data URI (base64)
        if (preg_match('/^data:image\/(\w+);base64,/', $src, $type)) {
            $extension = strtolower($type[1]);      // jpg, png, gif, webp...
            $data = substr($src, strpos($src, ',') + 1);
            $image = base64_decode($data);

            if ($image === false) {
                return null; // bad base64
            }

            $filename = uniqid('blog_', true) . '.' . $extension;
            $path = "blog-images/{$filename}";

            // stores to storage/app/public/blog-images/...
            Storage::disk('public')->put($path, $image);

            // return public URL, e.g. /storage/blog-images/xxx.jpg
            return Storage::url($path);
        }

        // Case 2: remote URL — download and store a local copy
        if (preg_match('#^https?://#i', $src)) {
            try {
                $image = @file_get_contents($src);
                if ($image === false) {
                    return null;
                }

                // try to guess extension from URL (fallback to jpg)
                $ext = 'jpg';
                if (preg_match('/\.(jpe?g|png|gif|webp|bmp|svg)(?:\?|$)/i', $src, $em)) {
                    $ext = strtolower($em[1]);
                }

                $filename = uniqid('blog_', true) . '.' . $ext;
                $path = "blog-images/{$filename}";
                Storage::disk('public')->put($path, $image);

                return Storage::url($path);
            } catch (\Throwable $e) {
                return null;
            }
        }

        // Case 3: already a relative path (e.g. /uploads/foo.jpg) — keep as-is
        return $src;
    }

    private function blogItemFormRequest(Blog $blogItem, Request $request): void
    {
        $blogItem->user_id = Auth::user()->id;
        $blogItem->title = $request->input('title');
        $blogItem->contents = $request->input('contents');

        // Extract & store the first image; save only its URL/path
        $firstImageUrl = self::extractFirstImageSrc($blogItem->contents);
        if (!empty($firstImageUrl)) {
            $blogItem->image_url = $firstImageUrl;  // <- string URL, not bytes
        }

        $blogItem->save();
    }

    public function delete()
    {
        try {
            \App\Models\Blog::destroy($_GET['id']);
        } catch (\Exception $e) {
            http_response_code(400);
        }
        return view('pages.blog');
    }

    //chat
    public function index()
    {
        $blogs = Blog::all(); // or paginate if needed
        return view('blogs.index', compact('blogs'));
    }

    public function show($id)
    {
        $blog = Blog::findOrFail($id);
        return view('blogs.show', compact('blog'));
    }
}

?>
