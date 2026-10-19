
<?php
require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../Controllers/CartController.php';

$cartUrl = 'http://169.239.251.102:442/~donna.omaboe/e-commerce/shoppn/views/cart.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect($cartUrl);
}

$items = $_POST['remove'] ?? [];

if (!is_array($items) || empty($items)) {
    $_SESSION['error'] = 'Please select at least one item to remove.';
    redirect($cartUrl);
}

$controller = new CartController();
$ip = get_ip();

$customer_id = isset($_SESSION['customer_id'])
    ? (int) $_SESSION['customer_id']
    : 0;

$removed = 0;

foreach ($items as $product_id) {
    $id = filter_var($product_id, FILTER_VALIDATE_INT);

    if ($id === false || $id < 1) {
        continue;
    }

    if ($controller->removeFromCart($id, $ip, $customer_id)) {
        $removed++;
    }
}

if ($removed > 0) {
    $_SESSION['success'] = 'Selected item(s) removed from your cart.';
} else {
    $_SESSION['error'] = 'No items were removed.';
}

redirect($cartUrl);
?>
