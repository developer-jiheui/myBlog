@extends('layouts.main')
@section('content')
    <article class="home  active" data-page="home">

        <header>
            <!-- <h2 class="h2 article-title">About me</h2> -->
        </header>

        <section class="about-text">
        </section>

        <!--
          - service
        -->

        <section class="service">

            <h3 class="h3 service-title">Latest Blogs</h3>

            <ul class="service-list">
                @foreach(\App\Models\Blog::latest()->take(4)->get() as $blogItem)
                    <a class="service-item" href="{{ route('page.blogfull',['id'=>$blogItem['BLOG_ID']]) }}">
                        <div class="service-icon-box">
                            <img src="{{ $blogItem['IMAGE_URL'] ?? '/images/default-blog.jpeg' }}" alt="project icon" style = "max-width: 120px; max-height: 120px;">
                        </div>

                        <div class="service-content-box">
                            <h4 class="h4 service-item-title">{{ $blogItem->TITLE }}</h4>

                            <p class="service-item-text">
                                {{ \Illuminate\Support\Str::limit(strip_tags($blogItem['CONTENTS']), 100) }}
                            </p>

                        </div>
                    </a>


                @endforeach

            </ul>

        </section>


        <!--
          - clients
        -->

        <section class="clients">

            <h3 class="h3 clients-title">Recent works</h3>

            <ul class="clients-list has-scrollbar">

                @foreach(\App\Models\Portfolio::latest()->take(6)->get() as $portfolio)

                            <a href="{{  route('page.portfoliofull',['id'=> $portfolio->PORTFOLIO_ID])}}">
                                <img src="{{ asset($portfolio->IMAGE_URL ?? 'images/default-blog.png') }}"
                                     style="width: 150px; height: 150px; object-fit: cover; border-radius: 8px;">
                            </a>
                        </li>
                @endforeach
            </ul>

        </section>
    </article>
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
