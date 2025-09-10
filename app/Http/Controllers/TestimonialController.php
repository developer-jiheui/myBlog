<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Testimonial;

class TestimonialController extends Controller
{
    public function dashboard(Request $request)
    {
        $user    = Auth::user();
        $isAdmin = (int)$user->user_type === 0;

        $query = Testimonial::query()
            ->when(!$isAdmin, fn($q) => $q->where('author_user_id', $user->id))
            ->orderByDesc('pinned')
            ->orderByDesc('created_at');

        // If you still want pagination, do it once, then group in PHP:
        $testimonials = $query->paginate(24)->withQueryString();

        // Group for lanes (so the blade is simple)
        $grouped = $testimonials->getCollection()->groupBy('status'); // 0/1/2

        // For lane headers
        $statusLabels = Testimonial::statusLabels();
        $val = [
            'testimonials'  => $testimonials, // keep paginator for links()
            'grouped'       => $grouped,
            'isAdmin'       => $isAdmin,
            'statusLabels'  => $statusLabels,
        ];
        return view('pages.testimonial', compact('testimonials', 'grouped', 'isAdmin','val'));
    }

    public function create()
    {
        // create form page -> make resources/views/pages/testimonial-create.blade.php
        return view('pages.testimonial');
    }

    public function store(Request $request)
    {
        $request->validate([
            'author_title' => ['nullable','string','max:120'],
            'body'         => ['required','string','max:5000'],
            'pinned'       => ['nullable','boolean'],
        ]);

        $u = Auth::user();

        Testimonial::create([
            'author_user_id'    => $u->id,
            'author_name'       => trim(($u->first_name ?? '').' '.($u->last_name ?? '')) ?: ($u->email ?? 'User'),
            'author_avatar_url' => $u->avatar,
            'author_title'      => $request->author_title,
            'body'              => $request->body,
            'status'            => 1,
            'pinned'            => $request->boolean('pinned') ? 1 : 0,
        ]);

        return redirect()->route('testimonials.dashboard')->with('success', 'Thanks! Your testimonial has been submitted.');
    }

    public function edit(Testimonial $t)
    {
        $this->authorizeEdit($t);
        return view('pages.testimonial-edit', compact('t'));
    }

    public function update(Testimonial $t, Request $request)
    {
        $this->authorizeEdit($t);

        $data = $request->validate([
            'author_title' => 'nullable|string|max:120',
            'body'         => 'required|string|max:5000',
        ]);

        $t->update($data);

        return redirect()->route('testimonials.dashboard')->with('success', 'Updated!');
    }

    public function togglePin(Testimonial $t)
    {
        abort_unless((int) Auth::user()->user_type === 0, 403);

        $t->pinned = $t->pinned ? 0 : 1;
        $t->save();

        return back()->with('success', $t->pinned ? 'Pinned' : 'Unpinned');
    }

    private function authorizeEdit(Testimonial $t)
    {
        $user    = Auth::user();
        $isOwner = (int) $t->author_user_id === (int) $user->id;
        $isAdmin = (int) $user->user_type === 0;

        abort_unless($isOwner || $isAdmin, 403);
    }

    // Admin: change status
    public function updateStatus(Testimonial $t, Request $request)
    {
        abort_unless((int)Auth::user()->user_type === 0, 403); // only admin
        $request->validate(['status' => 'required|in:0,1,2']);
        $t->update(['status' => $request->status]);
        return back()->with('success', 'Status updated.');
    }
}
