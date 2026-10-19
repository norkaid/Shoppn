
<?php

session_start();

require_once __DIR__ . '/../Controllers/CartController.php';
require_once __DIR__ . '/../core/core.php';

// Get the product ID.
$product_id = filter_input(
    INPUT_GET,
    'add_cart',
    FILTER_VALIDATE_INT
);

// Check whether the product ID is valid.
if (!$product_id || $product_id < 1) {
    $_SESSION['cart_message'] = [
        'type' => 'error',
        'text' => 'Invalid product. Please try again.'
    ];

    header('Location: ../index.php');
    exit;
}

// Get the visitor's IP address.
$ip = get_ip();

if (!$ip) {
    $_SESSION['cart_message'] = [
        'type' => 'error',
        'text' => 'Unable to identify your session.'
    ];

    header('Location: ../index.php');
    exit;
}

try {
    $cart = new CartController();

    // Check whether the product is already in the cart.
    if ($cart->isInCart($product_id, $ip)) {
        $_SESSION['cart_message'] = [
            'type' => 'warning',
            'text' => 'This product is already in your cart.'
        ];
    } else {
        // Add one item to the cart.
        $success = $cart->addToCart($product_id, 1, $ip);

        if ($success) {
            $_SESSION['cart_message'] = [
                'type' => 'success',
                'text' => 'Product added to cart successfully!'
            ];
        } else {
            $_SESSION['cart_message'] = [
                'type' => 'error',
                'text' => 'Product could not be added to cart.'
            ];
        }
    }
} catch (Throwable $e) {
    error_log('Add to Cart error: ' . $e->getMessage());

    $_SESSION['cart_message'] = [
        'type' => 'error',
        'text' => 'Something went wrong. Please try again.'
    ];
}

// Return to the page the user came from.
$referer = $_SERVER['HTTP_REFERER'] ?? '';
$referer_host = parse_url($referer, PHP_URL_HOST);
$current_host = $_SERVER['HTTP_HOST'] ?? '';

if (
    $referer_host &&
    strcasecmp($referer_host, $current_host) === 0
) {
    header('Location: ' . $referer);
} else {
    header('Location: ../index.php');
}

exit;
