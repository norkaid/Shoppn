document.addEventListener("DOMContentLoaded", function () {

    const form = document.querySelector("form");

    if (!form) {
        return;
    }


    form.addEventListener("submit", function (event) {

        const name =
            document.querySelector("#name");

        const email =
            document.querySelector("#email");

        const password =
            document.querySelector("#password");

        const contact =
            document.querySelector("#contact");


        if (name) {

            if (name.value.trim().length < 2) {

                alert("Name must be at least 2 characters.");

                event.preventDefault();

                return;
            }
        }


        if (email) {

            const emailRegex =
                /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

            if (!emailRegex.test(email.value)) {

                alert("Please enter a valid email address.");

                event.preventDefault();

                return;
            }
        }


        if (password) {

            const passOK =
                /^(?=.*\d).{8,}$/;

            if (!passOK.test(password.value)) {

                alert(
                    "Password must be at least 8 characters and contain a number."
                );

                event.preventDefault();

                return;
            }
        }


        if (contact) {

            const phoneRegex =
                /^[0-9+\-\s]{7,15}$/;

            if (!phoneRegex.test(contact.value)) {

                alert("Please enter a valid phone number.");

                event.preventDefault();

                return;
            }
        }

    });

});