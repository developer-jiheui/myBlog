@extends('layouts.main')
@section('content')
<article class="blog-full active" data-page="blog">
        @php
            try {
                $blogItem = \App\Models\Blog::find($_GET['id']);
            }
            catch (Exception $e) {
                http_response_code(404);
                die();
            }
        @endphp

        <header>
        <h2 class="h2 article-title">{{$blogItem['TITLE']}}</h2>
    <p class=filter-item>
    @auth
            @if(Auth::user()->USER_TYPE==0&&Auth::user()->USER_ID==$blogItem['USER_ID'])
            <div class=project-full-interact>
                    <a class="icon-box" href="{{route('edit.blog', ['id' => $blogItem['BLOG_ID']])}}">
                        <ion-icon name="pencil-outline" role="img" class="md hydrated" aria-label="Edit"></ion-icon>
                    </a>
                    <form action="{{route('edit.blog.delete', ['id' => $blogItem['BLOG_ID']])}}" method=post>
                        @csrf
                        @method('delete')
                    <button class="icon-box">
                        <ion-icon name="trash-outline" role="img" class="md hydrated" aria-label="Delete"></ion-icon>
                    </button>
                    </form>
    </div>
            @endif
        @endauth
    </header>
        <div class="project-description">{!! $blogItem['CONTENTS'] !!}</div>
        <h3>Comments</h3> {{-- semantically this should be an H2 and the page should start with an H1. --}}
        @auth
        <form method=post action="{{route('page.blog.comment')}}">
            @csrf
            <fieldset>
                <legend>Add a comment&hellip;</legend>
                <textarea name=content required></textarea>
                <input type=hidden name=blog_id value="{{$_GET['id']}}">
                <button type=submit>Add comment</button>
            </fieldset>
        </form>
        @endauth
    @php
            $comments = \App\Models\Comment::where('BLOG_ID','=',$_GET['id'])->get()->toArray();
        @endphp
        @if (empty($comments))
            <p>No comments.
        @else
            @foreach($comments as $comment)
                @php
                    $commenter = \App\Models\User::find($comment['USER_ID']);
                @endphp
            <section class=blog-comment>
                <h4><figure class=avatar-box><img alt src="{{asset($commenter['AVATAR']??'images/my-avatar.png')}}"></figure> {{$commenter['FIRST_NAME']}} {{$commenter['LAST_NAME']}} at <time>{{$comment['CREATED_AT']}}</time></h4>
                    @auth
                    @if(Auth::user()->USER_TYPE==0)
                    <form action="{{route('page.blog.comment.delete', ['id' => $comment['COMMENT_ID']])}}" method=post>
                            @csrf
                            @method('delete')
                        <button class="icon-box">
                            <ion-icon name="trash-outline" role="img" class="md hydrated" aria-label="Delete"></ion-icon>
                        </button>
                        </form>
                    @endif
                    @if(Auth::user()->USER_ID=$commenter['USER_ID'])
                    <details>
                        <summary class="icon-box">
                            <ion-icon name="pencil-outline" role="img" class="md hydrated" aria-label="Edit"></ion-icon>
                        </summary>
                        <form method=post action="{{route('page.blog.comment.update',['id'=>$comment['COMMENT_ID']])}}">
                            @csrf
                            @method('patch')
                            <fieldset>
                                <legend>Edit your comment&hellip;</legend>
                                <textarea name=content required>{{$comment['CONTENTS']}}</textarea>
                                <button type=submit>Edit comment</button>
                            </fieldset>
                        </form>
                    </details>
                    @endif
                    @endauth
                <p>{{$comment['CONTENTS']}}
            </section>
            @endforeach
        @endif
    </article>
@guest
    {{-- Show login button when user is not logged in --}}
    <a href="{{ route('page.show', ['name' => 'login']) }}" class="edit-page-button">
        <ion-icon name="log-in-outline" role="img" aria-label="Login"></ion-icon>
        Log In
    </a>
@endguest

@auth
    {{-- Show profile photo when logged in --}}
    <a href="{{ route('page.show', ['name' => 'profile']) }}" class="edit-page-button">
        @if(Auth::user()->AVATAR)
            <img src="{{ asset(Auth::user()->AVATAR) }}"
                 alt="Profile"
                 style="width:40px; height:40px; border-radius:50%; object-fit:cover;">
        @else
            <ion-icon name="person-circle-outline" role="img" aria-label="Profile"></ion-icon>
        @endif
    </a>
@endauth
@endsection
