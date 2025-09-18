@extends('layouts.main')
@section('content')
    <article class="portfolio-full active" data-page="portfolio">
        @php
            try {
                $item = \App\Models\Portfolio::find($_GET['id']);
            }
            catch (Exception $e) {
                http_response_code(404); // doesn't return a page but whatever good enough
                die();
            }
        @endphp

        <header>
            <h2 class="h2 article-title">{{$item['title']}}</h2>
            <p class=filter-item><a
                    href="{{route('portfolio.index',['cat'=>$item['CATEGORY']])}}">{{$item['CATEGORY']}}</a><a
                    href="{{$item['PROJECT_URL']}}">{{$item['PROJECT_URL']}}</a>
            @auth
                @if(Auth::user()->USER_TYPE==0&&Auth::user()->USER_ID==$item['USER_ID'])
                    <div class=project-full-interact>
                        <a class="icon-box" href="{{route('edit.portfolio', ['id' => $item['PORTFOLIO_ID']])}}">
                            <ion-icon name="pencil-outline" role="img" class="md hydrated" aria-label="Edit"></ion-icon>
                        </a>
                        <form action="{{route('edit.portfolio.delete', ['id' => $item['PORTFOLIO_ID']])}}" method=post>
                            @csrf
                            @method('delete')
                            <button class="icon-box">
                                <ion-icon name="trash-outline" role="img" class="md hydrated"
                                          aria-label="Delete"></ion-icon>
                            </button>
                        </form>
                    </div>
                @endif
            @endauth
        </header>
        <img class=project-full-img alt src="{{asset($item['IMAGE_URL'])}}">
        <p class=project-description>{{$item['DESCRIPTION']}}</p>
    </article>
@endsection
