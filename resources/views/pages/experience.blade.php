@extends('layouts.main')

@section('content')
    @php
        // Pull super admin (USER_TYPE = 0 ) and recent content
        $superAdmin = \App\Models\User::where('USER_TYPE', 0)->first();
        $latestBlogs = \App\Models\Blog::orderByDesc('CREATED_AT')->limit(3)->get();
        $recentWorks = \App\Models\Portfolio::orderByDesc('UPDATED_AT')->limit(6)->get();
    @endphp

    <article class="home active" data-page="home">

        {{-- HERO --}}
        <header class="hero">
            <div class="hero-left">
                <h1 class="h2 article-title" style="margin-bottom:.25rem;">
                    Hi, I’m {{ $superAdmin->FIRST_NAME ?? 'Jiheui' }} {{ $superAdmin->LAST_NAME ?? 'Lee' }}
                </h1>
                <p class="title" style="margin-bottom:1rem;">
                    {{ $superAdmin->JOB_TITLE ?? 'Full Stack Developer' }}
                </p>
                <p style="max-width:52ch;margin-bottom:1.25rem;">
                    {{ $superAdmin->BIO ?? 'I build fast, modern web apps and elegant UI.' }}
                </p>
                <div class="hero-actions" style="display:flex;gap:.6rem;flex-wrap:wrap;">
                    <a href="{{ route('page.show',['name'=>'portfolio']) }}" class="form-btn login-highlight">View Portfolio</a>
                    <a href="{{ route('page.show',['name'=>'blog']) }}" class="form-btn">Read Blog</a>
                    <a href="{{ route('page.show',['name'=>'contact']) }}" class="form-btn">Contact</a>
                </div>
            </div>

            <figure class="avatar-box" style="margin-left:auto;">
                <img
                    src="{{ asset(($superAdmin && $superAdmin->AVATAR) ? $superAdmin->AVATAR : 'images/my-avatar.png') }}"
                    alt="Avatar" width="120">
            </figure>
        </header>

        <div class="separator" style="margin:1.25rem 0;"></div>

        {{-- LATEST BLOGS --}}
        <section class="service">
            <h3 class="h3 service-title">Latest Blogs</h3>

            <ul class="service-list" style="display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:16px;">
                @forelse ($latestBlogs as $post)
                    <li class="service-item">
                        <a href="{{ route('page.blogfull', ['id'=>$post['BLOG_ID']]) }}" class="service-link" style="display:flex;gap:16px;">
                            <div class="service-icon-box" style="align-self:flex-start;">
                                <img
                                    src="{{ asset($post['IMAGE_URL'] ?: 'images/default-blog.jpeg') }}"
                                    alt="blog thumbnail" width="56" height="56" style="object-fit:cover;border-radius:12px;">
                            </div>
                            <div class="service-content-box">
                                <h4 class="h4 service-item-title" style="margin-bottom:.25rem;">{{ $post['TITLE'] }}</h4>
                                <p class="blog-meta" style="opacity:.7;margin-bottom:.5rem;">
                                    {{ \Carbon\Carbon::parse($post['CREATED_AT'])->format('M d, Y') }}
                                </p>
                                <p class="service-item-text">
                                    {{ \Illuminate\Support\Str::limit(strip_tags($post['CONTENTS']), 110) }}
                                </p>
                            </div>
                        </a>
                    </li>
                @empty
                    <li class="service-item"><div class="service-content-box">No posts yet.</div></li>
                @endforelse
            </ul>

            <div style="margin-top:12px;">
                <a href="{{ route('page.show',['name'=>'blog']) }}" class="navbar-link">View all posts →</a>
            </div>
        </section>

        <div class="separator" style="margin:1.25rem 0;"></div>

        {{-- RECENT WORKS --}}
        <section class="service">
            <h3 class="h3 service-title">Recent Works</h3>

            <ul class="service-list has-scrollbar" style="display:grid;grid-template-columns:repeat(auto-fit,minmax(240px,1fr));gap:16px;">
                @forelse ($recentWorks as $work)
                    <li class="service-item">
                        <a href="{{ route('page.portfoliofull', ['id'=>$work['PORTFOLIO_ID']]) }}" class="service-link" style="display:flex;gap:16px;">
                            <div class="service-icon-box" style="align-self:flex-start;">
                                <img
                                    src="{{ asset($work['IMAGE_URL'] ?: 'images/default-icon.svg') }}"
                                    alt="project" width="56" height="56" style="object-fit:cover;border-radius:12px;">
                            </div>
                            <div class="service-content-box">
                                <h4 class="h4 service-item-title" style="margin-bottom:.25rem;">{{ $work['TITLE'] }}</h4>
                                <p class="service-item-text">
                                    {{ \Illuminate\Support\Str::limit($work['DESCRIPTION'] ?? '', 120) }}
                                </p>
                            </div>
                        </a>
                    </li>
                @empty
                    <li class="service-item"><div class="service-content-box">No portfolio items yet.</div></li>
                @endforelse
            </ul>

            <div style="margin-top:12px;">
                <a href="{{ route('page.show',['name'=>'portfolio']) }}" class="navbar-link">See all work →</a>
            </div>
        </section>

    </article>
@endsection
