@extends('layouts.main')

@section('content')
    @php
        use Illuminate\Support\Facades\DB;

        $activeCat = request('cat');

        // Build the portfolio list, optionally filtered by category (via entity_labels)
        $portfolios = \App\Models\Portfolio::query()
            ->when($activeCat, function ($q) use ($activeCat) {
                $q->whereExists(function ($sub) use ($activeCat) {
                    $sub->select(DB::raw(1))
                        ->from('entity_labels as el')
                        ->whereColumn('el.target_id', 'portfolios.id')
                        ->where('el.target_type', 'portfolio')
                        ->where('el.kind', 'category')
                        ->where('el.slug', $activeCat);
                });
            })
            ->orderByDesc('created_at')
            ->get();

        // Categories come back as ['slug' => 'Display Name']
        $categories = \App\Models\Portfolio::categories();
    @endphp

    <article class="portfolio active" data-page="portfolio">
        <header>
            <h2 class="h2 article-title">Portfolio</h2>
        </header>

        <section class="projects">
            {{-- Filter tabs --}}
            <ul class="filter-list">
                <li class="filter-item">
                    <a href="{{ route('page.portfolio') }}" class="{{ $activeCat ? '' : 'active' }}">All</a>
                </li>

                @foreach($categories as $slug => $name)
                    <li class="filter-item">
                        <a href="{{ route('page.portfolio', ['cat' => $slug]) }}"
                           class="{{ $activeCat === $slug ? 'active' : '' }}">
                            {{ $name }}
                        </a>
                    </li>
                @endforeach
            </ul>

            {{-- Project cards --}}
            <ul class="project-list">
                @forelse($portfolios as $p)
                    @php
                        // Pick a cover image: portfolio_images.is_cover=1 (first), else portfolios.image_url, else default
                        $cover = DB::table('portfolio_images')
                            ->where('portfolio_id', $p->id)
                            ->where('is_cover', 1)
                            ->orderBy('position')
                            ->value('url');

                        $imgUrl = $cover ?: ($p->image_url ?: '/images/default-blog.jpeg');

                        $liked = auth()->check()
                            ? DB::table('likes')->where('portfolio_id', $p->id)->where('user_id', auth()->id())->exists()
                            : false;
                    @endphp

                    <li class="project-item">
                        <a href="{{ route('page.portfoliofull', ['id' => $p->id]) }}">
                            <figure class="project-img">
                                <div class="project-item-icon-box">
                                    <button select-project>
                                        <ion-icon name="eye-outline"></ion-icon>
                                    </button>
                                </div>
                                <img src="{{ asset($imgUrl) }}" alt="{{ $p->title }}" loading="lazy">
                            </figure>

                            <h3 class="project-title">{{ $p->title }}</h3>

                            {{-- Show categories (labels) for each project, if you want chips under the title --}}
                            @php
                                $cats = DB::table('entity_labels')
                                    ->where('target_type', 'portfolio')
                                    ->where('target_id', $p->id)
                                    ->where('kind', 'category')
                                    ->orderBy('weight')
                                    ->pluck('name')
                                    ->toArray();
                            @endphp
                            @if(count($cats))
                                <p class="project-category">{{ implode(' · ', $cats) }}</p>
                            @endif
                        </a>

                        {{-- Interactions: like/edit/delete --}}
                        @auth
                            @if(auth()->user()->user_type == 0 && auth()->id() == $p->user_id)
                                <div class="project-interact">
                                    <a class="icon-box" href="{{ route('edit.portfolio', ['id' => $p->id]) }}">
                                        <ion-icon name="pencil-outline" aria-label="Edit"></ion-icon>
                                    </a>
                                    <form action="{{ route('edit.portfolio.delete', ['id' => $p->id]) }}" method="post">
                                        @csrf
                                        @method('delete')
                                        <button class="icon-box">
                                            <ion-icon name="trash-outline" aria-label="Delete"></ion-icon>
                                        </button>
                                    </form>
                                </div>
                            @else
                                <form action="{{ route('page.portfolio.like', ['id' => $p->id, 'cat' => $activeCat]) }}"
                                      method="post" class="project-interact">
                                    @csrf
                                    <button class="icon-box" title="{{ $liked ? 'Liked' : 'Like' }}">
                                        <ion-icon {{ $liked ? 'name=thumbs-up aria-label=Liked' : 'name=thumbs-up-outline aria-label=Like' }}></ion-icon>
                                        {{ $p->like_count > 0 ? $p->like_count : '' }}
                                    </button>
                                </form>
                            @endif
                        @endauth

                        @guest
                            <span class="icon-box project-interact">
              <ion-icon name="thumbs-up-outline" aria-label="Likes"></ion-icon>
              {{ $p->like_count > 0 ? $p->like_count : '' }}
            </span>
                        @endguest
                    </li>
                @empty
                    <li>No projects found.</li>
                @endforelse
            </ul>
        </section>

    </article>

    @auth
        @if(auth()->user()->user_type == 0)
            <a href="{{ route('edit.portfolio') }}" class="edit-page-button">
                <ion-icon name="add-outline" aria-label="Add"></ion-icon> New Portfolio Item
            </a>
        @endif
    @endauth
    @guest
        {{-- Show login button when user is not logged in --}}
        <a href="{{ route('page.show', ['name' => 'login']) }}" class="edit-page-button">
            <ion-icon name="log-in-outline" role="img" aria-label="Login"></ion-icon>
            Log In
        </a>
    @endguest

    @auth
        {{-- Show profile photo when logged in --}}
        <a href="{{ route('page.show', ['name' => 'profile']) }}" class="edit-page-button">
            @if(Auth::user()->AVATAR)
                <img src="{{ asset(Auth::user()->AVATAR) }}"
                     alt="Profile"
                     style="width:40px; height:40px; border-radius:50%; object-fit:cover;">
            @else
                <ion-icon name="person-circle-outline" role="img" aria-label="Profile"></ion-icon>
            @endif
        </a>
    @endauth
@endsection
