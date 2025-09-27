@extends('layouts.main')
@section('content')
    <article class="active" data-page="portfolio">
        <header>
            <h2 class="h2 article-title">Portfolio</h2>
        </header>

        @if(empty($projects))
            <p style="color:var(--light-gray);">No projects yet.</p>
        @else
            <ul class="project-list"
                style="display:grid;grid-template-columns:repeat(auto-fill,minmax(260px,1fr));gap:16px;">
                @foreach($projects as $p)
                    <li class="project-item"
                        style="background:var(--eerie-black-2); border:1px solid var(--jet); border-radius:14px; overflow:hidden;">
                        <a href="{{ $p['url'] ?? '#' }}" target="{{ $p['url'] ? '_blank' : '_self' }}"
                           style="text-decoration:none;">
                            @if($p['cover'])
                                <div style="aspect-ratio: 16/9; background:#111; overflow:hidden;">
                                    <img src="{{ $p['cover'] }}" alt="{{ $p['name'] }}"
                                         style="width:100%; height:100%; object-fit:cover; display:block;">
                                </div>
                            @endif
                            <div style="padding:14px;">
                                <h3 class="project-title"
                                    style="color:var(--white-2); font-size:1.05rem; margin:0 0 .4rem;">
                                    {{ $p['name'] }}
                                </h3>
                                @if($p['summary'])
                                    <p style="color:var(--light-gray); font-size:.95rem; line-height:1.35; margin:0 0 .6rem;">
                                        {{ $p['summary'] }}
                                    </p>
                                @endif

                                @if(!empty($p['tech']))
                                    <div style="display:flex; flex-wrap:wrap; gap:6px; margin-bottom:.6rem;">
                                        @foreach($p['tech'] as $tag)
                                            <span
                                                style="font-size:.8rem; padding:.2rem .5rem; border:1px solid var(--jet); border-radius:999px; color:var(--white-2);">
                      {{ $tag }}
                    </span>
                                        @endforeach
                                    </div>
                                @endif

                                <div style="display:flex; gap:10px; align-items:center;">
                                    @if($p['url'])
                                        <a href="{{ $p['url'] }}" target="_blank" class="btn"
                                           style="font-size:.9rem; color:var(--orange-yellow-crayola);">Live</a>
                                    @endif
                                    @if($p['github'])
                                        <a href="{{ $p['github'] }}" target="_blank" class="btn"
                                           style="font-size:.9rem; color:var(--orange-yellow-crayola);">GitHub</a>
                                    @endif
                                    @if($p['date'])
                                        <span
                                            style="margin-left:auto; font-size:.8rem; color:var(--smoky-black); opacity:.8;">
                    {{ \Illuminate\Support\Carbon::parse($p['date'])->format('Y-m-d') }}
                  </span>
                                    @endif
                                </div>
                            </div>
                        </a>
                    </li>
                @endforeach
            </ul>
        @endif
    </article>
@endsection


{{--@extends('layouts.main')--}}

{{--@section('content')--}}
{{--    @php--}}
{{--        use Illuminate\Support\Facades\DB;--}}

{{--        // Categories come back as ['slug' => 'Display Name']--}}
{{--        $categories = \App\Models\Portfolio::categories();--}}
{{--    @endphp--}}

{{--    <article class="portfolio active" data-page="portfolio">--}}
{{--        <header>--}}
{{--            <h2 class="h2 article-title">Portfolio</h2>--}}
{{--        </header>--}}

{{--        <section class="projects">--}}
{{--            --}}{{-- Filter tabs --}}
{{--            <ul class="filter-list">--}}
{{--                <li class="filter-item">--}}
{{--                    <a href="{{ route('portfolio.index') }}" class="{{ $activeCat ? '' : 'active' }}">All</a>--}}
{{--                </li>--}}

{{--                @foreach($categories as $slug => $name)--}}
{{--                    <li class="filter-item">--}}
{{--                        <a href="{{ route('portfolio.index', ['cat' => $slug]) }}"--}}
{{--                           class="{{ $activeCat === $slug ? 'active' : '' }}">--}}
{{--                            {{ $name }}--}}
{{--                        </a>--}}
{{--                    </li>--}}
{{--                @endforeach--}}
{{--            </ul>--}}

{{--            --}}{{-- Project cards --}}
{{--            <ul class="project-list">--}}
{{--                @forelse($portfolios as $p)--}}
{{--                    @php--}}
{{--                        // Pick a cover image: portfolio_images.is_cover=1 (first), else portfolios.image_url, else default--}}
{{--                        $cover = DB::table('portfolio_images')--}}
{{--                            ->where('portfolio_id', $p->id)--}}
{{--                            ->where('is_cover', 1)--}}
{{--                            ->orderBy('position')--}}
{{--                            ->value('url');--}}

{{--                        $imgUrl = $cover ?: ($p->image_url ?: '/images/default-blog.jpeg');--}}

{{--                    @endphp--}}

{{--                    <li class="project-item">--}}
{{--                        <a href="{{ route('page.portfoliofull', ['id' => $p->id]) }}">--}}
{{--                            <figure class="project-img">--}}
{{--                                <div class="project-item-icon-box">--}}
{{--                                    <button select-project>--}}
{{--                                        <ion-icon name="eye-outline"></ion-icon>--}}
{{--                                    </button>--}}
{{--                                </div>--}}
{{--                                <img src="{{ asset($imgUrl) }}" alt="{{ $p->title }}" loading="lazy">--}}
{{--                            </figure>--}}

{{--                            <h3 class="project-title">{{ $p->title }}</h3>--}}
{{--                        </a>--}}

{{--                        --}}{{-- Interactions: like/edit/delete --}}
{{--                        @auth--}}
{{--                            @if(auth()->user()->user_type == 0)--}}
{{--                                <div class="project-interact">--}}
{{--                                    <a class="icon-box"--}}
{{--                                       href="{{ route('edit.portfolio', ['id' => $p->id]) }}">--}}
{{--                                        <ion-icon name="pencil-outline" aria-label="Edit"></ion-icon>--}}
{{--                                    </a>--}}
{{--                                    <form action="{{ route('edit.portfolio.delete', ['id' => $p->id]) }}"--}}
{{--                                          method="post">--}}
{{--                                        @csrf--}}
{{--                                        @method('delete')--}}
{{--                                        <button class="icon-box">--}}
{{--                                            <ion-icon name="trash-outline" aria-label="Delete"></ion-icon>--}}
{{--                                        </button>--}}
{{--                                    </form>--}}
{{--                                </div>--}}
{{--                            @else--}}
{{--                                <form--}}
{{--                                    action="{{ route('portfolio.like', $p) }}"--}}
{{--                                    method="post" class="project-interact">--}}
{{--                                    @csrf--}}
{{--                                    <button class="icon-box"--}}
{{--                                            title="{{ ($p->liked_by_me ?? false) ? 'Liked' : 'Like' }}">--}}
{{--                                        <ion-icon--}}
{{--                                            name="{{ $p->liked_by_me ? 'thumbs-up' : 'thumbs-up-outline' }}"--}}
{{--                                            aria-label="{{ $p->liked_by_me ? 'Liked' : 'Like' }}">--}}
{{--                                        </ion-icon>--}}
{{--                                        {{ $p->like_count > 0 ? $p->like_count : '' }}--}}
{{--                                    </button>--}}
{{--                                </form>--}}

{{--                            @endif--}}
{{--                        @endauth--}}
{{--                    </li>--}}
{{--                @empty--}}
{{--                    <li>No projects found.</li>--}}
{{--                @endforelse--}}
{{--            </ul>--}}
{{--            @auth--}}
{{--                @if(auth()->user()->user_type == 0)--}}
{{--                    <div style="display:flex; justify-content:flex-end; margin-top:18px;">--}}
{{--                        <a href="{{ route('edit.portfolio') }}" class="testimonial-button">--}}
{{--                            <ion-icon name="add-outline" aria-label="Add"></ion-icon>--}}
{{--                            New Portfolio Item--}}
{{--                        </a>--}}
{{--                    </div>--}}
{{--        @endif--}}
{{--        @endauth--}}


{{--    </article>--}}


{{--    @auth--}}
{{--        --}}{{-- Show profile photo when logged in --}}
{{--        <a href="{{ route('page.show', ['name' => 'profile']) }}" class="edit-page-button">--}}
{{--            @if(Auth::user()->AVATAR)--}}
{{--                <img src="{{ asset(Auth::user()->AVATAR) }}"--}}
{{--                     alt="Profile"--}}
{{--                     style="width:40px; height:40px; border-radius:50%; object-fit:cover;">--}}
{{--            @else--}}
{{--                <ion-icon name="person-circle-outline" role="img" aria-label="Profile"></ion-icon>--}}
{{--            @endif--}}
{{--        </a>--}}
{{--    @endauth--}}
{{--@endsection--}}
