@extends('layouts.main')

@section('content')
    @php
        use Illuminate\Support\Facades\DB;

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
                    <a href="{{ route('portfolio.index') }}" class="{{ $activeCat ? '' : 'active' }}">All</a>
                </li>

                @foreach($categories as $slug => $name)
                    <li class="filter-item">
                        <a href="{{ route('portfolio.index', ['cat' => $slug]) }}"
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
                            @if(auth()->user()->user_type == 0)
                                <div class="project-interact">
                                    <a class="icon-box"
                                       href="{{ route('edit.portfolio', ['portfolio_id' => $p->id]) }}">
                                        <ion-icon name="pencil-outline" aria-label="Edit"></ion-icon>
                                    </a>
                                    <form action="{{ route('edit.portfolio.delete', ['portfolio_id' => $p->id]) }}"
                                          method="post">
                                        @csrf
                                        @method('delete')
                                        <button class="icon-box">
                                            <ion-icon name="trash-outline" aria-label="Delete"></ion-icon>
                                        </button>
                                    </form>
                                </div>
                            @else
                                <form
                                    action="{{ route('portfolio.like', $p) }}"
                                    method="post" class="project-interact">
                                    @csrf
                                    <button class="icon-box"
                                            title="{{ ($p->liked_by_me ?? false) ? 'Liked' : 'Like' }}">
                                        <ion-icon
                                            name="{{ $p->liked_by_me ? 'thumbs-up' : 'thumbs-up-outline' }}"
                                            aria-label="{{ $p->liked_by_me ? 'Liked' : 'Like' }}">
                                        </ion-icon>
                                        {{ $p->like_count > 0 ? $p->like_count : '' }}
                                    </button>
                                </form>

                            @endif
                        @endauth
                    </li>
                @empty
                    <li>No projects found.</li>
                @endforelse
            </ul>
            @auth
                @if(auth()->user()->user_type == 0)
                    <div style="display:flex; justify-content:flex-end; margin-top:18px;">
                        <a href="{{ route('edit.portfolio') }}" class="testimonial-button">
                            <ion-icon name="add-outline" aria-label="Add"></ion-icon>
                            New Portfolio Item
                        </a>
                    </div>
        @endif
        @endauth


    </article>



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
