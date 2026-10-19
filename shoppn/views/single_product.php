
<?php
require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../Controllers/ProductController.php';

if (!isset($_GET['pro_id']) || !ctype_digit($_GET['pro_id'])) {
    header("Location: ../index.php");
    exit;
}

$controller = new ProductController();
$product = $controller->getProductById((int) $_GET['pro_id']);

include __DIR__ . '/layout/header.php';
?>

<main class="container">

    <?php include __DIR__ . '/layout/sidebar.php'; ?>

    <section class="single-product-section">

        <?php if (!$product): ?>

            <div class="product-not-found">
                <h2>Product Not Found</h2>
                <p>The product you selected could not be found.</p>
                <a href="../index.php" class="btn-primary">Back to Home</a>
            </div>

        <?php else: ?>

            <h2 class="section-heading">Product Details</h2>

            <div class="product-details">

                <div class="product-details-image">
                    <img
                        src="../images/products/<?php echo rawurlencode($product['product_image']); ?>"
                        alt="<?php echo htmlspecialchars($product['product_title']); ?>"
                    >
                </div>

                <div class="product-details-info">

                    <h3>
                        <?php echo htmlspecialchars($product['product_title']); ?>
                    </h3>

                    <p class="product-details-price">
                        GHS <?php echo number_format((float) $product['product_price'], 2); ?>
                    </p>

                    <p>
                        <strong>Brand:</strong>
                        <?php echo htmlspecialchars($product['brand_name'] ?? 'Not specified'); ?>
                    </p>

                    <p>
                        <strong>Category:</strong>
                        <?php echo htmlspecialchars($product['cat_name'] ?? 'Not specified'); ?>
                    </p>

                    <div class="product-description">
                        <strong>Description</strong>
                        <p>
                            <?php
                            echo nl2br(htmlspecialchars(
                                $product['product_desc'] ?? 'No description available.'
                            ));
                            ?>
                        </p>
                    </div>

                    <p>
                        <strong>Keywords:</strong>
                        <?php echo htmlspecialchars($product['product_keywords'] ?? 'None'); ?>
                    </p>

                    <div class="product-actions">
                        <a
                            href="../index.php?add_cart=<?php echo (int) $product['product_id']; ?>"
                            class="btn-primary"
                        >
                            Add to Cart
                        </a>

                        <a href="../index.php" class="btn-secondary">
                            Back to Home
                        </a>
                    </div>

                </div>

            </div>

        <?php endif; ?>

    </section>

</main>

<?php include __DIR__ . '/layout/footer.php'; ?>
