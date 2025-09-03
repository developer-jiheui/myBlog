@section('navbar')
    <nav class="navbar">
        <ul class="navbar-list">
            <li class="navbar-item">
                <a href="{{ route('page.show', ['name' => 'home']) }}"
                   class="navbar-link {{ (request()->routeIs('page.show') && request()->route('name') === 'home') ? 'active' : '' }}"
                   data-nav-link>Home</a>
            </li>

            <li class="navbar-item">
                <a href="{{ route('page.show', ['name' => 'experience']) }}"
                   class="navbar-link {{ (request()->routeIs('page.show') && request()->route('name') === 'experience') ? 'active' : '' }}"
                   data-nav-link>Experience</a>
            </li>

            <li class="navbar-item">
                <a href="{{ route('page.show', ['name' => 'portfolio']) }}"
                   class="navbar-link {{ (request()->routeIs('page.show') && request()->route('name') === 'portfolio') ? 'active' : '' }}"
                   data-nav-link>Portfolio</a>
            </li>

            <li class="navbar-item">
                <a href="{{ route('page.show', ['name' => 'blog']) }}"
                   class="navbar-link {{ (request()->routeIs('page.show') && request()->route('name') === 'blog') ? 'active' : '' }}"
                   data-nav-link>Blog</a>
            </li>

            <li class="navbar-item">
                <a href="{{ route('page.show', ['name' => 'contact']) }}"
                   class="navbar-link {{ (request()->routeIs('page.show') && request()->route('name') === 'contact') ? 'active' : '' }}"
                   data-nav-link>Contact</a>
            </li>
        </ul>
    </nav>
@endsection
