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
document.addEventListener('DOMContentLoaded', () => {
    const warningModal = document.getElementById('warningModal');
    if (!warningModal) return;

    const overlay = warningModal.querySelector('.warning-overlay');

    const open = () => {
        warningModal.classList.add('active');
        overlay.classList.add('active');      // explicit overlay activation
        document.body.classList.add('modal-open'); // lock scroll & blur content
    };

    const close = () => {
        warningModal.classList.remove('active');
        overlay.classList.remove('active');
        document.body.classList.remove('modal-open');
    };

    // Close on any [data-close] inside this modal
    warningModal.querySelectorAll('[data-close]').forEach(el => {
        el.addEventListener('click', close);
    });

    // Close on Escape
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') close();
    });

    // // Show only if not shown before (until tab/browser is closed)
    if (!sessionStorage.getItem('warningModalShown')) {
        open();
        sessionStorage.setItem('warningModalShown', 'true');
    }

    // open();
    // Or wire to a button elsewhere:
    // document.getElementById('openHomeModal')?.addEventListener('click', open);
});
