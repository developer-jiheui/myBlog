@extends('layouts.main')

@section('content')
    <article class="blog active" data-page="blog">

        <header>
            <h2 class="h2 article-title">Blog</h2>
        </header>

        <section class="blog-posts">
            <ul class="blog-posts-list">
                @foreach (\App\Models\Blog::latest()->get() as $blogItem)
                    <li class="blog-post-item">
                        <a href="{{ route('page.blogfull', ['id' => $blogItem['id']]) }}">
                            <figure class="blog-banner-box">
                                <img src="{{ $blogItem['image_url'] ?? '/images/default-blog.jpeg' }}"
                                     alt="Blog thumbnail" loading="lazy">
                            </figure>

                            <div class="blog-content">
                                <div class="blog-meta">
{{--                                    <p class="blog-category">By User {{ $blogItem['user_id'] }}</p>--}}
                                    <span class="dot"></span>
                                    <time>{{ \Carbon\Carbon::parse($blogItem['created_at'])->format('M d, Y') }}</time>
                                </div>

                                <h3 class="h3 blog-item-title">{{ $blogItem['title'] }}</h3>

                                <p class="blog-text">
                                    {{ \Illuminate\Support\Str::limit(strip_tags($blogItem['contents']), 150) }}
                                </p>
                            </div>
                        </a>
                    </li>
                @endforeach
            </ul>
            @auth
                @if(auth()->user()->user_type == 0)
                    <div style="display:flex; justify-content:flex-end; margin-top:18px;">
                        <a href="{{ route('edit.blog') }}" class="testimonial-button">
                            <ion-icon name="add-outline" role="img" class="md hydrated" aria-label="Add"></ion-icon>
                            New Blog Item
                        </a>
                    </div>
                @endif
            @endauth
        </section>

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
