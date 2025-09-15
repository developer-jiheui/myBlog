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


    </div>

</main>

@include('layouts.footer')
@yield('footer')
