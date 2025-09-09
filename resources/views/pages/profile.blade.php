@extends('layouts.main')
@section('content')

    <article class="edit_profile active" data-page="edit_profile">

        <header>
            <h2 class="h2 article-title">MY PROFILE</h2>
        </header>

        <div class="sidebar-info">
            <figure class="avatar-box">
                @if (Auth::user()->avatar)
                    <img src="{{ asset(Auth::user()->avatar) }}" alt="avatar" width="80">
                @else
                    <img src="{{ asset('images/default-avatar.png') }}" alt="Default avatar" width="80">
                @endif
            </figure>

            <div class="info-content">
                <h1 class="name" title="my-name">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</h1>
            </div>
        </div>

        <div class="separator"></div>

        <form method="POST" action="{{ route('update.profile') }}" enctype="multipart/form-data" class="form register-form">
            @csrf

            <div class="input-wrapper">
                <label for="profile_photo" class="form-label h5" style="margin-bottom: 0.5rem;">Profile Photo</label>
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <span id="image-name">No image selected</span>
                    <label class="icon-box" style="cursor: pointer;">
                        <ion-icon name="cloud-upload-outline" role="img" aria-label="Upload new icon…"></ion-icon>
                        <input type="file" name="profile_photo" id="profile_photo" style="position: absolute; top: -999px;" accept="image/*">
                    </label>
                </div>
                @error('profile_photo')
                <div></div><p class="form-error">{{ $message }}</p>
                @enderror
                <div id="image-preview" style="margin-top: 0; display: none; text-align: center;">
                    <img src="#" id="preview-img" alt="Image Preview" class="avatar-preview" />
                </div>
            </div>

            @foreach ([
                      'first_name' => 'First Name',
                      'last_name' => 'Last Name',
                      'password' => 'Password',
                      'password_confirmation' => 'Confirm Password',
                      'address' => 'Address',
                      'phone_num' => 'Phone Number',
                      'job_title' => 'Job Title',
                      'birthday' => 'Birthday',
                  ] as $field => $label)

                <div class="input-wrapper">
                    <label for="{{ $field }}" class="form-label h5">{{ $label }}</label>
                    @if ($field === 'address')
                        <input
                            type="text"
                            id="autocomplete"
                            name="address"
                            class="form-input"
                            value="{{ old('address', Auth::user()->address) }}"
                            placeholder="Address"
                            autocomplete="off"
                        >
                        @error('address')
                        <div></div><small class="text-danger" style="color:red">{{ $message }}</small>
                        @enderror
                    @else
                        <input
                            type="{{ in_array($field, ['email']) ? 'email' : ($field === 'password' || $field === 'password_confirmation' ? 'password' : ($field === 'birthday' ? 'date' : 'text')) }}"
                            id="{{ $field }}"
                            name="{{ $field }}"
                            class="form-input"
                            value="{{ in_array($field, ['password', 'password_confirmation']) ? '' : old($field, Auth::user()[strtoupper($field)]) }}"
                            placeholder="{{ in_array($field, ['password', 'password_confirmation']) ? $label : '' }}"
                        >
                        @error($field)
                        <div></div><small class="text-danger" style="color:red">{{ $message }}</small>
                        @enderror
                    @endif
                </div>

            @endforeach

            <div class="input-wrapper" style="display: flex; justify-content: center; margin-top: 1.5rem;">
                <button type="submit" class="form-btn login-highlight">Edit Profile</button>
            </div>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <div class="input-wrapper" style="display: flex; justify-content: center; margin-top: 1.5rem;">
                <button type="submit" class="form-btn login-highlight">Log Out</button>
            </div>
        </form>

    </article>

@endsection
