<?php
session_start();

require_once __DIR__ . '/core/core.php';
require_once __DIR__ . '/classes/ProductClass.php';

// Handle Add to Cart
if (isset($_GET['add_cart']) && ctype_digit($_GET['add_cart'])) {

    $product_id = (int) $_GET['add_cart'];

    // Create the product class
    $productClass = new ProductClass();

    // Check whether the product exists
    $product = $productClass->getProductById($product_id);

    if (!$product) {

        $_SESSION['cart_message'] = 'Product not found.';

    } else {

        // Get the visitor's IP address
        $ip_address = get_ip();

        // Get the logged-in customer's ID, or 0 for a guest
        $customer_id = is_logged_in()
            ? (int) $_SESSION['customer_id']
            : 0;

        // Add the product to the cart ONCE
        $success = $productClass->addToCart(
            $product_id,
            $ip_address,
            $customer_id
        );

        if ($success) {
            $_SESSION['cart_message'] = 'Product added to your cart.';
        } else {
            $_SESSION['cart_message'] = 'Could not add product to your cart.';
        }
    }

    // Return to the home page
    redirect(
        'http://169.239.251.102:442/~donna.omaboe/e-commerce/shoppn/index.php'
    );

    exit;
}

// Display the home page
include __DIR__ . '/views/home.php';

?>
