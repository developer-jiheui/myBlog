@section('sidebar')
    <aside class="sidebar" data-sidebar>
        @php
            $sa = $superAdmin ?? null;

            $avatar   = ($sa && $sa->AVATAR) ? asset($sa->AVATAR) : asset('images/my-avatar.png');
            $first    = $sa->FIRST_NAME ?? 'Jiheui';
            $last     = $sa->LAST_NAME ?? 'Lee';
            $job      = $sa->JOB_TITLE ?? 'Full Stack Developer';

            $email    = $sa->EMAIL ?? 'developer.jiheuilee@gmail.com';

            $address  = $sa->ADDRESS ?? 'Vancouver, BC, Canada';

            // Socials (use your actual DB columns)
            $linkedin = $sa->LINKEDIN_URL ?? 'https://www.linkedin.com/in/jiheuilee/';
            $github   = $sa->GITHUB_URL   ?? 'https://github.com/developer-jiheui';
            $insta    = $sa->INSTAGRAM_URL ?? '#';
        @endphp

        <div class="sidebar-info">
            <figure class="avatar-box">
                <img src="{{ $avatar }}" alt="{{ e($first.' '.$last) }}" width="80">
            </figure>

            <div class="info-content">
                <h1 class="name" title="my-name">{{ $first }} {{ $last }}</h1>
                <p class="title">{{ $job }}</p>
            </div>

            <button class="info_more-btn" data-sidebar-btn>
                <span>Show Contacts</span>
                <ion-icon name="chevron-down"></ion-icon>
            </button>
        </div>

        <div class="sidebar-info_more">
            <div class="separator"></div>

            <ul class="contacts-list">
                <li class="contact-item">
                    <div class="icon-box"><ion-icon name="mail-outline"></ion-icon></div>
                    <div class="contact-info">
                        <p class="contact-title">Email</p>
                        <a href="mailto:{{ $email }}" class="contact-link">{{ $email }}</a>
                    </div>
                </li>


                <li class="contact-item">
                    <div class="icon-box"><ion-icon name="location-outline"></ion-icon></div>
                    <div class="contact-info">
                        <p class="contact-title">Location</p>
                        <address>{{ $address }}</address>
                    </div>
                </li>
            </ul>

            <div class="separator"></div>

            <ul class="social-list">
                <li class="social-item">
                    <a href="{{ $linkedin }}" class="social-link" target="_blank" rel="noopener">
                        <ion-icon name="logo-linkedin"></ion-icon>
                    </a>
                </li>
                <li class="social-item">
                    <a href="{{ $github }}" class="social-link" target="_blank" rel="noopener">
                        <ion-icon name="logo-github"></ion-icon>
                    </a>
                </li>
                <li class="social-item">
                    <a href="{{ $insta }}" class="social-link" target="_blank" rel="noopener">
                        <ion-icon name="logo-instagram"></ion-icon>
                    </a>
                </li>
            </ul>
        </div>
    </aside>
@endsection
