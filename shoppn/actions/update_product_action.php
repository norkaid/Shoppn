<?php

require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../Controllers/ProductController.php';

require_admin();

function updateProductError($message)
{
    $_SESSION['error'] = $message;
    header('Location: ../views/admin/product.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../views/admin/product.php');
    exit;
}

$productId = filter_var(
    $_POST['product_id'] ?? null,
    FILTER_VALIDATE_INT
);

$title = trim($_POST['product_title'] ?? '');
$price = $_POST['product_price'] ?? '';
$categoryId = filter_var(
    $_POST['product_cat'] ?? null,
    FILTER_VALIDATE_INT
);
$brandId = filter_var(
    $_POST['product_brand'] ?? null,
    FILTER_VALIDATE_INT
);
$description = trim($_POST['product_desc'] ?? '');
$keywords = trim($_POST['product_keywords'] ?? '');

if (
    !$productId || $productId < 1 ||
    $title === '' ||
    strlen($title) > 200 ||
    !is_numeric($price) ||
    !is_finite((float) $price) ||
    (float) $price < 0 ||
    $categoryId === false || $categoryId === null || $categoryId < 1 ||
    $brandId === false || $brandId === null || $brandId < 1 ||
    strlen($description) > 500 ||
    strlen($keywords) > 100
) {
    updateProductError('Please enter valid product information.');
}

$controller = new ProductController();
$product = $controller->getProductById($productId);

if (!$product) {
    updateProductError('Product not found.');
}

$oldImage = $product['product_image'] ?? '';
$imageName = $oldImage;
$newImagePath = null;

if (
    isset($_FILES['product_image']) &&
    $_FILES['product_image']['error'] !== UPLOAD_ERR_NO_FILE
) {
    $file = $_FILES['product_image'];

    if ($file['error'] !== UPLOAD_ERR_OK) {
        updateProductError('The image upload failed.');
    }

    if ($file['size'] > 5 * 1024 * 1024) {
        updateProductError('The image must be 5 MB or smaller.');
    }

    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = $finfo->file($file['tmp_name']);

    $allowedTypes = [
        'image/jpeg' => 'jpg',
        'image/png'  => 'png',
        'image/webp' => 'webp',
        'image/gif'  => 'gif'
    ];

    if (
        !isset($allowedTypes[$mime]) ||
        getimagesize($file['tmp_name']) === false
    ) {
        updateProductError('Upload a valid JPG, PNG, WEBP or GIF image.');
    }

    $uploadDir = __DIR__ . '/../images/products/';

    if (!is_dir($uploadDir) &&
        !mkdir($uploadDir, 0755, true) &&
        !is_dir($uploadDir)
    ) {
        updateProductError('Could not create the product image directory.');
    }

    $imageName = bin2hex(random_bytes(16)) . '.' . $allowedTypes[$mime];
    $newImagePath = $uploadDir . $imageName;

    if (!move_uploaded_file($file['tmp_name'], $newImagePath)) {
        updateProductError('Image upload failed. Please try again.');
    }
}

try {
    // Expected order:
    // ID, category, brand, title, price, description, image, keywords
    $result = $controller->updateProduct(
        $productId,
        $categoryId,
        $brandId,
        $title,
        (float) $price,
        $description,
        $imageName,
        $keywords
    );

    if (!$result) {
        if ($newImagePath && is_file($newImagePath)) {
            unlink($newImagePath);
        }

        updateProductError('Could not update the product.');
    }

    $_SESSION['success'] = 'Product updated successfully.';
    header('Location: ../views/admin/product.php');
    exit;

} catch (Throwable $e) {
    if ($newImagePath && is_file($newImagePath)) {
        unlink($newImagePath);
    }

    error_log('Update product error: ' . $e->getMessage());
    updateProductError('An error occurred while updating the product.');
}