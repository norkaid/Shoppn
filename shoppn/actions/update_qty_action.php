
<?php

ini_set('display_errors', '1');
error_reporting(E_ALL);

require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../Controllers/CartController.php';

$cartUrl = 'http://169.239.251.102:442/~donna.omaboe/e-commerce/shoppn/views/cart.php';

// Only allow POST requests.
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect($cartUrl);
    exit;
}

// Make sure the customer is logged in.
$customer_id = isset($_SESSION['customer_id'])
    ? (int) $_SESSION['customer_id']
    : 0;

if ($customer_id <= 0) {
    $_SESSION['error'] = 'Please log in to update your cart.';
    redirect($cartUrl);
    exit;
}

// Get the submitted product IDs and quantities.
$quantities = $_POST['qty'] ?? [];
$productIds = $_POST['update'] ?? [];

if (
    !is_array($quantities) ||
    !is_array($productIds) ||
    empty($productIds) ||
    count($productIds) !== count($quantities)
) {
    $_SESSION['error'] = 'Invalid quantity update request.';
    redirect($cartUrl);
    exit;
}

$controller = new CartController();

$ip = get_ip();

$updated = 0;
$invalid = false;

foreach ($productIds as $index => $product_id) {

    if (!isset($quantities[$index])) {
        $invalid = true;
        continue;
    }

    $id = filter_var(
        $product_id,
        FILTER_VALIDATE_INT
    );

    $qty = filter_var(
        $quantities[$index],
        FILTER_VALIDATE_INT
    );

    // Product ID and quantity must be positive integers.
    if (
        $id === false ||
        $id === null ||
        $id < 1 ||
        $qty === false ||
        $qty === null ||
        $qty < 1
    ) {
        $invalid = true;
        continue;
    }

    // Pass all four arguments expected by CartController.
    $result = $controller->updateQty(
        $id,
        $qty,
        $ip,
        $customer_id
    );

    if ($result) {
        $updated++;
    }
}

// Display a message after the update.
if ($updated > 0 && !$invalid) {
    $_SESSION['success'] = 'Cart quantities updated successfully.';
} elseif ($updated > 0 && $invalid) {
    $_SESSION['success'] = 'Some quantities were updated, but others were invalid.';
} elseif ($invalid) {
    $_SESSION['error'] = 'Enter a valid quantity of at least 1.';
} else {
    $_SESSION['error'] = 'No quantities were updated. Please try again.';
}

// Return to the cart page.
redirect($cartUrl);
exit;

?>
