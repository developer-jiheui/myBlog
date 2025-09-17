// Helper function to toggle classes
function elementToggleFunc(el) {
    el.classList.toggle("active");
}

// Sidebar toggle functionality for mobile
document.addEventListener("DOMContentLoaded", function () {
    const sidebar = document.querySelector("[data-sidebar]");
    const sidebarBtn = document.querySelector("[data-sidebar-btn]");

    if (sidebar && sidebarBtn) {
        sidebarBtn.addEventListener("click", function () {
            elementToggleFunc(sidebar);
        });
    }
});

document.addEventListener("DOMContentLoaded", function () {
    const fileInput = document.getElementById("profile_photo");
    const fileNameDisplay = document.getElementById("image-name");
    const imagePreviewWrapper = document.getElementById("image-preview");
    const previewImg = document.getElementById("preview-img");

    if (fileInput) {
        fileInput.addEventListener("change", function () {
            const file = this.files[0];

            if (file) {
                fileNameDisplay.textContent = file.name;

                const reader = new FileReader();
                reader.onload = function (e) {
                    previewImg.src = e.target.result;
                    imagePreviewWrapper.style.display = "block";
                };
                reader.readAsDataURL(file);
            } else {
                fileNameDisplay.textContent = "No image selected";
                imagePreviewWrapper.style.display = "none";
            }
        });
    }
});

document.addEventListener("DOMContentLoaded", function () {
    const input = document.getElementById('autocomplete');
    const autocomplete = new google.maps.places.Autocomplete(input, {
        types: ['geocode']  // only addresses
    });
});


// testimonials variables
const testimonialsItem = document.querySelectorAll("[data-testimonials-item]");
const modalContainer = document.querySelector("[data-modal-container]");
const modalCloseBtn = document.querySelector("[data-modal-close-btn]");
const overlay = document.querySelector("[data-overlay]");
const modalAuthorTitle = document.querySelector("[data-modal-author-title]"); // <— new

// modal variable
const modalImg = document.querySelector("[data-modal-img]");
const modalTitle = document.querySelector("[data-modal-title]");
const modalText = document.querySelector("[data-modal-text]");

// modal toggle function
const testimonialsModalFunc = function () {
    modalContainer.classList.toggle("active");
    overlay.classList.toggle("active");
}

// add click event to all modal items
for (let i = 0; i < testimonialsItem.length; i++) {

    testimonialsItem[i].addEventListener("click", function () {

        modalImg.src = this.querySelector("[data-testimonials-avatar]").src;
        modalImg.alt = this.querySelector("[data-testimonials-avatar]").alt;
        modalTitle.innerHTML = this.querySelector("[data-testimonials-title]").innerHTML;
        modalText.innerHTML = this.querySelector("[data-testimonials-text]").innerHTML;

        // Set author title from data- attribute (fallback to empty string)
        modalAuthorTitle.textContent = this.dataset.authorTitle || "";

        testimonialsModalFunc();

    });

}

// add click event to modal close button
modalCloseBtn.addEventListener("click", testimonialsModalFunc);
overlay.addEventListener("click", testimonialsModalFunc);


//--------------------------//
//warning modal
// document.addEventListener('DOMContentLoaded', () => {
//     const warningModal = document.getElementById('warningModal');
//     if (!warningModal) return;
//
//     const overlay = warningModal.querySelector('.warning-overlay');
//
//     const open = () => {
//         warningModal.classList.add('active');
//         overlay.classList.add('active');      // explicit overlay activation
//         document.body.classList.add('modal-open'); // lock scroll & blur content
//     };
//
//     const close = () => {
//         warningModal.classList.remove('active');
//         overlay.classList.remove('active');
//         document.body.classList.remove('modal-open');
//     };
//
//     // Close on any [data-close] inside this modal
//     warningModal.querySelectorAll('[data-close]').forEach(el => {
//         el.addEventListener('click', close);
//     });
//
//     // Close on Escape
//     document.addEventListener('keydown', (e) => {
//         if (e.key === 'Escape') close();
//     });
//
//     // // Show only if not shown before (until tab/browser is closed)
//     if (!sessionStorage.getItem('warningModalShown')) {
//         open();
//         sessionStorage.setItem('warningModalShown', 'true');
//     }
//
//     // open();
//     // Or wire to a button elsewhere:
//     // document.getElementById('openHomeModal')?.addEventListener('click', open);
// });

// document.addEventListener('DOMContentLoaded', () => {
//
//     document.querySelectorAll('[data-modal]').forEach((modal) => {
//
//         const overlay = modal.querySelector('.warning-overlay');
//
//         const open = () => {
//             modal.classList.add('active');
//             overlay?.classList.add('active');
//             document.body.classList.add('modal-open');
//         };
//
//         const close = () => {
//             modal.classList.remove('active');
//             overlay?.classList.remove('active');
//             document.body.classList.remove('modal-open');
//         };
//
//         modal.querySelectorAll('[data-close]').forEach(el => {
//             el.addEventListener('click', close);
//         });
//
//         document.addEventListener('keydown', (e) => {
//             if (e.key === 'Escape') close();
//         });
//
//         if (modal.hasAttribute('data-open-on-load')) {
//             open();
//         }
//         // Handle "Don't show again" (session)
//         modal.querySelectorAll('[data-dismiss-key]').forEach(btn => {
//             btn.addEventListener('click', async () => {
//                 const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
//                 const dismissUrl = document.querySelector('meta[name="modal-dismiss-url"]')?.getAttribute('content') || '';
//
//                 const key = btn.getAttribute('data-dismiss-key'); // e.g. "home"
//                 try {
//                     await fetch(dismissUrl, {
//                         method: 'POST',
//                         headers: {
//                             'Content-Type': 'application/json',
//                             'X-CSRF-TOKEN': csrf,
//                             'X-Requested-With': 'XMLHttpRequest'
//                         },
//                         credentials: 'same-origin', //send session cookie
//                         body: JSON.stringify({key})
//                     });
//                 } catch (e) {
//                     // optional: toast/log
//                     console.warn('Dismiss save failed:', e);
//                 } finally {
//                     close();
//                 }
//             });
//         });
//
//         // Optional: expose open/close globally to trigger from buttons
//         modal.dataset.controller = JSON.stringify({id: modal.id});
//         window[`open_${modal.id}`] = open;
//         window[`close_${modal.id}`] = close;
//     });
// });

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

        // ✅ Only open if Blade rendered data-open-on-load
        if (modal.hasAttribute('data-open-on-load')) {
            open();
        }

        // “Don’t show again” → save to Laravel session (no localStorage)
        modal.querySelectorAll('[data-dismiss-key]').forEach(btn => {
            btn.addEventListener('click', async () => {
                const btnKey = btn.getAttribute('data-dismiss-key');
                if (!btnKey) return;

                try {
                    await fetch(dismissUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrf,
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        credentials: 'same-origin', // send session cookie
                        body: JSON.stringify({key: btnKey})
                    });
                } catch (err) {
                    console.warn('Dismiss save failed:', err);
                } finally {
                    // After saving to session, close now; the next page load
                    // Blade will see the session flag and NOT render data-open-on-load.
                    close();
                }
            });
        });

        // Optional helpers
        window[`open_${modal.id}`] = open;
        window[`close_${modal.id}`] = close;
    });
});
