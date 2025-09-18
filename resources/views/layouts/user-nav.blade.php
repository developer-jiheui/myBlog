<nav id="userPopover"
     class="user-popover"
     role="menu"
     aria-hidden="true"
     aria-labelledby="userPopoverBtn">
    <a href="{{ route('page.show', ['name' => 'profile']) }}" class="up-item" role="menuitem">Profile</a>
    <a href="{{ route('testimonials.dashboard') }}" class="up-item">Testimonial</a>
    <a href="{{ route('page.show', ['name' => 'history']) }}" class="up-item" role="menuitem">History</a>
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button class="up-item danger" type="submit" role="menuitem">Sign out</button>
    </form>
</nav>
<div class="user-popover-backdrop" id="popoverBackdrop"></div>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const trigger = document.querySelector('[data-open-profile]');
        const popover = document.getElementById('userPopover');
        const backdrop = document.getElementById('popoverBackdrop');

        if (!trigger || !popover) return;

        const open  = () => { trigger.setAttribute('aria-expanded','true');  popover.setAttribute('aria-hidden','false'); backdrop.classList.add('active');};
        const close = () => { trigger.setAttribute('aria-expanded','false'); popover.setAttribute('aria-hidden','true');  backdrop.classList.remove('active');};
        const toggle = () => (popover.getAttribute('aria-hidden') === 'false') ? close() : open();

        // Open/close on button
        trigger.addEventListener('click', (e) => { e.preventDefault(); toggle(); });

        // Click outside closes
        document.addEventListener('click', (e) => {
            if (!popover.contains(e.target) && !trigger.contains(e.target)) close();
        });

        // Esc closes
        document.addEventListener('keydown', (e) => { if (e.key === 'Escape') close(); });

        // Optional: keep it fixed above the button on resize/scroll (since we use fixed right/bottom, nothing needed)
    });

</script>
