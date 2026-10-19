
<?php
require_once __DIR__ . '/../Controllers/ProductController.php';

include __DIR__ . '/layout/header.php';

// Display the cart confirmation message
if (isset($_SESSION['cart_message'])) {
    echo '<div class="cart-message">';
    echo htmlspecialchars($_SESSION['cart_message']);
    echo '</div>';

    unset($_SESSION['cart_message']);
}

$controller = new ProductController();

// Choose which products to display
if (isset($_GET['cat']) && ctype_digit($_GET['cat'])) {

    $cat_id = (int) $_GET['cat'];

    $products = $controller->getProductsByCategory($cat_id);
    $category = $controller->getCategoryById($cat_id);

    if ($category) {
        $heading = "Products in " . $category['cat_name'];
    } else {
        $heading = "Category Not Found";
    }

} elseif (isset($_GET['brand']) && ctype_digit($_GET['brand'])) {

    $brand_id = (int) $_GET['brand'];

    $products = $controller->getProductsByBrand($brand_id);
    $brand = $controller->getBrandById($brand_id);

    if ($brand) {
        $heading = "Products by " . $brand['brand_name'];
    } else {
        $heading = "Brand Not Found";
    }

} else {

    $products = $controller->getFeaturedProducts();
    $heading = "Featured Products";
}
?>

<div class="container">

    <?php include __DIR__ . '/layout/sidebar.php'; ?>

    <section class="products-section">

        <div class="welcome-section">
            <h2>Welcome to Shoppn</h2>
            <p>Your online shopping destination.</p>
        </div>

        <h2 class="section-heading">
            <?php echo htmlspecialchars($heading); ?>
        </h2>

        <?php if (empty($products)): ?>

            <p class="empty-message">
                No products found. Please check back later.
            </p>

        <?php else: ?>

            <div class="product-grid">

                <?php foreach ($products as $product): ?>

                    <div class="product-card">

                        <img
                            src="images/products/<?php echo rawurlencode($product['product_image']); ?>"
                            alt="<?php echo htmlspecialchars($product['product_title']); ?>"
                            class="product-image"
                        >

                        <div class="product-info">

                            <h3>
                                <?php echo htmlspecialchars($product['product_title']); ?>
                            </h3>

                            <p class="product-price">
                                GHS
                                <?php echo number_format(
                                    (float) $product['product_price'],
                                    2
                                ); ?>
                            </p>

                            <div class="product-actions">

                                <!-- Product Details Button -->
                                <a
                                    class="btn-secondary"
                                    href="views/single_product.php?pro_id=<?php echo (int) $product['product_id']; ?>"
                                >
                                    Details
                                </a>

                                <!-- Add to Cart Button -->
                                <a
                                    class="btn-primary"
                                    href="index.php?add_cart=<?php echo (int) $product['product_id']; ?>"
                                >
                                    <span class="material-symbols-outlined">
                                        add_shopping_cart
                                    </span>
                                    Add to Cart
                                </a>

                            </div>

                        </div>

                    </div>

                <?php endforeach; ?>

            </div>

        <?php endif; ?>

    </section>

</div>

<?php include __DIR__ . '/layout/footer.php'; ?>
