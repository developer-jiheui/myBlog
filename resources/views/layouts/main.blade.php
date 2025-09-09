@include('layouts.header')
@yield('header')

<!--
  - #MAIN
-->

<main>

    <!--
      - #SIDEBAR
    -->
    @include('layouts.sidebar')
    @yield('sidebar')

    <!--
      - #main-content
    -->

    <div class="main-content">
        {{--TODO : authenticated user type and change it to admin navbar or normal user navbar        --}}

            @include('layouts.navbar')
            @yield('navbar')


        <!--
          - #PAGE CONTENTS
        -->
        @yield('content')


        @auth
            <button type="button" class="profile-button" aria-label="Open profile" data-open-profile>
                @if (Auth::user()->avatar)
                    <img src="{{ asset(Auth::user()->avatar) }}" alt="avatar" width="80">
                @else
                    <img src="{{ asset('images/default-avatar.png') }}" alt="Default avatar" width="80">
                @endif
            </button>
            @include('layouts.user-nav')
        @endauth

        @guest
            <a href="{{ route('page.show', ['name' => 'login']) }}" class="edit-page-button">
                <ion-icon name="log-in-outline" role="img" aria-label="Login"></ion-icon>
                Log In
            </a>
        @endguest


    </div>

</main>

@include('layouts.footer')
@yield('footer')
