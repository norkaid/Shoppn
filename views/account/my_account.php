<?php

require_once __DIR__ . '/../../core/core.php';

require_login();

include __DIR__ . '/../layout/header.php';

?>

<div class="container">

    <h2>My Account</h2>

    <p>
        Welcome,
        <?= htmlspecialchars($_SESSION['customer_name']) ?>.
    </p>

    <p>
        Email:
        <?= htmlspecialchars($_SESSION['customer_email']) ?>
    </p>

</div>

<?php

include __DIR__ . '/../layout/footer.php';

?>