<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- CSRF + modal dismiss endpoint for JS --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="modal-dismiss-url" content="{{ route('modal.dismiss') }}">

    <title>Developer Jiheui</title>

    {{-- Favicon (served from public/images) --}}
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

    {{-- Quill CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" rel="stylesheet">

    {{-- assets via Vite (source: resources/css/app.css, resources/js/app.js) --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Page-specific head injections --}}
    @stack('head')
</head>
<body>
{{-- Auth-only profile button + user menu --}}
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

{{-- Guest login CTA --}}
@guest
    <a href="{{ route('page.show', ['name' => 'login']) }}" class="edit-page-button">
        <ion-icon name="log-in-outline" role="img" aria-label="Login"></ion-icon>
        Log In
    </a>
@endguest


