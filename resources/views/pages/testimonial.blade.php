@extends('layouts.main')

@section('content')
    <article class="active" data-page="testimonials-dashboard">
        <header>
            <h2 class="h2 article-title">Testimonials</h2>
        </header>


        {{-- Grid of testimonial cards --}}
            <ul class="project-list" style="display:grid;grid-template-columns:repeat(auto-fill,minmax(260px,1fr));gap:16px;">
                @forelse($testimonials as $t)
                    <li class="project-item"
                        data-testimonials-item
                        style="background:var(--eerie-black-2); border:1px solid var(--jet); border-radius:14px; padding:14px;">

                        {{-- Header (avatar + name) --}}
                        <div style="display:flex; gap:10px; align-items:center; margin-bottom:8px; cursor:pointer;">
                            <img data-testimonials-avatar
                                 src="{{ $t->author_avatar_url ? asset($t->author_avatar_url) : asset('images/default-avatar.png') }}"
                                 alt="{{ $t->author_name }}"
                                 style="width:44px;height:44px;border-radius:12px;object-fit:cover;">
                            <div>
                                <div data-testimonials-title style="font-weight:700; color:var(--white-2);">
                                    {{ $t->author_name }}
                                    @if($t->pinned)
                                        <span style="font-size:12px;color:var(--orange-yellow-crayola);">• pinned</span>
                                    @endif
                                </div>
                                <small style="color:var(--light-gray-70);">{{ $t->author_title ?? '—' }}</small>
                            </div>
                        </div>

                        {{-- Body excerpt (clickable to open modal) --}}
                        <p data-testimonials-text
                           style="color:var(--light-gray); line-height:1.5; cursor:pointer; min-height:3.2em;">
                            {{ \Illuminate\Support\Str::limit($t->body, 160) }}
                        </p>

                        @php
                            $isOwner = (int) auth()->user()->id === (int) $t->author_user_id;
                        @endphp

                        <div style="display:flex; justify-content:space-between; align-items:center; margin-top:10px;">
                            <small style="color:var(--light-gray-70);">{{ $t->created_at->diffForHumans() }}</small>

                            <div style="display:flex; gap:10px; align-items:center;">
                                @if($isOwner)
                                    <button class="icon-box open-edit"
                                            data-edit
                                            data-update-url="{{ route('testimonials.update', $t) }}"
                                            data-author-title="{{ $t->author_title }}"
                                            data-body="{{ $t->body }}"
                                            style="color:#ffd166; background:rgba(255,255,255,.06); border:0; cursor:pointer; display:inline-flex; align-items:center; gap:6px; padding:6px 10px; border-radius:10px;">
                                        <ion-icon name="pencil-outline"></ion-icon>
                                    </button>

                                @endif

                                @if($isAdmin)
                                    <form method="POST" action="{{ route('testimonials.pin', $t) }}" style="margin:0;">
                                        @csrf
                                        <button class="icon-box" style="background:transparent;border:0;color:var(--orange-yellow-crayola);cursor:pointer;">
                                            <ion-icon name="{{ $t->pinned ? 'bookmark' : 'bookmark-outline' }}"></ion-icon>

                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </li>
                @empty
                    <p style="color:var(--light-gray-70);">No testimonials yet.</p>
                @endforelse
            </ul>

            {{-- Pagination --}}
            <div style="margin-top:16px;">
                {{ $testimonials->links() }}
            </div>

            {{-- Write button --}}
            <div style="display:flex; justify-content:flex-end; margin-top:18px;">
                <a href="{{ route('testimonials.create') }}" id="openCreateTestimonial" class="testimonial-button">
                    <ion-icon name="add-outline"></ion-icon> Write Testimonial
                </a>
            </div>



    {{-- Reuse your existing modal (one instance) --}}
    <div class="modal-container" data-modal-container>
        <div class="overlay" data-overlay></div>
        <section class="testimonials-modal">
            <button class="modal-close-btn" data-modal-close-btn>
                <ion-icon name="close-outline"></ion-icon>
            </button>
            <div class="modal-img-wrapper">
                <figure class="modal-avatar-box">
                    <img data-modal-img src="{{ asset('images/default-avatar.png') }}" alt="avatar" width="80">
                </figure>
                <img src="{{ asset('images/icon-quote.svg') }}" alt="" width="32">
            </div>
            <h4 class="modal-title" data-modal-title>—</h4>
            <div class="modal-content">
                <p data-modal-text>—</p>
            </div>
        </section>
    </div>

        {{-- CREATE TESTIMONIAL MODAL --}}
        <div class="modal-container" id="createModal">
            <div class="overlay" data-close="#createModal"></div>
            <section class="testimonials-modal">
                <button class="modal-close-btn" data-close="#createModal"><ion-icon name="close-outline"></ion-icon></button>

                <h4 class="modal-title">Write a Testimonial</h4>
                <form method="POST" action="{{ route('testimonials.store') }}" class="stack" style="margin-top:10px;">
                    @csrf
                    <label class="field">
                        <span>Your Title (optional)</span>
                        <input type="text" name="author_title" value="{{ old('author_title') }}" maxlength="120">
                    </label>

                    <label class="field">
                        <span>Message</span>
                        <textarea name="body" rows="6" required maxlength="5000">{{ old('body') }}</textarea>
                    </label>

                    {{-- show pin only if admin (optional) --}}
                    @if(!empty($isAdmin) && $isAdmin)
                        <label class="field" style="display:flex;align-items:center;gap:8px;">
                            <input type="checkbox" name="pinned" value="1"> <span>Pin</span>
                        </label>
                    @endif

                    <div class="actions">
                        <button type="submit" class="btn primary">Publish</button>
                    </div>
                </form>
            </section>
        </div>

        {{-- EDIT TESTIMONIAL MODAL --}}
        <div class="modal-container" id="editModal">
            <div class="overlay" data-close="#editModal"></div>
            <section class="testimonials-modal">
                <button class="modal-close-btn" data-close="#editModal"><ion-icon name="close-outline"></ion-icon></button>

                <h4 class="modal-title">Edit Testimonial</h4>
                <form id="editForm" method="POST" action="#" class="stack" style="margin-top:10px;">
                    @csrf
                    @method('PUT')
                    <label class="field">
                        <span>Your Title (optional)</span>
                        <input type="text" name="author_title" id="edit_author_title" maxlength="120">
                    </label>

                    <label class="field">
                        <span>Message</span>
                        <textarea name="body" rows="6" id="edit_body" required maxlength="5000"></textarea>
                    </label>

                    <div class="actions">
                        <button type="submit" class="btn primary">Save</button>
                    </div>
                </form>
            </section>
        </div>


    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const items = document.querySelectorAll('[data-testimonials-item]');
            const container = document.querySelector('[data-modal-container]');
            const closeBtn = document.querySelector('[data-modal-close-btn]');
            const overlay = document.querySelector('[data-overlay]');
            const img = document.querySelector('[data-modal-img]');
            const title = document.querySelector('[data-modal-title]');
            const text = document.querySelector('[data-modal-text]');

            const open = () => { container.classList.add('active'); };
            const close = () => { container.classList.remove('active'); };

            items.forEach(li => {
                li.addEventListener('click', (e) => {
                    // ignore clicks on action buttons inside the card
                    if (e.target.closest('form') || e.target.closest('a.icon-box')) return;

                    img.src = li.querySelector('[data-testimonials-avatar]').src;
                    img.alt = li.querySelector('[data-testimonials-avatar]').alt;
                    title.innerHTML = li.querySelector('[data-testimonials-title]').innerHTML;
                    text.innerHTML  = li.querySelector('[data-testimonials-text]').innerHTML;
                    open();
                });
            });

            closeBtn?.addEventListener('click', close);
            overlay?.addEventListener('click', close);
            document.addEventListener('keydown', e => e.key === 'Escape' && close());
        });

        document.addEventListener('DOMContentLoaded', () => {
            const openCreateBtn = document.getElementById('openCreateTestimonial');
            const createModal   = document.getElementById('createModal');
            const editModal     = document.getElementById('editModal');

            const open = (el) => el?.classList.add('active');
            const close = (el) => el?.classList.remove('active');

            // Close handlers (overlay & X buttons)
            document.querySelectorAll('[data-close]').forEach(btn => {
                btn.addEventListener('click', () => {
                    const sel = btn.getAttribute('data-close');
                    const target = document.querySelector(sel);
                    close(target);
                });
            });

            // Create
            openCreateBtn?.addEventListener('click', (e) => {
                e.preventDefault();
                open(createModal);
            });

            // Edit: use the data-* from the card button
            document.querySelectorAll('[data-edit]').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    e.stopPropagation(); // keep card click from opening the read-only modal
                    const url   = btn.getAttribute('data-update-url');
                    const title = btn.getAttribute('data-author-title') || '';
                    const body  = btn.getAttribute('data-body') || '';

                    const form  = document.getElementById('editForm');
                    form.action = url;
                    document.getElementById('edit_author_title').value = title;
                    document.getElementById('edit_body').value = body;

                    open(editModal);
                });
            });

            // ESC to close any open modal
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') {
                    close(createModal);
                    close(editModal);
                }
            });
        });
    </script>

        @if ($errors->any())
            <script>
                // Heuristic: if the old request had PUT, it was edit; otherwise create
                @if (strtoupper(request()->method()) === 'PUT' || old('_method') === 'PUT')
                document.addEventListener('DOMContentLoaded', () => document.getElementById('editModal').classList.add('active'));
                @else
                document.addEventListener('DOMContentLoaded', () => document.getElementById('createModal').classList.add('active'));
                @endif
            </script>
    @endif
@endsection
