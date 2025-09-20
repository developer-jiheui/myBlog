@extends('layouts.main')

@section('content')
    @php
        $isEdit = request()->has('id');
        $blogItem = $isEdit ? \App\Models\Blog::find(request('id')) : [];

        if (!$blogItem && $isEdit) {
            abort(404);
        }

        $user = Auth::user();

        if (!$user || ($isEdit && $user->id !== $blogItem->user_id) || (!$isEdit && $user->user_type != 0)) {
            abort(403); // Forbidden
        }
    @endphp

    <article class="active" data-page="blog">
        <header>
            <h2 class="h2 article-title">
                {{ $isEdit ? 'Editing Item' : 'Adding Item' }}
            </h2>
        </header>

        <form id="blog-form"
              class="form"
              action="{{ $isEdit ? route('edit.blog.update', ['id' => $blogItem->id]) : route('edit.blog.create') }}"
              method="post" enctype="multipart/form-data">
            @csrf
            @if($isEdit)
                @method('patch')
            @endif

            <div class="input-wrapper">
                <label for="title" class="form-label">Title</label>
                <input name="title" id="title" class="form-input"
                       value="{{ old('title', $blogItem->title ?? '') }}" required>
            </div>

            <input type="hidden" name="contents" id="hidden-contents">
            <div id="editor" style="height:300px;"></div>

            <button type="submit" class="form-btn" style="margin-right:auto;">
                {{ $isEdit ? 'Save Item' : 'Add Item' }}
            </button>
        </form>

        <!-- Quill Styles & Scripts -->
        <link href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>

        <script>
            function resizeImage(file, maxWidth = 600, callback) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    const img = new Image();
                    img.onload = function () {
                        const canvas = document.createElement('canvas');
                        const scaleFactor = maxWidth / img.width;
                        canvas.width = maxWidth;
                        canvas.height = img.height * scaleFactor;
                        const ctx = canvas.getContext('2d');
                        ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
                        callback(canvas.toDataURL('image/jpeg', 0.3));
                    };
                    img.src = e.target.result;
                };
                reader.readAsDataURL(file);
            }

            const quill = new Quill('#editor', {
                theme: 'snow',
                placeholder: 'Write your blog post...',
                modules: {
                    toolbar: [
                        [{header: [1, 2, false]}],
                        ['bold', 'italic', 'underline'],
                        ['image', 'link', 'code-block'],
                    ]
                }
            });

            // Load existing content if editing
            {{--quill.root.innerHTML = `{!! addslashes($blogItem['contents'] ?? '') !!}`;--}}
                quill.root.innerHTML = {!! json_encode(old('contents', $blogItem->contents ?? '')) !!};

            // Custom image upload + resize
            quill.getModule('toolbar').addHandler('image', () => {
                const input = document.createElement('input');
                input.setAttribute('type', 'file');
                input.setAttribute('accept', 'image/*');
                input.click();

                input.onchange = function () {
                    const file = input.files[0];
                    if (/^image\//.test(file.type)) {
                        resizeImage(file, 600, (resizedDataUrl) => {
                            const range = quill.getSelection();
                            quill.insertEmbed(range.index, 'image', resizedDataUrl);
                        });
                    }
                };
            });

            // Set content in hidden input on form submit
            document.getElementById('blog-form').addEventListener('submit', function () {
                document.getElementById('hidden-contents').value = quill.root.innerHTML.trim();
            });
        </script>
    </article>
@endsection
