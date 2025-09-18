@extends('layouts.main')

@section('content')
    <article class="admin active" data-page="admin">
        <header>
            <h2 class="h2 article-title">ADMIN</h2>
        </header>

        <table class="admin-table">
            <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                {{-- <th>Comment</th> --}}
                <th>User Type</th>
                <th>Action</th>
                <th>Delete</th>
            </tr>
            </thead>
            <tbody>
            @foreach($users as $user)
                @if($user->USER_ID != 1)
                    <tr>
                        <td>{{ $user->USER_ID }}</td>
                        <td>{{ $user->FIRST_NAME }} {{ $user->LAST_NAME }}</td>
                        <td>{{ $user->EMAIL }}</td>
                        {{-- <td>{{ $user->BIO ?? 'N/A' }}</td> --}}
                        <td>{{ $user->USER_TYPE === 0 ? 'Admin' : 'User' }}</td>

                        <td>
                            {{-- Promote / Demote --}}
                            @if($user->USER_TYPE !== 0)
                                <form method="POST" action="{{ route('admin.promote', $user->USER_ID) }}" onsubmit="return confirm('Are you sure you want to promote this user?');">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-success">Make Admin</button>
                                </form>
                            @else
                                <form method="POST" action="{{ route('admin.demote', $user->USER_ID) }}" onsubmit="return confirm('Are you sure you want to demote this user?');">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-warning">Make User</button>
                                </form>
                            @endif
                        </td>

                        <td>
                            @if($user->USER_ID !== auth()->id())
                                <form method="POST" action="{{ route('admin.delete', $user->USER_ID) }}" onsubmit="return confirm('Are you sure you want to delete this user?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endif
            @endforeach
            </tbody>
        </table>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <div style="display: flex; justify-content: center; margin-top: 2rem;">
                <button type="submit" class="form-btn login-highlight">Log Out</button>
            </div>
        </form>
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
