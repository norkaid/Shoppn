document.addEventListener("DOMContentLoaded", function () {

    const form = document.querySelector("form");

    if (!form) {
        return;
    }

    form.addEventListener("submit", function (event) {

        const name = document.querySelector("#name");
        const email = document.querySelector("#email");
        const password = document.querySelector("#password");
        const contact = document.querySelector("#contact");


        // Validate name
        if (name) {
            if (name.value.trim().length < 2) {
                alert("Name must be at least 2 characters.");
                event.preventDefault();
                return;
            }
        }


        // Validate email
        if (email) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

            if (!emailRegex.test(email.value.trim())) {
                alert("Please enter a valid email address.");
                event.preventDefault();
                return;
            }
        }


        // Validate password
        if (password) {

            const passwordRegex =
                /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^a-zA-Z\d]).{10,}$/;

            if (!passwordRegex.test(password.value)) {
                alert(
                    "Password must be at least 10 characters and contain:\n" +
                    "- At least one uppercase letter\n" +
                    "- At least one lowercase letter\n" +
                    "- At least one number\n" +
                    "- At least one special character"
                );

                event.preventDefault();
                return;
            }
        }


        // Validate contact number
        if (contact) {
            const phoneRegex = /^[0-9+\-\s]{7,15}$/;

            if (!phoneRegex.test(contact.value.trim())) {
                alert("Please enter a valid phone number.");
                event.preventDefault();
                return;
            }
        }

    });

});