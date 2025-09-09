@extends('layouts.main')
@section('content')
    @php
        // Pull super admin (USER_TYPE = 0 ) and recent content
        $superAdmin = \App\Models\User::where('USER_TYPE', 0)->first();
        $latestBlogs = \App\Models\Blog::orderByDesc('CREATED_AT')->limit(3)->get();
        $recentWorks = \App\Models\Portfolio::orderByDesc('UPDATED_AT')->limit(6)->get();
    @endphp

    <article class="home  active" data-page="home">

        <header>
            <h2 class="h2 article-title">About me</h2>
        </header>

        <section class="about-text">
            <p>
                {{ $superAdmin->BIO ?? 'I build fast, modern web apps and elegant UI.' }}
            </p>

        </section>


        <!--
          - service
        -->

        <section class="service">

            <h3 class="h3 service-title">What i'm doing</h3>

            <ul class="service-list">

                <li class="service-item">

                    <div class="service-icon-box">
                        <img src="{{ asset('images/icon-design.svg') }}" alt="design icon" width="40">
                    </div>

                    <div class="service-content-box">
                        <h4 class="h4 service-item-title">Web design</h4>

                        <p class="service-item-text">
                            The most modern and high-quality design made at a professional level.
                        </p>
                    </div>

                </li>

                <li class="service-item">

                    <div class="service-icon-box">
                        <img src="{{ asset('images/icon-dev.svg') }}" alt="Web development icon" width="40">
                    </div>

                    <div class="service-content-box">
                        <h4 class="h4 service-item-title">Web development</h4>

                        <p class="service-item-text">
                            High-quality development of sites at the professional level.
                        </p>
                    </div>

                </li>

                <li class="service-item">

                    <div class="service-icon-box">
                        <img src="{{ asset('images/icon-app.svg') }}" alt="mobile app icon" width="40">
                    </div>

                    <div class="service-content-box">
                        <h4 class="h4 service-item-title">Mobile apps</h4>

                        <p class="service-item-text">
                            Professional development of applications for iOS and Android.
                        </p>
                    </div>

                </li>

                <li class="service-item">

                    <div class="service-icon-box">
                        <img src="{{ asset('images/icon-photo.svg') }}" alt="camera icon" width="40">
                    </div>

                    <div class="service-content-box">
                        <h4 class="h4 service-item-title">Photography</h4>

                        <p class="service-item-text">
                            I make high-quality photos of any category at a professional level.
                        </p>
                    </div>

                </li>

            </ul>

        </section>


        <!--
          - testimonials
        -->

        <section class="testimonials">

            <h3 class="h3 testimonials-title">Testimonials</h3>

            <ul class="testimonials-list has-scrollbar">

                <li class="testimonials-item">
                    <div class="content-card" data-testimonials-item>

                        <figure class="testimonials-avatar-box">
                            <img src="{{ asset('images/default-avatar.png') }}" alt="Daniel lewis" width="60" data-testimonials-avatar>
                        </figure>

                        <h4 class="h4 testimonials-item-title" data-testimonials-title>Daniel lewis</h4>

                        <div class="testimonials-text" data-testimonials-text>
                            <p>
                                Richard was hired to create a corporate identity. We were very pleased with the work done. She has a
                                lot of experience
                                and is very concerned about the needs of client. Lorem ipsum dolor sit amet, ullamcous cididt
                                consectetur adipiscing
                                elit, seds do et eiusmod tempor incididunt ut laborels dolore magnarels alia.
                            </p>
                        </div>

                    </div>
                </li>

                <li class="testimonials-item">
                    <div class="content-card" data-testimonials-item>

                        <figure class="testimonials-avatar-box">
                            <img src="{{ asset('images/default-avatar.png') }}" alt="Jessica miller" width="60" data-testimonials-avatar>
                        </figure>

                        <h4 class="h4 testimonials-item-title" data-testimonials-title>Jessica miller</h4>

                        <div class="testimonials-text" data-testimonials-text>
                            <p>
                                Richard was hired to create a corporate identity. We were very pleased with the work done. She has a
                                lot of experience
                                and is very concerned about the needs of client. Lorem ipsum dolor sit amet, ullamcous cididt
                                consectetur adipiscing
                                elit, seds do et eiusmod tempor incididunt ut laborels dolore magnarels alia.
                            </p>
                        </div>

                    </div>
                </li>

                <li class="testimonials-item">
                    <div class="content-card" data-testimonials-item>

                        <figure class="testimonials-avatar-box">
                            <img src="./assets/images/avatar-3.png" alt="Emily evans" width="60" data-testimonials-avatar>
                        </figure>

                        <h4 class="h4 testimonials-item-title" data-testimonials-title>Emily evans</h4>

                        <div class="testimonials-text" data-testimonials-text>
                            <p>
                                Richard was hired to create a corporate identity. We were very pleased with the work done. She has a
                                lot of experience
                                and is very concerned about the needs of client. Lorem ipsum dolor sit amet, ullamcous cididt
                                consectetur adipiscing
                                elit, seds do et eiusmod tempor incididunt ut laborels dolore magnarels alia.
                            </p>
                        </div>

                    </div>
                </li>

                <li class="testimonials-item">
                    <div class="content-card" data-testimonials-item>

                        <figure class="testimonials-avatar-box">
                            <img src="./assets/images/avatar-4.png" alt="Henry william" width="60" data-testimonials-avatar>
                        </figure>

                        <h4 class="h4 testimonials-item-title" data-testimonials-title>Henry william</h4>

                        <div class="testimonials-text" data-testimonials-text>
                            <p>
                                Richard was hired to create a corporate identity. We were very pleased with the work done. She has a
                                lot of experience
                                and is very concerned about the needs of client. Lorem ipsum dolor sit amet, ullamcous cididt
                                consectetur adipiscing
                                elit, seds do et eiusmod tempor incididunt ut laborels dolore magnarels alia.
                            </p>
                        </div>

                    </div>
                </li>

            </ul>

        </section>


        <!--
          - testimonials modal
        -->

        <div class="modal-container" data-modal-container>

            <div class="overlay" data-overlay></div>

            <section class="testimonials-modal">

                <button class="modal-close-btn" data-modal-close-btn>
                    <ion-icon name="close-outline"></ion-icon>
                </button>

                <div class="modal-img-wrapper">
                    <figure class="modal-avatar-box">
                        <img src="./assets/images/avatar-1.png" alt="Daniel lewis" width="80" data-modal-img>
                    </figure>

                    <img src="{{ asset("/images/icon-quote.svg") }}" alt="quote icon">
                </div>

                <div class="modal-content">

                    <h4 class="h3 modal-title" data-modal-title>Daniel lewis</h4>

                    <time datetime="2021-06-14">14 June, 2021</time>

                    <div data-modal-text>
                        <p>
                            Richard was hired to create a corporate identity. We were very pleased with the work done. She has a
                            lot of experience
                            and is very concerned about the needs of client. Lorem ipsum dolor sit amet, ullamcous cididt
                            consectetur adipiscing
                            elit, seds do et eiusmod tempor incididunt ut laborels dolore magnarels alia.
                        </p>
                    </div>

                </div>

            </section>

        </div>


        <!--
          - clients
        -->

        <section class="recent-works">
            <h3 class="h3 service-title">Recent works</h3>

            <div class="recent-scroll-wrap">

                <ul class="recent-list has-scrollbar" id="recent-list">
                    @foreach (\App\Models\Portfolio::latest()->take(12)->get() as $p)
                        <li class="recent-item">
                            <a href="{{ route('page.portfoliofull', ['id' => $p->PORTFOLIO_ID]) }}" class="recent-card">
                                <figure class="recent-thumb">
                                    <img
                                        src="{{ asset($p->IMAGE_URL ?? 'images/default-icon.svg') }}"
                                        alt="{{ $p->TITLE }}"
                                        loading="lazy"
                                    >
                                </figure>

                                <div class="recent-body">
                                    <h4 class="h5 recent-title">{{ $p->TITLE }}</h4>
                                    <p class="recent-desc">
                                        {{ \Illuminate\Support\Str::limit(strip_tags($p->DESCRIPTION), 80) }}
                                    </p>
                                </div>
                            </a>
                        </li>
                    @endforeach
                </ul>


            </div>
        </section>
    </article>


    {{--    <article class="home  active" data-page="home">--}}

{{--        <header>--}}
{{--            <!-- <h2 class="h2 article-title">About me</h2> -->--}}
{{--        </header>--}}

{{--        <section class="about-text">--}}
{{--        </section>--}}

{{--        <!----}}
{{--          - service--}}
{{--        -->--}}

{{--        <section class="service">--}}

{{--            <h3 class="h3 service-title">Latest Blogs</h3>--}}

{{--            <ul class="service-list">--}}
{{--                @foreach(\App\Models\Blog::latest()->take(4)->get() as $blogItem)--}}
{{--                    <a class="service-item" href="{{ route('page.blogfull',['id'=>$blogItem['BLOG_ID']]) }}">--}}
{{--                        <div class="service-icon-box">--}}
{{--                            <img src="{{ $blogItem['IMAGE_URL'] ?? '/images/default-blog.jpeg' }}" alt="project icon" style = "max-width: 120px; max-height: 120px;">--}}
{{--                        </div>--}}

{{--                        <div class="service-content-box">--}}
{{--                            <h4 class="h4 service-item-title">{{ $blogItem->TITLE }}</h4>--}}

{{--                            <p class="service-item-text">--}}
{{--                                {{ \Illuminate\Support\Str::limit(strip_tags($blogItem['CONTENTS']), 100) }}--}}
{{--                            </p>--}}

{{--                        </div>--}}
{{--                    </a>--}}


{{--                @endforeach--}}

{{--            </ul>--}}

{{--        </section>--}}


{{--        <!----}}
{{--          - clients--}}
{{--        -->--}}

{{--        <section class="clients">--}}

{{--            <h3 class="h3 clients-title">Recent works</h3>--}}

{{--            <ul class="clients-list has-scrollbar">--}}

{{--                @foreach(\App\Models\Portfolio::latest()->take(6)->get() as $portfolio)--}}

{{--                            <a href="{{  route('page.portfoliofull',['id'=> $portfolio->PORTFOLIO_ID])}}">--}}
{{--                                <img src="{{ asset($portfolio->IMAGE_URL ?? 'images/default-blog.png') }}"--}}
{{--                                     style="width: 150px; height: 150px; object-fit: cover; border-radius: 8px;">--}}
{{--                            </a>--}}
{{--                        </li>--}}
{{--                @endforeach--}}
{{--            </ul>--}}

{{--        </section>--}}
{{--    </article>--}}

@endsection
