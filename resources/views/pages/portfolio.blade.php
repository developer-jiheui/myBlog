@extends('layouts.main')
@section('content')
    <article class="active" data-page="portfolio">
        <header>
            <h2 class="h2 article-title">Portfolio</h2>
        </header>

        @if(empty($projects))
            <p style="color:var(--light-gray);">No projects yet.</p>
        @else
            <ul class="project-list"
                style="display:grid;grid-template-columns:repeat(auto-fill,minmax(260px,1fr));gap:16px;">
                @foreach($projects as $p)
                    <li class="project-item"
                        style="background:var(--eerie-black-2); border:1px solid var(--jet); border-radius:14px; overflow:hidden;">
                        <a href="{{ route('page.portfoliofull', ['key' => $p['slug'] ?: $p['id']]) }}"
                           style="text-decoration:none;">
                            @if($p['cover'])
                                <div style="aspect-ratio: 16/9; background:#111; overflow:hidden;">
                                    <img src="{{ $p['cover'] }}" alt="{{ $p['name'] }}"
                                         style="width:100%; height:100%; object-fit:cover; display:block;">
                                </div>
                            @endif
                            <div style="padding:14px;">
                                <h3 class="project-title"
                                    style="color:var(--white-2); font-size:1.05rem; margin:0 0 .4rem;">
                                    {{ $p['name'] }}
                                </h3>
                                @if($p['summary'])
                                    <p style="color:var(--light-gray); font-size:.95rem; line-height:1.35; margin:0 0 .6rem;">
                                        {{ $p['summary'] }}
                                    </p>
                                @endif

                                @if(!empty($p['tech']))
                                    <div style="display:flex; flex-wrap:wrap; gap:6px; margin-bottom:.6rem;">
                                        @foreach($p['tech'] as $tag)
                                            <span
                                                style="font-size:.8rem; padding:.2rem .5rem; border:1px solid var(--jet); border-radius:999px; color:var(--white-2);">
                      {{ $tag }}
                    </span>
                                        @endforeach
                                    </div>
                                @endif

                                <div style="display:flex; gap:10px; align-items:center;">
                                    @if($p['url'])
                                        <a href="{{ $p['url'] }}" target="_blank" class="btn"
                                           style="font-size:.9rem; color:var(--orange-yellow-crayola);">Live</a>
                                    @endif
                                    @if($p['github'])
                                        <a href="{{ $p['github'] }}" target="_blank" class="btn"
                                           style="font-size:.9rem; color:var(--orange-yellow-crayola);">GitHub</a>
                                    @endif
                                    @if($p['date'])
                                        <span
                                            style="margin-left:auto; font-size:.8rem; color:var(--smoky-black); opacity:.8;">
                    {{ \Illuminate\Support\Carbon::parse($p['date'])->format('Y-m-d') }}
                  </span>
                                    @endif
                                </div>
                            </div>
                        </a>
                    </li>
                @endforeach
            </ul>
        @endif
    </article>
@endsection
