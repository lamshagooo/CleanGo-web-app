
const cleaners = {
    amila: {
        name: "Amila H.",
        initials: "AH",
        service: "Home Cleaning",
        rating: "4.9",
        price: 25,
        about: "Hi! I'm Amila, a reliable and friendly cleaning professional.",
        services: "Home Cleaning, Deep Cleaning"
    },

    sara: {
        name: "Sara M.",
        initials: "SM",
        service: "Deep Cleaning",
        rating: "4.8",
        price: 30,
        about: "Hi! I'm Sara, a cleaning professional specializing in deep cleaning.",
        services: "Deep Cleaning, Home Cleaning"
    }
};


// Load HTML pages without reloading the whole website
async function loadView() {
    const app = document.getElementById("app");

    const hash = window.location.hash.substring(1) || "home";
    const parts = hash.split("?");

    const viewName = parts[0];
    const params = new URLSearchParams(parts[1] || "");
    const cleanerId = params.get("cleaner") || "amila";
    const cleaner = cleaners[cleanerId] || cleaners.amila;

    try {
        const response = await fetch(`views/${viewName}.html`);

        if (!response.ok) {
            app.innerHTML = "<p>Page could not be loaded.</p>";
            return;
        }

        app.innerHTML = await response.text();

        // Prevent demo forms from submitting
        const form = app.querySelector("form");

        if (form) {
            form.addEventListener("submit", function(event) {
                event.preventDefault();

                if (viewName === "booking") {
                    const date = document.getElementById("booking-date");
                    const time = document.getElementById("booking-time");

                    const selectedDateTime = new Date(
                        `${date.value}T${time.value}`
                    );

                    if (selectedDateTime <= new Date()) {
                        time.setCustomValidity(
                            "Please choose a future date and time."
                        );
                        time.reportValidity();
                        return;
                    }

                    alert(
                        "Booking details are valid! " +
                        "This is a demo; no booking was submitted."
                    );
                }
            });
        }

        if (viewName === "cleaner-profile") {
            initializeCleanerProfile(cleaner, cleanerId);
        }

        if (viewName === "booking") {
            initializeBooking(cleaner);
        }

    } catch (error) {
        app.innerHTML = "<p>Something went wrong.</p>";
        console.error(error);
    }
}


// Show the selected cleaner's profile
function initializeCleanerProfile(cleaner, cleanerId) {
    document.getElementById("profile-avatar").textContent = cleaner.initials;
    document.getElementById("profile-name").textContent = cleaner.name;
    document.getElementById("profile-service").textContent =
        `${cleaner.service} · Sarajevo`;

    document.getElementById("profile-rating").textContent =
        `⭐ ${cleaner.rating} | ${cleaner.price} KM/hour`;

    document.getElementById("profile-about").textContent = cleaner.about;
    document.getElementById("profile-services").textContent = cleaner.services;

    document.getElementById("profile-price").textContent =
        `${cleaner.price} KM/hour`;

    document.getElementById("profile-book-button").href =
        `#booking?cleaner=${cleanerId}`;
}


// Initialize booking page
function initializeBooking(cleaner) {
    const cleanerName = document.getElementById("cleaner-name");
    const duration = document.getElementById("booking-duration");
    const estimate = document.getElementById("booking-estimate");
    const dateInput = document.getElementById("booking-date");
    const timeInput = document.getElementById("booking-time");

    cleanerName.value = cleaner.name;
    document.getElementById("booking-rate").textContent =
    `${cleaner.name}'s rate is ${cleaner.price} KM per hour.`;

    // Update price based on selected cleaner and duration
    function updateEstimate() {
        const total = Number(duration.value) * cleaner.price;
        estimate.textContent = `${total} KM`;
    }

    duration.addEventListener("change", updateEstimate);
    updateEstimate();

    // Prevent booking in the past
    function getToday() {
        const today = new Date();
        const year = today.getFullYear();
        const month = String(today.getMonth() + 1).padStart(2, "0");
        const day = String(today.getDate()).padStart(2, "0");

        return `${year}-${month}-${day}`;
    }

    dateInput.min = getToday();

    function validateDateTime() {
        dateInput.min = getToday();

        if (!dateInput.value || !timeInput.value) {
            timeInput.setCustomValidity("");
            return;
        }

        const selected = new Date(
            `${dateInput.value}T${timeInput.value}`
        );

        if (selected <= new Date()) {
            timeInput.setCustomValidity(
                "Please choose a future date and time."
            );
        } else {
            timeInput.setCustomValidity("");
        }
    }

    dateInput.addEventListener("change", validateDateTime);
    timeInput.addEventListener("change", validateDateTime);
}


// SPA navigation
window.addEventListener("hashchange", loadView);
loadView();
