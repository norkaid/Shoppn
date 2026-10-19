<?php
require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../Controllers/ProductController.php';

$controller = new ProductController();

// Get the search term from the URL
$query = trim($_GET['user_query'] ?? '');

// Search for products
$products = [];

if ($query !== '') {
    $products = $controller->searchProducts($query);
}

include __DIR__ . '/layout/header.php';
?>

<div class="container">

    <?php include __DIR__ . '/layout/sidebar.php'; ?>

    <section>
        <h2>
            Search Results for:
            <?php echo htmlspecialchars($query); ?>
        </h2>

        <?php if ($query === ''): ?>

            <p>Please enter a product name or keyword to search.</p>

        <?php elseif (empty($products)): ?>

            <p>No products found for your search. Please try another keyword.</p>

        <?php else: ?>

            <div class="product-grid">

                <?php foreach ($products as $product): ?>

                    <div class="product-card">

                        <img
                            src="../images/products/<?php echo rawurlencode($product['product_image']); ?>"
                            alt="<?php echo htmlspecialchars($product['product_title']); ?>"
                            width="180"
                        >

                        <h3>
                            <?php echo htmlspecialchars($product['product_title']); ?>
                        </h3>

                        <p>
                            Price: GHS
                            <?php echo number_format((float) $product['product_price'], 2); ?>
                        </p>

                        <a href="single_product.php?pro_id=<?php echo (int) $product['product_id']; ?>">
                            Details
                        </a>

                        <a href="../index.php?add_cart=<?php echo (int) $product['product_id']; ?>">
                            Add to Cart
                        </a>

                    </div>

                <?php endforeach; ?>

            </div>

        <?php endif; ?>

    </section>

</div>

<?php include __DIR__ . '/layout/footer.php'; ?>
