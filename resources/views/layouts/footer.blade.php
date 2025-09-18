{{-- Ionicons (needed for <ion-icon> tags) --}}
<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>

{{-- Quill JS (load only if used; otherwise push from the page that needs it) --}}
<script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js" defer></script>

{{-- Page-specific scripts --}}
@stack('scripts')
</body>
</html>
