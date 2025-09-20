<div id="profile-modal"
     class="sheet-modal"
     data-modal-container
     aria-hidden="true"
     role="dialog"
     aria-modal="true"
     aria-labelledby="profile-modal-title">
    <div class="sheet-backdrop" data-close-profile></div>

    <div class="sheet-panel" role="document">
        <header class="sheet-header">
            <div class="sheet-title-wrap">
                <img class="sheet-avatar" src="{{ asset(Auth::user()->avatar ?? 'images/default-avatar.png') }}"
                     alt="Avatar">
                <div>
                    <h2 id="profile-modal-title">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</h2>
                    <p class="muted">{{ Auth::user()->job_title ?? 'Member' }}</p>
                </div>
            </div>
            <button class="sheet-close" data-close-profile aria-label="Close">
                <svg width="22" height="22" viewBox="0 0 24 24">
                    <path d="M18 6L6 18M6 6l12 12" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                </svg>
            </button>
        </header>

        <nav class="sheet-tabs" role="tablist" aria-label="Profile Tabs">
            <button class="tab-btn is-active" role="tab" aria-selected="true" data-tab="bio">Bio</button>
            <button class="tab-btn" role="tab" aria-selected="false" data-tab="testimonials">Testimonials</button>
            <button class="tab-btn" role="tab" aria-selected="false" data-tab="history">History</button>
        </nav>

        <div class="sheet-body">
            {{-- BIO --}}
            <section class="tab-pane is-active" id="tab-bio" role="tabpanel">
                <form method="POST" action="{{ route('update.profile') }}" enctype="multipart/form-data" class="stack">
                    @csrf @method('PUT')

                    <label class="field">
                        <span>Display Name</span>
                        <input type="text" name="name"
                               value="{{ old('name', trim(Auth::user()->first_name.' '.Auth::user()->last_name)) }}">
                    </label>

                    <label class="field">
                        <span>Job Title</span>
                        <input type="text" name="job_title" value="{{ old('job_title', Auth::user()->job_title) }}">
                    </label>

                    <label class="field">
                        <span>Bio</span>
                        <textarea name="bio" rows="4">{{ old('bio', Auth::user()->bio) }}</textarea>
                    </label>

                    <label class="field">
                        <span>Avatar</span>
                        <input type="file" name="avatar" accept="image/*">
                    </label>

                    <div class="actions">
                        <button type="submit" class="btn primary">Save</button>
                    </div>
                </form>
            </section>

            {{-- TESTIMONIALS --}}
            <section class="tab-pane" id="tab-testimonials" role="tabpanel">
                <form method="POST" action="
{{--                {{ route('testimonials.store') }}--}}
                " class="stack">
                    @csrf
                    <label class="field">
                        <span>Headline (optional)</span>
                        <input type="text" name="headline" placeholder="e.g., Great collaborator!">
                    </label>
                    <label class="field">
                        <span>Your Title (optional)</span>
                        <input type="text" name="author_title" placeholder="PM at Acme">
                    </label>
                    <label class="field">
                        <span>Message</span>
                        <textarea name="body" rows="5" required placeholder="Your experience…"></textarea>
                    </label>
                    <div class="actions">
                        <button type="submit" class="btn primary">Submit</button>
                    </div>
                </form>

                <div class="divider"></div>
                <h3 class="h3">Your recent testimonials</h3>
                <ul class="list">
                    @foreach(\App\Models\Testimonial::where('author_user_id', Auth::user()->id)->latest()->take(5)->get() as $t)
                        <li class="list-item">
                            <div class="truncate">{{ \Illuminate\Support\Str::limit($t->body, 120) }}</div>
                            <small class="muted">{{ $t->created_at->diffForHumans() }}</small>
                        </li>
                    @endforeach
                </ul>
            </section>

            {{-- HISTORY --}}
            <section class="tab-pane" id="tab-history" role="tabpanel">
                <div class="table-wrap">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>Signed In</th>
                            <th>Signed Out</th>
                            <th>IP</th>
                            <th>Session</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach(\App\Models\AccessHistory::where('user_id', Auth::user()->id)->latest('signed_in_at')->limit(20)->get() as $h)
                            <tr>
                                <td>{{ $h->signed_in_at }}</td>
                                <td>{{ $h->signed_out_at ?? '—' }}</td>
                                <td>{{ $h->ip ?? '—' }}</td>
                                <td class="truncate">{{ $h->session_id ?? '—' }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </section>
        </div>
    </div>
</div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const modal = document.getElementById('profile-modal');
        if (!modal) return;
        const openers = document.querySelectorAll('[data-open-profile]');
        const closers = modal.querySelectorAll('[data-close-profile]');
        const backdrop = modal.querySelector('.sheet-backdrop');
        const tabs = modal.querySelectorAll('.tab-btn');
        const panes = modal.querySelectorAll('.tab-pane');

        const open = e => {
            e?.preventDefault();
            modal.setAttribute('aria-hidden', 'false');
            document.body.style.overflow = 'hidden';
        };
        const close = () => {
            modal.setAttribute('aria-hidden', 'true');
            document.body.style.overflow = '';
        };

        openers.forEach(b => b.addEventListener('click', open));
        closers.forEach(b => b.addEventListener('click', close));
        backdrop?.addEventListener('click', close);
        document.addEventListener('keydown', e => (e.key === 'Escape' && modal.getAttribute('aria-hidden') === 'false') && close());

        tabs.forEach(btn => btn.addEventListener('click', () => {
            tabs.forEach(b => b.classList.remove('is-active'));
            panes.forEach(p => p.classList.remove('is-active'));
            btn.classList.add('is-active');
            modal.querySelector('#tab-' + btn.dataset.tab).classList.add('is-active');
        }));
    });
</script>

