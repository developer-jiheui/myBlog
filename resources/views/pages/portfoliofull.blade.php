@extends('layouts.main')
@section('content')
    <article class="active" data-page="portfoliofull">
        <header><h2 class="h2 article-title">{{ $project['name'] }}</h2></header>

        @if($project['cover'])
            <div
                style="aspect-ratio:16/9;background:#111;display:grid;place-items:center;overflow:hidden;border-radius:14px;margin-bottom:1rem;">
                <img src="{{ $project['cover'] }}" alt="{{ $project['name'] }}"
                     style="width:100%;height:100%;object-fit:cover;display:block;">
            </div>
        @endif

        {{-- Summary above the content (optional) --}}
        @if(!empty($project['summary']))
            <p style="color:var(--light-gray);font-size:1rem;line-height:1.6;margin-bottom:1rem;">
                {{ $project['summary'] }}
            </p>
        @endif

        {{-- Full Notion page content --}}
        <div class="notion-content prose" style="color:var(--white-2); line-height:1.7;">
            {!! $contentHtml !!} {{-- trusting Notion’s content we just built --}}
        </div>

        {{-- External links --}}
        <div style="display:flex;gap:10px;align-items:center;margin-top:16px;">
            @if($project['url'])
                <a href="{{ $project['url'] }}" target="_blank" class="btn" style="color:var(--orange-yellow-crayola);">Live</a>
            @endif
            @if($project['github'])
                <a href="{{ $project['github'] }}" target="_blank" class="btn"
                   style="color:var(--orange-yellow-crayola);">GitHub</a>
            @endif
        </div>
    </article>
@endsection

{{--@extends('layouts.main')--}}
{{--@section('content')--}}
{{--    <article class="portfolio-full active" data-page="portfolio">--}}
{{--        @php--}}
{{--            try {--}}
{{--                $item = \App\Models\Portfolio::find($_GET['id']);--}}
{{--            }--}}
{{--            catch (Exception $e) {--}}
{{--                http_response_code(404); // doesn't return a page but whatever good enough--}}
{{--                die();--}}
{{--            }--}}
{{--        @endphp--}}

{{--        <header>--}}
{{--            <h2 class="h2 article-title">{{$item['title']}}</h2>--}}
{{--            <p class=filter-item><a--}}
{{--                    href="{{route('portfolio.index',['cat'=>$item['CATEGORY']])}}">{{$item['CATEGORY']}}</a><a--}}
{{--                    href="{{$item['PROJECT_URL']}}">{{$item['PROJECT_URL']}}</a>--}}
{{--            @auth--}}
{{--                @if(Auth::user()->USER_TYPE==0&&Auth::user()->USER_ID==$item['USER_ID'])--}}
{{--                    <div class=project-full-interact>--}}
{{--                        <a class="icon-box" href="{{route('edit.portfolio', ['id' => $item['PORTFOLIO_ID']])}}">--}}
{{--                            <ion-icon name="pencil-outline" role="img" class="md hydrated" aria-label="Edit"></ion-icon>--}}
{{--                        </a>--}}
{{--                        <form action="{{route('edit.portfolio.delete', ['id' => $item['PORTFOLIO_ID']])}}" method=post>--}}
{{--                            @csrf--}}
{{--                            @method('delete')--}}
{{--                            <button class="icon-box">--}}
{{--                                <ion-icon name="trash-outline" role="img" class="md hydrated"--}}
{{--                                          aria-label="Delete"></ion-icon>--}}
{{--                            </button>--}}
{{--                        </form>--}}
{{--                    </div>--}}
{{--                @endif--}}
{{--            @endauth--}}
{{--        </header>--}}
{{--        <img class=project-full-img alt src="{{asset($item['IMAGE_URL'])}}">--}}
{{--        <p class=project-description>{{$item['DESCRIPTION']}}</p>--}}
{{--    </article>--}}
{{--@endsection--}}
