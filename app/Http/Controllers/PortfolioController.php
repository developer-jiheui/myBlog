<?php

namespace App\Http\Controllers;

use App\Models\Portfolio;
use App\Models\Like;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PortfolioController extends Controller
{
    // List page: precompute liked_by_me + likes_count (no N+1)
    public function index(Request $request, ?string $slug = null)
    {
        // Category can come from /portfolios?category=foo or /portfolios/category/foo
        $activeCat = $slug ?? $request->query('cat');

        $query = Portfolio::query()
            ->select('portfolios.*')          // keep model hydration
            ->withCount('likes')              // provides $p->likes_count
            ->when($activeCat, function ($q) use ($activeCat) {
                // Filter by category using entity_labels
                $q->whereExists(function ($sub) use ($activeCat) {
                    $sub->select(DB::raw(1))
                        ->from('entity_labels as el')
                        ->whereColumn('el.target_id', 'portfolios.id')
                        ->where('el.target_type', 'portfolio')
                        ->where('el.kind', 'category')
                        ->where('el.slug', $activeCat);
                });
            });

        if (Auth::check()) {
            $query->withExists(['likes as liked_by_me' => function ($q) {
                $q->where('user_id', Auth::user()->id);
            }]);
        }

        $portfolios = $query->latest('updated_at')->get();

        return view('pages.portfolio', [
            'portfolios' => $portfolios,
            'activeCat' => $activeCat,
        ]);
    }

    // Store
    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'desc' => 'required|string',
            'category' => 'nullable|string|max:100',
            'url' => 'nullable|url|max:2048',
            'img' => 'nullable|image|max:4096', // 4MB
        ]);

        $p = new Portfolio();
        $p->user_id = Auth::user()->id;
        $p->title = $data['title'];
        $p->description = $data['desc'];
        $p->slug = $data['category'] ?? null;
        $p->project_url = $data['url'] ?? null;

        if ($request->hasFile('img')) {
            $p->image_url = '/storage/' . $request->file('img')->store('portfolioImgs', 'public');
        }

        $p->save();

        return redirect()->route('portfolio.index')->with('ok', 'Created');
    }

    // Update
    public function update(Request $request, Portfolio $portfolio)
    {
        // Optional: authorize the owner
        // $this->authorize('update', $portfolio);

        $data = $request->validate([
            'title' => 'required|string|max:255',
            'desc' => 'required|string',
            'category' => 'nullable|string|max:100',
            'url' => 'nullable|url|max:2048',
            'img' => 'nullable|image|max:4096',
        ]);

        $portfolio->title = $data['title'];
        $portfolio->description = $data['desc'];
        $portfolio->slug = $data['category'] ?? null;
        $portfolio->project_url = $data['url'] ?? null;

        if ($request->hasFile('img')) {
            $portfolio->image_url = '/storage/' . $request->file('img')->store('portfolioImgs', 'public');
        }

        $portfolio->save();

        return redirect()->route('portfolio.show', $portfolio)->with('ok', 'Updated');
    }

    // Destroy
    public function destroy(Portfolio $portfolio)
    {
        // $this->authorize('delete', $portfolio);
        $portfolio->delete();
        return redirect()->route('portfolio.index')->with('ok', 'Deleted');
    }

    // Toggle like (POST)
    public function like(Request $request, Portfolio $portfolio)
    {
        if (!Auth::check()) {
            abort(401);
        }

        DB::transaction(function () use ($portfolio) {
            $like = Like::where('user_id', Auth::user()->id)
                ->where('portfolio_id', $portfolio->id)
                ->first();

            if ($like) {
                $like->delete();
            } else {
                Like::create([
                    'user_id' => Auth::user()->id,
                    'portfolio_id' => $portfolio->id,
                    'created_at' => now(),
                ]);
            }

            // If you KEEP a cached like_count column on portfolios:
            $count = Like::where('portfolio_id', $portfolio->id)->count();
            $portfolio->update(['like_count' => $count]);
        });

        return redirect()->back();
    }
}
