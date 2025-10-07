{{--            NOTION--}}
@extends('layouts.main')
@section('content')

    <article class="active" data-page="portfolio">
        <header>
            <h2 class="h2 article-title">Portfolio</h2>
        </header>

        @if(empty($projects))
            <p style="color:var(--light-gray);">No projects yet.</p>
        @else
            @php
                // Collect all unique tech tags from projects
                $allTechs = collect($projects)
                    ->pluck('tech')        // get the tech arrays
                    ->flatten()            // flatten into one array
                    ->unique()             // remove duplicates
                    ->sort()               // sort alphabetically
                    ->values();            // reindex
            @endphp
                <!-- Filter list-->
            <ul class="filter-list">
                <li class="filter-item">
                    <button data-filter-btn class="active" data-category="all">All</button>
                </li>

                @foreach($allTechs as $tech)
                    <li class="filter-item">
                        <button data-filter-btn data-category="{{ Str::slug($tech) }}">
                            {{ $tech }}
                        </button>
                    </li>
                @endforeach
            </ul>
            <div class="filter-select-box">
                <button class="filter-select" data-select="">
                    <div class="select-value" data-selecct-value="">Web</div>

                    <div class="select-icon">
                        <ion-icon name="chevron-down" role="img" class="md hydrated"
                                  aria-label="chevron down"></ion-icon>
                    </div>
                </button>

                <ul class="select-list">
                    <li class="select-item">
                        <button data-select-item data-category="all">All</button>
                    </li>

                    @foreach($allTechs as $tech)
                        <li class="select-item">
                            <button data-select-item data-category="{{ Str::slug($tech) }}">
                                {{ $tech }}
                            </button>
                        </li>
                    @endforeach
                </ul>
            </div>

            <!-- PROJECT LIST -->
            <ul class="project-list">
                @foreach($projects as $p)
                    <li class="project-item"
                        data-filter-item
                        data-category="{{ collect($p['tech'])->map(fn($t) => Str::slug($t))->implode(' ') }}"><a
                            href="{{ route('page.portfoliofull', ['key' => $p['slug'] ?: $p['id']]) }}">
                            <figure class="project-img">
                                <div class="project-item-icon-box">
                                    <ion-icon name="eye-outline" role="img" class="md hydrated"
                                              aria-label="eye outline"></ion-icon>
                                </div>
                                <img src="{{ $p['cover']?? '/images/default-blog.jpg' }}" alt="{{ $p['name'] }}"
                                     loading="lazy">
                            </figure>
                            <div class="project-content">
                                <div class="project-header">
                                    <h3 class="project-title">{{ $p['name'] }}</h3>
                                    <div class="project-icons">
                                        @if($p['github'])
                                            <div class="social-item">
                                                <a href="{{ $p['github'] }}" class="social-link" target="_blank"
                                                   rel="noopener">
                                                    <ion-icon name="logo-github"></ion-icon>
                                                </a>
                                            </div>
                                        @endif
                                        @if($p['url'])
                                            <div class="social-item">
                                                <a href="{{ $p['url'] }}" class="social-link" target="_blank"
                                                   rel="noopener">
                                                    <ion-icon name="link-outline"></ion-icon>
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                @if(!empty($p['tech']))
                                    <div class="project-techs">
                                        @foreach($p['tech'] as $tag)
                                            <span class="project-tech">
                                            {{ $tag }}
                                        </span>
                                        @endforeach
                                    </div>
                                @endif

                                <p class="project-desc">{{ $p['summary'] ?? ''}}</p>
                            </div>
                        </a>
                    </li>
                @endforeach
            </ul>
        @endif
    </article>
@endsection
