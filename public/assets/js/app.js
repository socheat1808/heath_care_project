/**
 * One-Health — Main JS
 * Handles: navbar toggle, Owl Carousel, WOW.js init
 */
window.addEventListener("scroll", () => {
    const nav = document.getElementById("mainNav");
    nav.style.boxShadow =
        window.scrollY > 10
            ? "0 4px 20px rgba(0,0,0,0.1)"
            : "0 1px 3px rgba(0,0,0,0.06)";
});

document.addEventListener("DOMContentLoaded", function () {
    /* ─── Navbar mobile toggle ─── */
    const toggler = document.getElementById("navToggler");
    const navCollapse = document.getElementById("navbarSupport");

    if (toggler && navCollapse) {
        toggler.addEventListener("click", function () {
            navCollapse.classList.toggle("show");
            const expanded = navCollapse.classList.contains("show");
            toggler.setAttribute("aria-expanded", expanded);
        });

        // Close navbar when clicking outside
        document.addEventListener("click", function (e) {
            if (
                !toggler.contains(e.target) &&
                !navCollapse.contains(e.target)
            ) {
                navCollapse.classList.remove("show");
                toggler.setAttribute("aria-expanded", false);
            }
        });
    }

    /* ─── Navbar scroll shadow ─── */
    const mainNav = document.getElementById("mainNav");
    if (mainNav) {
        window.addEventListener("scroll", function () {
            if (window.scrollY > 10) {
                mainNav.style.boxShadow = "0 4px 20px rgba(0,0,0,0.1)";
            } else {
                mainNav.style.boxShadow = "";
            }
        });
    }

    /* ─── Owl Carousel: Doctor Slideshow ─── */
    if (typeof $ !== "undefined" && $.fn.owlCarousel) {
        $("#doctorSlideshow").owlCarousel({
            loop: true,
            margin: 24,
            nav: true,
            dots: true,
            autoplay: true,
            autoplayTimeout: 4000,
            autoplayHoverPause: true,
            smartSpeed: 600,
            navText: [
                '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>',
                '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>',
            ],
            responsive: {
                0: { items: 1 },
                576: { items: 2 },
                992: { items: 3 },
                1200: { items: 3 },
            },
        });
    }

    /* ─── WOW.js animations ─── */
    if (typeof WOW !== "undefined") {
        new WOW({
            boxClass: "wow",
            animateClass: "animated",
            offset: 80,
            mobile: false,
            live: true,
        }).init();
    }
});
