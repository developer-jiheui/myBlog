import './bootstrap';

// Helper
function elementToggleFunc(el) {
    el.classList.toggle("active");
}

// Sidebar
document.addEventListener("DOMContentLoaded", () => {
    const sidebar = document.querySelector("[data-sidebar]");
    const sidebarBtn = document.querySelector("[data-sidebar-btn]");
    if (sidebar && sidebarBtn) sidebarBtn.addEventListener("click", () => elementToggleFunc(sidebar));
});

// File preview
document.addEventListener("DOMContentLoaded", () => {
    const fileInput = document.getElementById("profile_photo");
    if (!fileInput) return;
    const fileNameDisplay = document.getElementById("image-name");
    const imagePreviewWrapper = document.getElementById("image-preview");
    const previewImg = document.getElementById("preview-img");
    fileInput.addEventListener("change", function () {
        const file = this.files?.[0];
        if (!file) {
            fileNameDisplay.textContent = "No image selected";
            imagePreviewWrapper.style.display = "none";
            return;
        }
        fileNameDisplay.textContent = file.name;
        const reader = new FileReader();
        reader.onload = (e) => {
            previewImg.src = e.target.result;
            imagePreviewWrapper.style.display = "block";
        };
        reader.readAsDataURL(file);
    });
});

// Google Places (guarded)
document.addEventListener("DOMContentLoaded", () => {
    const input = document.getElementById('autocomplete');
    if (input && window.google?.maps?.places) {
        new google.maps.places.Autocomplete(input, {types: ['geocode']});
    }
});

// Testimonials modal (guarded)
document.addEventListener("DOMContentLoaded", () => {
    const items = document.querySelectorAll("[data-testimonials-item]");
    const container = document.querySelector("[data-modal-container]");
    const closeBtn = document.querySelector("[data-modal-close-btn]");
    const overlay = document.querySelector("[data-overlay]");
    if (!items.length || !container || !closeBtn || !overlay) return;

    const modalImg = document.querySelector("[data-modal-img]");
    const modalTitle = document.querySelector("[data-modal-title]");
    const modalText = document.querySelector("[data-modal-text]");
    const modalAuthorTitle = document.querySelector("[data-modal-author-title]");

    const toggle = () => {
        container.classList.toggle("active");
        overlay.classList.toggle("active");
    };

    items.forEach(card => {
        card.addEventListener("click", () => {
            modalImg.src = card.querySelector("[data-testimonials-avatar]").src;
            modalImg.alt = card.querySelector("[data-testimonials-avatar]").alt;
            modalTitle.innerHTML = card.querySelector("[data-testimonials-title]").innerHTML;
            modalText.innerHTML = card.querySelector("[data-testimonials-text]").innerHTML;
            if (modalAuthorTitle) modalAuthorTitle.textContent = card.dataset.authorTitle || "";
            toggle();
        });
    });
    closeBtn.addEventListener("click", toggle);
    overlay.addEventListener("click", toggle);
});

//--------------------------//
//warning modal
//--------------------------//

document.addEventListener('DOMContentLoaded', () => {
    const csrf = document.querySelector('meta[name="csrf-token"]')?.content || '';
    const dismissUrl = document.querySelector('meta[name="modal-dismiss-url"]')?.content || '';

    // One Escape handler (global)
    document.addEventListener('keydown', (e) => {
        if (e.key !== 'Escape') return;
        const active = document.querySelector('[data-modal].active');
        if (!active) return;
        active.classList.remove('active');
        active.querySelector('.warning-overlay')?.classList.remove('active');
        document.body.classList.remove('modal-open');
    });

    document.querySelectorAll('[data-modal]').forEach((modal) => {
        const overlay = modal.querySelector('.warning-overlay');
        const key = modal.getAttribute('data-modal-key'); // e.g. "home"

        const open = () => {
            modal.classList.add('active');
            overlay?.classList.add('active');
            document.body.classList.add('modal-open');
        };

        const close = () => {
            modal.classList.remove('active');
            overlay?.classList.remove('active');
            document.body.classList.remove('modal-open');
        };

        modal.querySelectorAll('[data-close]').forEach(el => el.addEventListener('click', close));

        //  Only open if Blade rendered data-open-on-load
        if (modal.hasAttribute('data-open-on-load')) {
            open();
        }

        // “Don’t show again” → save to Laravel session (no localStorage)
        document.querySelectorAll('[data-dismiss-key]').forEach(input => {
            input.addEventListener('change', async (e) => {
                const key = input.getAttribute('data-dismiss-key');
                if (!key) return;
                if (!input.checked) return; // only save when checked

                try {
                    await fetch(dismissUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrf,
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        credentials: 'same-origin',
                        body: JSON.stringify({key})
                    });
                } catch (err) {
                    console.warn('Dismiss save failed:', err);
                }
            });
        });
        // Optional helpers
        window[`open_${modal.id}`] = open;
        window[`close_${modal.id}`] = close;
    });
});
