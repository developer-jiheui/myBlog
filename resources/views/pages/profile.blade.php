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

        {{-- ===================== FORM ===================== --}}
        <form method="POST" action="{{ route('update.profile') }}" enctype="multipart/form-data"
              class="form register-form">
            @csrf

            {{-- Upload new profile photo --}}
            <div class="input-wrapper">
                <label for="profile_photo" class="form-label h5" style="margin-bottom: 0.5rem;">Profile Photo</label>
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <span id="image-name">No image selected</span>
                    <label class="icon-box" style="cursor: pointer;">
                        <ion-icon name="cloud-upload-outline" role="img" aria-label="Upload new icon…"></ion-icon>
                        <input type="file" name="profile_photo" id="profile_photo"
                               style="position: absolute; top: -999px;" accept="image/*">
                    </label>
                </div>
                @error('profile_photo')
                <p class="form-error" style="color:red;">{{ $message }}</p>
                @enderror

                <div id="image-preview" style="margin-top: 0; display: none; text-align: center;">
                    <img src="#" id="preview-img" alt="Image Preview" class="avatar-preview"
                         style="border-radius:50%; width:80px; height:80px; object-fit:cover;"/>
                </div>
            </div>

            {{-- RANDOM AVATAR --}}
            <div style="text-align:center; margin-top:1rem;">
                <button type="button" id="choose-avatar-btn" class="form-btn"
                        style="background:var(--jet); color:var(--white-2);">
                    🎲 Choose Random Avatar
                </button>
            </div>

            {{-- ===================== AVATAR MODAL ===================== --}}
            <div id="avatarModal" class="modal-container">
                <div class="overlay" id="closeModal"></div>
                <div class="modal-content">
                    <button class="modal-close-btn" id="closeModalBtn" style="float:right;">
                        <ion-icon name="close-outline"></ion-icon>
                    </button>
                    <h3 style="color: var(--orange-yellow-crayola); margin-bottom: 1rem;">Select an Avatar</h3>
                    <div id="avatarGrid" class="avatar-grid">
                        @php
                            $avatars = File::files(public_path('images/avatars'));
                        @endphp
                        @foreach($avatars as $avatar)
                            @php
                                $avatarPath = 'images/avatars/' . basename($avatar);
                            @endphp
                            <img src="{{ asset($avatarPath) }}" alt="Avatar" class="avatar-option"
                                 data-avatar="{{ $avatarPath }}">
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- ===================== INPUT FIELDS ===================== --}}
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
                        <input type="text" id="autocomplete" name="address" class="form-input"
                               value="{{ old('address', Auth::user()->address) }}" placeholder="Address"
                               autocomplete="off">
                    @else
                        <input
                            type="{{ in_array($field, ['email']) ? 'email' : ($field === 'password' || $field === 'password_confirmation' ? 'password' : ($field === 'birthday' ? 'date' : 'text')) }}"
                            id="{{ $field }}" name="{{ $field }}" class="form-input"
                            value="{{ in_array($field, ['password', 'password_confirmation']) ? '' : old($field, Auth::user()->{$field}) }}"
                            placeholder="{{ in_array($field, ['password', 'password_confirmation']) ? $label : '' }}">
                    @endif
                    @error($field)
                    <small class="text-danger" style="color:red">{{ $message }}</small>
                    @enderror
                </div>

            @endforeach

            <div class="input-wrapper" style="display: flex; justify-content: center; margin-top: 1.5rem;">
                <button type="submit" class="form-btn login-highlight">Edit Profile</button>
            </div>
        </form>

        {{-- Logout --}}
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <div class="input-wrapper" style="display: flex; justify-content: center; margin-top: 1.5rem;">
                <button type="submit" class="form-btn login-highlight">Log Out</button>
            </div>
        </form>

    </article>

    {{-- ===================== MODAL CSS ===================== --}}
    <style>
        .modal-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            display: none;
            justify-content: center;
            align-items: center;
            background: rgba(0, 0, 0, 0.6);
            z-index: 9999;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .modal-container.active {
            display: flex;
            opacity: 1;
        }

        .overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 9998;
        }

        .modal-content {
            background: var(--eerie-black-2);
            border-radius: 16px;
            border: 1px solid var(--jet);
            padding: 1.5rem;
            max-width: 600px;
            width: 90%;
            color: var(--white-2);
            z-index: 10000;
            position: relative;
            animation: fadeIn 0.3s ease;
        }

        .avatar-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(80px, 1fr));
            gap: 12px;
        }

        .avatar-option {
            cursor: pointer;
            border-radius: 50%;
            width: 80px;
            height: 80px;
            object-fit: cover;
            border: 2px solid transparent;
            transition: transform 0.2s ease, border-color 0.2s ease;
        }

        .avatar-option:hover {
            transform: scale(1.1);
            border-color: var(--orange-yellow-crayola);
        }

        @keyframes fadeIn {
            from {
                transform: translateY(20px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }
    </style>

    {{-- ===================== MODAL JS ===================== --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const modal = document.getElementById('avatarModal');
            const openBtn = document.getElementById('choose-avatar-btn');
            const closeBtns = [document.getElementById('closeModal'), document.getElementById('closeModalBtn')];
            const preview = document.getElementById('preview-img');
            const imagePreview = document.getElementById('image-preview');
            const avatarGrid = document.getElementById('avatarGrid');

            console.log("openBtn found:", openBtn);
            if (!openBtn) return;

            openBtn.addEventListener('click', () => {
                console.log("Opening modal...");
                modal.classList.add('active');
                document.body.style.overflow = 'hidden';
            });

            closeBtns.forEach(btn => {
                if (btn) {
                    btn.addEventListener('click', () => {
                        modal.classList.remove('active');
                        document.body.style.overflow = '';
                    });
                }
            });

            avatarGrid.addEventListener('click', (e) => {
                if (e.target.classList.contains('avatar-option')) {
                    const selectedAvatar = e.target.dataset.avatar;

                    document.querySelectorAll('.avatar-option').forEach(img => {
                        img.style.border = '2px solid transparent';
                    });
                    e.target.style.border = '2px solid var(--orange-yellow-crayola)';

                    preview.src = e.target.src;
                    imagePreview.style.display = 'block';

                    let hiddenInput = document.getElementById('selected_avatar');
                    if (!hiddenInput) {
                        hiddenInput = document.createElement('input');
                        hiddenInput.type = 'hidden';
                        hiddenInput.name = 'selected_avatar';
                        hiddenInput.id = 'selected_avatar';
                        document.querySelector('.register-form').appendChild(hiddenInput);
                    }
                    hiddenInput.value = selectedAvatar;

                    // fade-out effect
                    setTimeout(() => {
                        modal.classList.remove('active');
                        document.body.style.overflow = '';
                    }, 400);
                }
            });
        });
    </script>

@endsection
