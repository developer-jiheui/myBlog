@extends('layouts.main')
@section('content')
    <article class="blog-full active" data-page="blog">
        @php
            try {
                $blogItem = \App\Models\Blog::find(request('id'));
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
                            <div class=project-buttons>
                                <a class="icon-box" href="{{route('edit.blog', ['id' => $blogItem['blog_id']])}}">
                                    <ion-icon name="pencil-outline" role="img" class="md hydrated"
                                              aria-label="Edit"></ion-icon>
                                </a>
                                <form action="{{route('edit.blog.delete', ['id' => $blogItem['blog_id']])}}"
                                      method=post>
                                    @csrf
                                    @method('delete')
                                    <button class="icon-box delete-btn">
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
                    $comments = \App\Models\Comment::where('blog_id','=',request('id'))->get();
                @endphp
                <div class="comment-count">
                    @if (empty($comments))
                        <div class="comment-count-text">No responses yet</div>
                    @else
                        <div class="comment-count-text">Responses ( {{$comments->count()}} )</div>
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
                                    No Name
                                @endif
                            </div>
                        </div>
                        <div class="">
                            <form method=post action="{{route('page.blog.comment')}}" class="comment-text-container">
                                @csrf
                                <div class="comment-text-area">
                                <textarea name=content required class="comment-input-text"
                                          placeholder="what's your thoughts?"></textarea>
                                    <input type=hidden name=blog_id value="{{request('id')}}">
                                </div>
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
                        <div class="comment-text-container">
                            <form method=post action="{{route('page.blog.comment')}}">
                                @csrf
                                <textarea name=content required class="comment-input-text"
                                          placeholder="what's your thoughts?"></textarea>
                                <input type=hidden name=blog_id value="{{request('id')}}">
                                <div class="comment-btn-container">
                                    <button class="comment-submit-btn" type=submit>Respond</button>
                                </div>
                            </form>
                        </div>

                    </div>

                @endguest

                <div class="separator"></div>

                <div class="comments-list">

                    @if (!empty($comments))
                        @foreach($comments as $comment)
                            @php
                                $commenter = \App\Models\User::find($comment['user_id']);
                            @endphp
                            <div class="comment-item">
                                <div class="comment-header comment-user-info">
                                    <div class="comment-avatar">
                                        <img alt
                                             src="{{asset($commenter['avatar']??'images/default-avatar.png')}}">
                                    </div>
                                    <div class="commenter-info">
                                        <div class="commenter-name comment-user">
                                            @php
                                                $name = trim(($commenter->first_name ?? '') . ' ' . ($commenter->last_name ?? ''));
                                            @endphp

                                            @if ($name)
                                                {{ $name }}
                                            @else
                                                No Name
                                            @endif
                                        </div>
                                        <div class="comment-day">
                                            <time>{{ $comment->created_at->diffForHumans() }}</time>
                                        </div>
                                    </div>
                                    <div class="comment-btn-container">
                                        @auth
                                            <div class=project-buttons>
                                                {{--TODO : USE POLICIES--}}

                                                @if(Auth::user()->user_type==0||Auth::user()->id=$commenter->id)
                                                    <form
                                                        action="{{route('page.blog.comment.delete', ['id' => $comment['id']])}}"
                                                        method=post>
                                                        @csrf
                                                        @method('delete')
                                                        <button class="icon-box delete-btn">
                                                            <ion-icon name="trash-outline" role="img"
                                                                      class="md hydrated"
                                                                      aria-label="Delete"></ion-icon>
                                                        </button>
                                                    </form>
                                                @endif
                                                @if(Auth::user()->id==$commenter->id)
                                                    <details>
                                                        <summary class="icon-box">
                                                            <ion-icon name="pencil-outline" role="img"
                                                                      class="md hydrated"
                                                                      aria-label="Edit"></ion-icon>
                                                        </summary>
                                                        <form method=post
                                                              action="{{route('page.blog.comment.update',['id'=>$comment['comment_id']])}}">
                                                            @csrf
                                                            @method('patch')
                                                            <div class="comment-text-container">
                                                                <textarea name=content required
                                                                          class="comment-input-text"
                                                                          placeholder="{{$comment['contents']}}"></textarea>
                                                                <input type=hidden name=blog_id
                                                                       value="{{request('id')}}">
                                                                <div class="comment-btn-container">
                                                                    <button class="comment-submit-btn" type=submit>
                                                                        Edit
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </details>
                                                @endif
                                            </div>
                                        @endauth
                                    </div>
                                </div>
                                <div class="comment-body">
                                    {{$comment['contents']}}
                                </div>
                            </div>
                            <div class="separator"></div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>


    </article>

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
