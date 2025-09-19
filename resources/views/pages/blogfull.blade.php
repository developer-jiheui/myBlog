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
            <h2 class="h2 article-title">{{$blogItem['title']}}</h2>

        </header>
        <div class="blog-container">
            <div class="blog-header">
                <div class="blog-category">
                    {{--                    TODO : categories of blog--}}
                    <p class=filter-item>
                </div>
                <div class="blog-btn-container">
                    @auth
                        @if(Auth::user()->USER_TYPE==0&&Auth::user()->id==$blogItem['user_id'])
                            <div class=project-full-interact>
                                <a class="icon-box" href="{{route('edit.blog', ['id' => $blogItem['blog_id']])}}">
                                    <ion-icon name="pencil-outline" role="img" class="md hydrated"
                                              aria-label="Edit"></ion-icon>
                                </a>
                                <form action="{{route('edit.blog.delete', ['id' => $blogItem['blog_id']])}}"
                                      method=post>
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
                </div>
            </div>
            <div class="blog-body">
                <div class="project-description">{!! $blogItem['contents'] !!}</div>
            </div>

            {{--        comments        --}}

            <div class="separator"></div>
            <div class="blog-footer">
                @php
                    $comments = \App\Models\Comment::where('blog_id','=',$_GET['id'])->get()->toArray();
                @endphp
                <div class="comment-count">
                    @if (empty($comments))
                        <div class="comment-count-text">No responses yet</div>
                    @else
                        <div class="comment-count-text">Responses ({{$comments->count()}})</div>
                    @endif
                </div>
                @auth
                    <div class="comment-input-container">
                        <div class="comment-user-info">
                            <div class="comment-avatar">
                                @if (Auth::user()->avatar)
                                    <img src="{{ asset(Auth::user()->avatar) }}" alt="avatar">
                                @else
                                    <img src="{{ asset('images/default-avatar.png') }}" alt="Default avatar">
                                @endif
                            </div>
                            <div class="comment-user">
                                @php
                                    $name = trim((Auth::user()->first_name ?? '') . ' ' . (Auth::user()->last_name ?? ''));
                                @endphp

                                @if ($name)
                                    {{ $name }}
                                @else
                                    write a response
                                @endif
                            </div>
                        </div>
                        <div class="comment-text-container">
                            <form method=post action="{{route('page.blog.comment')}}">
                                @csrf
                                <textarea name=content required class="comment-input-text"
                                          placeholder="what's your thoughts?"></textarea>
                                <input type=hidden name=blog_id value="{{$_GET['id']}}">
                                <div class="comment-btn-container">
                                    <button class="comment-submit-btn" type=submit>Respond</button>
                                </div>
                            </form>
                        </div>

                    </div>

                @endauth
                @guest
                    <div class="comment-input-container">
                        <div class="comment-user-info">
                            <div class="comment-avatar">
                                <img src="{{ asset('images/default-avatar.png') }}" alt="Default avatar">
                            </div>
                            <div class="comment-user">
                                write a response
                            </div>
                        </div>
                        <div class="comment-input-container">
                            <div role="textbox" class="comment-input-text" name=content>
                                what's your thoughts?
                            </div>
                            <div class="comment-btn-container">
                                <button class="comment-submit-btn" type=submit>Respond</button>
                            </div>
                        </div>

                    </div>

                @endguest

                <div class="comments-list">

                    @if (!empty($comments))
                        @foreach($comments as $comment)
                            @php
                                $commenter = \App\Models\User::find($comment['user_id']);
                            @endphp
                            <section class=blog-comment>
                                <h4>
                                    <figure class=avatar-box><img alt
                                                                  src="{{asset($commenter['avatar']??'images/my-avatar.png')}}">
                                    </figure> {{$commenter['first_name']}} {{$commenter['last_name']}} at
                                    <time>{{$comment['created_at']}}</time>
                                </h4>
                                @auth

                                    @if(Auth::user()->user_type==0)
                                        <form action="{{route('page.blog.comment.delete', ['id' => $comment['id']])}}"
                                              method=post>
                                            @csrf
                                            @method('delete')
                                            <button class="icon-box">
                                                <ion-icon name="trash-outline" role="img" class="md hydrated"
                                                          aria-label="Delete"></ion-icon>
                                            </button>
                                        </form>
                                    @endif
                                    @if(Auth::user()->id=$commenter['user_id'])
                                        <details>
                                            <summary class="icon-box">
                                                <ion-icon name="pencil-outline" role="img" class="md hydrated"
                                                          aria-label="Edit"></ion-icon>
                                            </summary>
                                            <form method=post
                                                  action="{{route('page.blog.comment.update',['id'=>$comment['comment_id']])}}">
                                                @csrf
                                                @method('patch')
                                                <fieldset>
                                                    <legend>Edit your comment&hellip;</legend>
                                                    <textarea name=content required>{{$comment['contents']}}</textarea>
                                                    <button type=submit>Edit comment</button>
                                                </fieldset>
                                            </form>
                                        </details>
                                    @endif
                                @endauth
                                <p>{{$comment['contents']}}
                            </section>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>


    </article>

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
