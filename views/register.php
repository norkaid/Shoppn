<?php
require_once __DIR__ . '/../core/core.php';

include __DIR__ . '/layout/header.php';

?>

<div class="container">

    <h2>Create an Account</h2>

    <?php if (isset($_SESSION['error'])): ?>

        <p class="error">
            <?= htmlspecialchars($_SESSION['error']) ?>
        </p>

        <?php unset($_SESSION['error']); ?>

    <?php endif; ?>


    <form
        action="../actions/register_action.php"
        method="POST"
    >

        <div>

            <label for="name">
                Full Name
            </label>

            <input
                type="text"
                id="name"
                name="name"
                required
            >

        </div>


        <div>

            <label for="email">
                Email
            </label>

            <input
                type="email"
                id="email"
                name="email"
                required
            >

        </div>


        <div>

            <label for="password">
                Password
            </label>

            <input
                type="password"
                id="password"
                name="password"
                required
            >

        </div>


        <div>

            <label for="country">
                Country
            </label>

            <select
                id="country"
                name="country"
                required
            >

                <option value="">
                    Select Country
                </option>

                <option value="Ghana">
                    Ghana
                </option>

                <option value="Nigeria">
                    Nigeria
                </option>

                <option value="Kenya">
                    Kenya
                </option>

                <option value="South Africa">
                    South Africa
                </option>

            </select>

        </div>


        <div>

            <label for="city">
                City
            </label>

            <input
                type="text"
                id="city"
                name="city"
                required
            >

        </div>


        <div>

            <label for="contact">
                Contact Number
            </label>

            <input
                type="text"
                id="contact"
                name="contact"
                required
            >

        </div>


        <button type="submit">
            Register
        </button>

    </form>

</div>

<script src="../js/validate.js"></script>

<?php

include __DIR__ . '/layout/footer.php';

?>