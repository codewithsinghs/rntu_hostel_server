// hero section javascript
const swiper = new Swiper(".swiper", {
    loop: true,
    speed: 600,
    autoplay: false, // Optional: Set to true if you want auto sliding
    navigation: {
        nextEl: ".swiper-button-next",
        prevEl: ".swiper-button-prev",
    },
    effect: "slide", // can be "slide", "fade", etc.
});

// faq section javascript

const accheaders = document.querySelectorAll(".accordion-header");

accheaders.forEach((accheader) => {
    accheader.addEventListener("click", () => {
        const item = accheader.parentElement;
        document.querySelectorAll(".accordion-item").forEach((i) => {
            if (i !== item) i.classList.remove("active");
        });
        item.classList.toggle("active");
    });
});

// hostel list section javascript

const filterButtons = document.querySelectorAll(".filter-btn");
const hostelCards = document.querySelectorAll(".hostel-card");

filterButtons.forEach((button) => {
    button.addEventListener("click", () => {
        // Active class toggle
        filterButtons.forEach((btn) => btn.classList.remove("active"));
        button.classList.add("active");

        const type = button.getAttribute("data-type");

        hostelCards.forEach((card) => {
            if (type === "all" || card.getAttribute("data-type") === type) {
                card.style.display = "block";
            } else {
                card.style.display = "none";
            }
        });
    });
});

// testimonial section javascript

const prevBtn = document.querySelector(".slider-btn.prev");
const nextBtn = document.querySelector(".slider-btn.next");
const testimonials = document.querySelectorAll(".testimonial-item");

let current = 0;

function showTestimonial(index) {
    testimonials.forEach((item, i) => {
        item.classList.toggle("active", i === index);
    });
}

prevBtn.addEventListener("click", () => {
    current = (current - 1 + testimonials.length) % testimonials.length;
    showTestimonial(current);
});

nextBtn.addEventListener("click", () => {
    current = (current + 1) % testimonials.length;
    showTestimonial(current);
});

showTestimonial(current);

document.addEventListener("DOMContentLoaded", () => {
    const loginBtn = document.querySelectorAll(".btn-login");
    const popup = document.getElementById("loginPopup");
    const closeBtn = document.getElementById("closePopup");
    const tabBtns = document.querySelectorAll(".tab-btn");
    const otpInputs = document.querySelectorAll(".otp-inputs input");

    // Open popup
    loginBtn.forEach((btn) =>
        btn.addEventListener("click", () => {
            popup.style.display = "flex";
        })
    );

    // Close popup
    closeBtn.addEventListener("click", () => {
        popup.style.display = "none";
    });

    // Tab switch
    tabBtns.forEach((btn) => {
        btn.addEventListener("click", () => {
            tabBtns.forEach((b) => b.classList.remove("active"));
            btn.classList.add("active");
            // Optionally handle logic here if student/staff has separate behavior
        });
    });

    // OTP Input Handling
    otpInputs.forEach((input, idx) => {
        input.addEventListener("input", () => {
            if (input.value && idx < otpInputs.length - 1) {
                otpInputs[idx + 1].focus();
            }
        });

        input.addEventListener("keydown", (e) => {
            if (e.key === "Backspace" && !input.value && idx > 0) {
                otpInputs[idx - 1].focus();
            }
        });
    });
});
