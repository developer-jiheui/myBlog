@props([
  'id' => 'appModal',
  'title' =>"🚧 Webpage building in progress 🚧",
  'message' => null,
])
<div id="{{ $id }}" data-modal class="warning-container" aria-hidden="true">
    <div class="warning-overlay" data-close></div>

    <section
        class="warning-panel content-card {{ $size === 'lg' ? 'modal-lg' : '' }} {{ $variant ? 'modal-'.$variant : '' }}">
        <button class="modal-close-btn" data-close>
            <ion-icon name="close-outline"></ion-icon>
        </button>

        @if($title)
            <h3 class="warning-title">{{ $title }}</h3>
        @endif

        @if($message)
            <p class="warning-message">{!! nl2br(e($message)) !!}</p>
        @endif

        {{-- Main content from the caller (optional) --}}
        <div class="modal-slot">
            {{ $slot }}
        </div>

        {{-- Optional actions area --}}
        @isset($actions)
            <div class="modal-actions">
                {{ $actions }}
            </div>
        @endisset
    </section>
</div>
