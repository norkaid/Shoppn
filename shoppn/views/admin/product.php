<?php

require_once __DIR__ . '/../../core/core.php';
require_once __DIR__ . '/../../Controllers/ProductController.php';

require_admin();

$controller = new ProductController();

$editMode = false;
$product = null;

if (isset($_GET['edit_id'])) {
    $editId = filter_var($_GET['edit_id'], FILTER_VALIDATE_INT);

    if ($editId && $editId > 0) {
        $product = $controller->getProductById($editId);

        if ($product) {
            $editMode = true;
        } else {
            $_SESSION['error'] = 'Product not found.';
        }
    }
}

$products = $controller->getAllProducts();
$brands = $controller->getAllBrands();
$categories = $controller->getAllCategories();

include __DIR__ . '/../layout/header.php';
?>

<div class="admin-container">

    <section class="admin-card">
        <h2><?php echo $editMode ? 'Edit Product' : 'Add Product'; ?></h2>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert success">
                <?php
                echo htmlspecialchars($_SESSION['success']);
                unset($_SESSION['success']);
                ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert error">
                <?php
                echo htmlspecialchars($_SESSION['error']);
                unset($_SESSION['error']);
                ?>
            </div>
        <?php endif; ?>

        <form
            action="<?php echo $editMode
                ? '../../actions/update_product_action.php'
                : '../../actions/add_product_action.php'; ?>"
            method="POST"
            enctype="multipart/form-data"
        >

            <?php if ($editMode): ?>
                <input
                    type="hidden"
                    name="product_id"
                    value="<?php echo (int) $product['product_id']; ?>"
                >
            <?php endif; ?>

            <label for="product_title">Product Name</label>
            <input
                type="text"
                id="product_title"
                name="product_title"
                value="<?php echo $editMode
                    ? htmlspecialchars($product['product_title'])
                    : ''; ?>"
                maxlength="200"
                required
            >

            <label for="product_price">Price</label>
            <input
                type="number"
                id="product_price"
                name="product_price"
                min="0"
                step="0.01"
                value="<?php echo $editMode
                    ? htmlspecialchars((string) $product['product_price'])
                    : ''; ?>"
                required
            >

            <label for="product_cat">Category</label>
            <select id="product_cat" name="product_cat" required>
                <option value="">Select a category</option>

                <?php foreach ($categories as $category): ?>
                    <option
                        value="<?php echo (int) $category['cat_id']; ?>"
                        <?php echo $editMode &&
                            (int) $product['product_cat'] ===
                            (int) $category['cat_id']
                            ? 'selected'
                            : ''; ?>
                    >
                        <?php echo htmlspecialchars($category['cat_name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="product_brand">Brand</label>
            <select id="product_brand" name="product_brand" required>
                <option value="">Select a brand</option>

                <?php foreach ($brands as $brandItem): ?>
                    <option
                        value="<?php echo (int) $brandItem['brand_id']; ?>"
                        <?php echo $editMode &&
                            (int) $product['product_brand'] ===
                            (int) $brandItem['brand_id']
                            ? 'selected'
                            : ''; ?>
                    >
                        <?php echo htmlspecialchars($brandItem['brand_name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="product_desc">Description</label>
            <textarea
                id="product_desc"
                name="product_desc"
                maxlength="500"
            ><?php echo $editMode
                ? htmlspecialchars($product['product_desc'] ?? '')
                : ''; ?></textarea>

            <label for="product_keywords">Keywords</label>
            <input
                type="text"
                id="product_keywords"
                name="product_keywords"
                maxlength="100"
                value="<?php echo $editMode
                    ? htmlspecialchars($product['product_keywords'] ?? '')
                    : ''; ?>"
            >

            <label for="product_image">Product Image</label>
            <input
                type="file"
                id="product_image"
                name="product_image"
                accept="image/jpeg,image/png,image/webp,image/gif"
                <?php echo $editMode ? '' : 'required'; ?>
            >

            <?php if (
                $editMode &&
                !empty($product['product_image'])
            ): ?>
                <p>Current image:</p>
                <img
                    src="../../images/products/<?php echo rawurlencode(basename($product['product_image'])); ?>"
                    alt="Current product image"
                    style="max-width: 150px; height: auto;"
                >
            <?php endif; ?>

            <button type="submit" class="btn-primary">
                <?php echo $editMode ? 'Update Product' : 'Add Product'; ?>
            </button>

            <?php if ($editMode): ?>
                <a href="product.php" class="btn-secondary">Cancel</a>
            <?php endif; ?>

        </form>
    </section>

    <section class="admin-card">
        <h2>All Products</h2>

        <?php if (!empty($products)): ?>
            <div class="table-wrapper">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Image</th>
                            <th>Product</th>
                            <th>Price</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach ($products as $item): ?>
                            <tr>
                                <td>
                                    <?php echo (int) $item['product_id']; ?>
                                </td>

                                <td>
                                    <?php if (!empty($item['product_image'])): ?>
                                        <img
                                            src="../../images/products/<?php echo rawurlencode(basename($item['product_image'])); ?>"
                                            alt="<?php echo htmlspecialchars($item['product_title']); ?>"
                                            style="width: 60px; height: 60px; object-fit: cover;"
                                        >
                                    <?php else: ?>
                                        No image
                                    <?php endif; ?>
                                </td>

                                <td>
                                    <?php echo htmlspecialchars($item['product_title']); ?>
                                </td>

                                <td>
                                    GHS <?php echo number_format((float) $item['product_price'], 2); ?>
                                </td>

                                <td>
                                    <a
                                        class="edit-link"
                                        href="product.php?edit_id=<?php echo (int) $item['product_id']; ?>"
                                    >
                                        Edit
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p>No products have been added yet.</p>
        <?php endif; ?>

    </section>

</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>