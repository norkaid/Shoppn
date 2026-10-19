<?php
require_once __DIR__ . '/../../Controllers/ProductController.php';

$controller = new ProductController();

$categories = $controller->getAllCategories();
$brands = $controller->getAllBrands();
?>

<aside>
    <h3>Shoppn</h3>
    <p>Welcome to our online shopping website.</p>

    <h3>Categories</h3>
    <ul>
        <?php foreach ($categories as $category): ?>
            <li>
                <a href="http://169.239.251.102:442/~donna.omaboe/e-commerce/shoppn/index.php?cat=<?php echo (int) $category['cat_id']; ?>">
                <?php echo htmlspecialchars($category['cat_name']); ?>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>

    <h3>Brands</h3>
    <ul>
        <?php foreach ($brands as $brand): ?>
            <li>
                <a href="http://169.239.251.102:442/~donna.omaboe/e-commerce/shoppn/index.php?brand=<?php echo (int) $brand['brand_id']; ?>">
                    <?php echo htmlspecialchars($brand['brand_name']); ?>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
</aside>
