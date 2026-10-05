<?php

require_once __DIR__ . '/../core/core.php';
include __DIR__ . '/layout/header.php';


?>

<div class="container">

    <h2>Login</h2>


    <?php if (isset($_SESSION['error'])): ?>

        <p class="error">
            <?= htmlspecialchars($_SESSION['error']) ?>
        </p>

        <?php unset($_SESSION['error']); ?>

    <?php endif; ?>


    <form
        action="../actions/login_action.php"
        method="POST"
    >

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


        <button type="submit">
            Login
        </button>

    </form>


    <p>
        Don't have an account?

        <a href="register.php">
            Register
        </a>
    </p>

</div>

<?php

include __DIR__ . '/layout/footer.php';

?>