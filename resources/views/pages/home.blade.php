@extends('layouts.main')
@section('content')
    @php
        // Pull super admin (USER_TYPE = 0 ) and recent content
        $superAdmin = \App\Models\User::where('USER_TYPE', 0)->first();
        $latestBlogs = \App\Models\Blog::orderByDesc('CREATED_AT')->limit(3)->get();
        $recentWorks = \App\Models\Portfolio::orderByDesc('UPDATED_AT')->limit(6)->get();
        $testimonials = \App\Models\Testimonial::where('pinned',1)->get();
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
                @forelse($testimonials as $t)
                    <li class="testimonials-item">
                        <div class="content-card" data-testimonials-item
                             data-author-title="{{ $t->author_title ?? '' }}">

                            <figure class="testimonials-avatar-box">
                                <img data-testimonials-avatar
                                     src="{{ $t->author_avatar_url ? asset($t->author_avatar_url) : asset('images/default-avatar.png') }}"
                                     alt="{{ $t->author_name }}"
                                     width="60" data-testimonials-avatar>
                            </figure>

                            <h4 class="h4 testimonials-item-title" data-testimonials-title>{{ $t->author_name }}</h4>

                            <div class="testimonials-text" data-testimonials-text>
                                <p>
                                    {{$t->body}}
                                </p>
                            </div>
                        </div>
                    </li>
                @empty
                    <p>
                        NO Testimonial yet
                    </p>
                @endforelse
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
                        <img src="" alt="" width="80" data-modal-img>
                    </figure>
                    <div class="modal-quote">
                        <img src="{{ asset("/images/icon-quote.svg") }}" alt="quote icon">
                    </div>
                </div>

                <div class="modal-content">
                    <h4 class="modal-title" data-modal-title></h4>
                    <p class="modal-author-title" data-modal-author-title></p>
                    <div class="modal-text" data-modal-text>
                        <p></p>
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
                            <a href="{{ route('page.portfoliofull', ['id' => $p->id]) }}" class="recent-card">
                                <figure class="recent-thumb">
                                    <img
                                        src="{{ asset($p->image_url ?? 'images/default-icon.svg') }}"
                                        alt="{{ $p->title }}"
                                        loading="lazy"
                                    >
                                </figure>

                                <div class="recent-body">
                                    <h4 class="h5 recent-title">{{ $p->title }}</h4>
                                    <p class="recent-desc">
                                        {{ \Illuminate\Support\Str::limit(strip_tags($p->description), 80) }}
                                    </p>
                                </div>
                            </a>
                        </li>
                    @endforeach
                </ul>


            </div>
        </section>


    </article>
    <div id="warningModal" class="warning-container" aria-hidden="true">
        <div class="warning-overlay" data-close></div>

        <section class="warning-panel content-card">
            <button class="modal-close-btn" data-close>
                <ion-icon name="close-outline"></ion-icon>
            </button>

            <h3 class="warning-title">
                🚧 Webpage building in progress 🚧
            </h3>

            <p class="warning-message">
                This site is currently under construction.
                <br><br>

                To preview the user experience without signing up, simply use the
                <strong>Guest Login</strong> button below.
                <br><br>
                👇This demo access is temporary and will be updated
            </p>

            <form id="guest-login-form" method="POST" action="{{ route('guest.login') }}">
                @csrf
                <button type="submit" class="guest-login-btn">
                    Guest Login
                </button>
            </form>
        </section>
    </div>

@endsection
