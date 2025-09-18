@props([
  'id' => 'appModal',
  'title' =>"🚧 Webpage building in progress 🚧",
  'message' => null,
  'variant'=> 'warning',
  'size' => 'md',
  'openOnLoad' => false,
])
<div id="{{ $id }}"
     data-modal
     {{ $openOnLoad ? 'data-open-on-load' : '' }}
     class="warning-container"
     aria-hidden="true">
    <div class="warning-overlay" data-close></div>


    <section
        class="warning-panel content-card {{ $size === 'lg' ? 'modal-lg' : '' }} {{ $variant ? 'modal-'.$variant : '' }}">
        <button class="modal-close-btn" data-close>
            <ion-icon name="close-outline"></ion-icon>
        </button>

        @if($title)
            <div class="warning-title-container">
                <h3 class="warning-title">{{ $title }}</h3>
            </div>
        @endif

        @if($message)
            <div class="warning-message-container">
                <p class="warning-message">{!! nl2br(e($message)) !!}</p>
            </div>
        @endif

        {{-- Main content from the caller (optional) --}}
        <div class="warning-modal-slot">
            {{ $slot }}
        </div>

        {{-- Optional actions area --}}
        @isset($actions)
            <div class="warning-modal-actions">
                {{ $actions }}
            </div>
        @endisset
    </section>
</div>
