@extends('layouts.main')
@section('content')
    <article class="contact active" data-page="contact">

        <header>
            <h2 class="h2 article-title">Contact</h2>
        </header>

        <section class="mapbox" data-mapbox>

                    <figure>
                        @if($superAdmin && $superAdmin->ADDRESS)
                            <iframe
                                width="100%"
                                height="50%"
                                style="border:0"
                                referrerpolicy="no-referrer-when-downgrade"
                                loading="lazy"
                                src="https://www.google.com/maps/embed/v1/place?key=AIzaSyAJBgLFtPeoudAH2Wkaqn6lkQbY8TwZqxU&q={{ urlencode($superAdmin->ADDRESS) }}">
                            </iframe>
                        @else
                            <iframe
                                width="100%"
                                height="50%"
                                style="border:0"
                                referrerpolicy="no-referrer-when-downgrade"
                                loading="lazy"
                                src="https://www.google.com/maps/embed/v1/place?key=AIzaSyAJBgLFtPeoudAH2Wkaqn6lkQbY8TwZqxU&q=Vancouver,+BC,+Canada">
                            </iframe>
                        @endif
                    </figure>

        </section>

        <section class="contact-form">

            <h3 class="h3 form-title">Contact Form</h3>

            <form id="frm-contact-email"
                  class="form" data-form>

                <div class="input-wrapper">
                    <input type="text" id="sender-name" name="fullname" class="form-input" placeholder="Full name" required
                           data-form-input
                           @auth
                           value="{{Auth::user()->FIRST_NAME}}"
                        @endauth
                    >

                    <input type="email" id="sender-email" name="email" class="form-input" placeholder="Email address"
                           required
                           data-form-input
                           @auth
                           value="{{Auth::user()->EMAIL}}"
                        @endauth
                    >
                </div>

                <textarea name="message" id="email-content" class="form-input" placeholder="Your Message" required
                          data-form-input></textarea>

                <button class="form-btn" type="button" data-form-btn id="contact-btn">
                    <ion-icon name="paper-plane"></ion-icon>
                    <span>Send Message</span>
                </button>

            </form>

        </section>
    </article>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const senderName = document.getElementById("sender-name");
            const senderEmail = document.getElementById("sender-email");
            const emailContent = document.getElementById("email-content");
            const contactBtn = document.getElementById("contact-btn");

            contactBtn.addEventListener('click', () => {
                fetch("/send-email", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        senderName: senderName.value,
                        senderEmail: senderEmail.value,
                        emailContent: emailContent.value
                    })
                })
                    .then(response => {
                        if (!response.ok) {
                            return response.json().then(err => {
                                throw new Error(err.details || "Unknown error");
                            });
                        }
                        return response.json();
                    })
                    .then(data => {
                        alert(data.message);
                    })
                    .catch(error => {
                        console.error("❌ Error:", error.message);
                        alert("❌ Failed to send email: " + error.message);
                    });
            });
        });
    </script>
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
