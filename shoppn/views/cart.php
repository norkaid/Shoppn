
<?php

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

require_once __DIR__ . '/../core/core.php';

$controllerFile = __DIR__ . '/../Controllers/CartController.php';

if (!file_exists($controllerFile)) {
    die('Error: CartController.php was not found.');
}

require_once $controllerFile;

if (!class_exists('CartController')) {
    die('Error: CartController class could not be loaded.');
}

$controller = new CartController();

$ip = get_ip();

$customer_id = isset($_SESSION['customer_id'])
    ? (int) $_SESSION['customer_id']
    : 0;

$items = $controller->getCartItems($ip, $customer_id);
$total = $controller->getCartTotal($ip, $customer_id);

/**
 * Safely display text in HTML.
 */
function cartEscape($value)
{
    return htmlspecialchars(
        (string) $value,
        ENT_QUOTES,
        'UTF-8'
    );
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>My Shopping Cart | Shoppn</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 30px;
            color: #222;
            background: #f7f9fc;
        }

        .cart-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 25px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        }

        h1 {
            color: #1769aa;
            margin-bottom: 10px;
        }

        h2 {
            margin-top: 30px;
            color: #333;
        }

        .cart-description {
            color: #666;
            margin-bottom: 25px;
        }

        .table-wrapper {
            width: 100%;
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
            background: #fff;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }

        th {
            background: #1769aa;
            color: #fff;
        }

        tbody tr:nth-child(even) {
            background: #f8fbff;
        }

        input[type="number"] {
            width: 85px;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        input[type="checkbox"] {
            width: 17px;
            height: 17px;
            cursor: pointer;
        }

        button,
        .button {
            display: inline-block;
            padding: 11px 16px;
            margin: 8px 8px 8px 0;
            border: none;
            border-radius: 5px;
            background: #1769aa;
            color: #fff;
            text-decoration: none;
            cursor: pointer;
            font-size: 14px;
        }

        button:hover,
        .button:hover {
            background: #0d4f85;
        }

        .remove-button {
            background: #c0392b;
        }

        .remove-button:hover {
            background: #962d22;
        }

        .cart-total {
            margin: 25px 0;
            padding: 18px;
            background: #eef6ff;
            border-radius: 6px;
            font-size: 22px;
            font-weight: bold;
            text-align: right;
            color: #1769aa;
        }

        .empty-cart {
            padding: 25px;
            background: #f2f6fa;
            border-radius: 6px;
            margin: 20px 0;
        }

        .cart-actions {
            margin-top: 20px;
        }

        .muted {
            color: #666;
            font-size: 14px;
        }

        @media (max-width: 600px) {
            body {
                padding: 12px;
            }

            .cart-container {
                padding: 15px;
            }

            th,
            td {
                padding: 8px;
                font-size: 13px;
            }

            .cart-total {
                font-size: 18px;
            }
        }
    </style>
</head>

<body>

<div class="cart-container">

    <h1>My Shopping Cart</h1>

    <p class="cart-description">
        Review your products, update quantities, or remove items
        before proceeding to checkout.
    </p>

    <?php if ($customer_id <= 0): ?>

        <div class="empty-cart">
            <p>Please log in to view your shopping cart.</p>
            <a class="button" href="../index.php">
                Continue Shopping
            </a>
        </div>

    <?php elseif (empty($items)): ?>

        <div class="empty-cart">
            <h2>Your cart is empty</h2>
            <p>You have not added any products to your cart yet.</p>

            <a class="button" href="../index.php">
                Continue Shopping
            </a>
        </div>

    <?php else: ?>

        <p>
            You have
            <strong><?= count($items) ?></strong>
            product item(s) in your cart.
        </p>

        <h2>Your Products</h2>

        <!-- Form for removing selected products -->
        <form action="../actions/remove_from_cart_action.php"
              method="POST">

            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>Select</th>
                            <th>Product</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach ($items as $item): ?>

                            <tr>
                                <td>
                                    <input
                                        type="checkbox"
                                        name="remove[]"
                                        value="<?= (int) $item['p_id'] ?>"
                                        aria-label="Select <?= cartEscape($item['product_title']) ?> for removal"
                                    >
                                </td>

                                <td>
                                    <strong>
                                        <?= cartEscape($item['product_title']) ?>
                                    </strong>
                                </td>

                                <td>
                                    GHS <?= number_format(
                                        (float) $item['product_price'],
                                        2
                                    ) ?>
                                </td>

                                <td>
                                    <?= (int) $item['qty'] ?>
                                </td>

                                <td>
                                    GHS <?= number_format(
                                        (float) $item['product_price']
                                        * (int) $item['qty'],
                                        2
                                    ) ?>
                                </td>
                            </tr>

                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <button type="submit"
                    class="remove-button">
                Remove Selected Items
            </button>

        </form>

        <!-- Separate form for updating quantities -->
        <h2>Update Quantities</h2>

        <p class="muted">
            Enter the new quantity for each product you want to update,
            then select Update Cart.
        </p>

        <form action="../actions/update_qty_action.php"
              method="POST">

            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Current Quantity</th>
                            <th>New Quantity</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach ($items as $item): ?>

                            <tr>
                                <td>
                                    <?= cartEscape($item['product_title']) ?>
                                </td>

                                <td>
                                    <?= (int) $item['qty'] ?>
                                </td>

                                <td>
                                    <input
                                        type="number"
                                        name="qty[]"
                                        min="1"
                                        step="1"
                                        required
                                        value="<?= (int) $item['qty'] ?>"
                                    >

                                    <input
                                        type="hidden"
                                        name="update[]"
                                        value="<?= (int) $item['p_id'] ?>"
                                    >
                                </td>
                            </tr>

                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <button type="submit">
                Update Cart
            </button>

        </form>

        <!-- Cart total -->
        <div class="cart-total">
            Cart Total:
            GHS <?= number_format((float) $total, 2) ?>
        </div>

        <div class="cart-actions">
            <a class="button" href="../index.php">
                Continue Shopping
            </a>

            <a class="button" href="checkout.php">
                Proceed to Checkout
            </a>
        </div>

    <?php endif; ?>

</div>

</body>
</html>
