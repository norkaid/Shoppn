
<?php

require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../Controllers/CartController.php';

// Get the current visitor's details
$ip = get_ip();

$customer_id = isset($_SESSION['customer_id'])
    ? (int) $_SESSION['customer_id']
    : 0;

// Create the cart controller
$controller = new CartController();

// Retrieve this customer's cart and calculate the current total
$items = $controller->getCartItems($ip, $customer_id);
$total = $controller->getCartTotal($ip, $customer_id);

// Escape output before displaying it
function checkoutEscape($value)
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Checkout | Shoppn</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 30px;
            color: #222;
            background: #f8f9fa;
        }

        .checkout-container {
            max-width: 900px;
            margin: 0 auto;
            padding: 25px;
            background: white;
            border-radius: 8px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        th,
        td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: left;
        }

        th {
            background: #f0f0f0;
        }

        .total {
            text-align: right;
            font-size: 22px;
            font-weight: bold;
            margin: 25px 0;
        }

        .button {
            display: inline-block;
            padding: 12px 18px;
            margin: 5px 5px 5px 0;
            color: white;
            background: #1769aa;
            text-decoration: none;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .empty-message {
            padding: 15px;
            background: #f0f0f0;
        }
    </style>
</head>

<body>

<div class="checkout-container">

    <h1>Checkout</h1>

    <?php if (empty($items)): ?>

        <p class="empty-message">
            Your cart is empty. Add some products before checking out.
        </p>

        <a class="button" href="../index.php">
            Continue Shopping
        </a>

    <?php else: ?>

        <h2>Order Summary</h2>

        <table>
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Unit Price</th>
                    <th>Quantity</th>
                    <th>Subtotal</th>
                </tr>
            </thead>

            <tbody>

                <?php foreach ($items as $item): ?>

                    <?php
                    $price = (float) $item['product_price'];
                    $quantity = (int) $item['qty'];
                    $subtotal = $price * $quantity;
                    ?>

                    <tr>
                        <td>
                            <?= checkoutEscape($item['product_title']) ?>
                        </td>

                        <td>
                            GHS <?= number_format($price, 2) ?>
                        </td>

                        <td>
                            <?= $quantity ?>
                        </td>

                        <td>
                            GHS <?= number_format($subtotal, 2) ?>
                        </td>
                    </tr>

                <?php endforeach; ?>

            </tbody>
        </table>

        <div class="total">
            Total Amount: GHS <?= number_format($total, 2) ?>
        </div>

        <a class="button" href="cart.php">
            Back to Cart
        </a>

        <a class="button" href="../index.php">
            Continue Shopping
        </a>

        <p>
            Review your products and quantities before proceeding.
        </p>

    <?php endif; ?>

</div>

</body>
</html>
