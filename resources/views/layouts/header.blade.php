<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Developer Jiheui</title>

    <!--
      - favicon
    -->
    <link rel="shortcut icon" href="{{asset('images/logo.png') }}" type="image/x-icon">

    <!--
      - custom css link
    -->
    <link rel="stylesheet" href="{{asset('/css/style.css') }}">

    <!--
      - google font link
    -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

    <!--
        text editor
    -->
    <!-- Include stylesheet -->
    <link href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" rel="stylesheet"/>


</head>

<body>
{{--<div id="btn-container">--}}
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
{{--</div>--}}

