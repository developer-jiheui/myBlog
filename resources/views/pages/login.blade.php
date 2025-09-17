@extends('layouts.main')
@section('content')
    <article class="login active" data-page="login">
        <header>
            <h2 class="h2 article-title">Log in</h2>
        </header>

        <section class="content-card" style="max-width: 500px; margin: 2rem auto;">
            @if ($errors->any())
                <div class="alert alert-danger" style="color: red; padding: 10px; margin-bottom: 1rem;">
                    <ul style="margin: 0; padding-left: 20px;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <div style="display: flex; flex-direction: column; align-items: center; gap: 1rem; margin-top: 2rem;">

                <form method="POST" action="{{ route('login') }}" class="form login-form">
                    @csrf

                    <div class="input-wrapper">
                        <label for="email" class="form-label h5">Email</label>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            class="form-input"
                            required
                            placeholder="Enter your email"
                        >
                    </div>

                    <div class="input-wrapper">
                        <label for="password" class="form-label h5">Password</label>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            class="form-input"
                            required
                            placeholder="Enter your password"
                        >
                    </div>
                    <div class="input-wrapper" style="display: flex; justify-content: center; margin-top: 5rem;">
                        <div style="display: flex; justify-content: center;">
                            <button type="submit" class="form-btn login-highlight">Log In</button>
                        </div>
                    </div>
                </form>
                <p class="form-text" style="text-align: center; color: var(--light-gray); font-size: var(--fs-7);">
                    Don't have an account?
                    <a href="{{ route('page.show', ['name' => 'register']) }}"
                       style="color: var(--orange-yellow-crayola); font-weight: var(--fw-500);">Register</a>
                </p>
            </div>
        </section>
    </article>
    <x-modal id="loginModal" variant="warning"
             size="md" :openOnLoad="!($dismissedModals['login'] ?? false)">
        <x-slot:actions>
            <form method="POST" action="{{ route('guest.login') }}" id="warning-form">
                @csrf
                <button type="submit" class="guest-login-btn">Guest Login</button>
            </form>

            <label class="warning-dismiss">
                <input type="checkbox" data-dismiss-key="login" id="dont-show-login">
                Don’t show this message again
            </label>
        </x-slot:actions>
    </x-modal>

@endsection
