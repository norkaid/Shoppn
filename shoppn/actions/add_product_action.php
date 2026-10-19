<?php

require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../Controllers/ProductController.php';

require_admin();

function productError($message)
{
    $_SESSION['error'] = $message;
    header('Location: ../views/admin/product.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../views/admin/product.php');
    exit;
}

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
    productError('Please enter valid product information.');
}

if (!isset($_FILES['product_image'])) {
    productError('No image was received. Check the form and try again.');
}

$uploadError = $_FILES['product_image']['error'];

if ($uploadError !== UPLOAD_ERR_OK) {
    $uploadMessages = [
        UPLOAD_ERR_INI_SIZE   => 'The image exceeds the server upload limit.',
        UPLOAD_ERR_FORM_SIZE  => 'The image exceeds the form upload limit.',
        UPLOAD_ERR_PARTIAL    => 'The image was only partially uploaded. Try again.',
        UPLOAD_ERR_NO_FILE    => 'Please select a product image.',
        UPLOAD_ERR_NO_TMP_DIR => 'The server is missing its temporary upload folder.',
        UPLOAD_ERR_CANT_WRITE => 'The server could not write the uploaded file.',
        UPLOAD_ERR_EXTENSION  => 'A server extension stopped the upload.'
    ];

    productError(
        $uploadMessages[$uploadError]
        ?? 'An unknown upload error occurred. Code: ' . $uploadError
    );
}

$file = $_FILES['product_image'];

if ($file['size'] > 5 * 1024 * 1024) {
    productError('The image must be 5 MB or smaller.');
}

$finfo = new finfo(FILEINFO_MIME_TYPE);
$mime = $finfo->file($file['tmp_name']);

$allowedTypes = [
    'image/jpeg' => 'jpg',
    'image/png'  => 'png',
    'image/webp' => 'webp',
    'image/gif'  => 'gif'
];

if (!isset($allowedTypes[$mime]) || getimagesize($file['tmp_name']) === false) {
    productError('Upload a valid JPG, PNG, WEBP or GIF image.');
}

$uploadDir = __DIR__ . '/../images/products/';

if (!is_dir($uploadDir) &&
    !mkdir($uploadDir, 0755, true) &&
    !is_dir($uploadDir)
) {
    productError('Could not create the product image directory.');
}

$imageName = bin2hex(random_bytes(16)) . '.' . $allowedTypes[$mime];
$imagePath = $uploadDir . $imageName;

if (!is_dir($uploadDir)) {
    error_log('Upload directory does not exist: ' . $uploadDir);
    productError('The product image directory does not exist.');
}

if (!is_writable($uploadDir)) {
    error_log('Upload directory is not writable: ' . $uploadDir);
    productError('The server cannot write to the product image directory.');
}

if (!is_uploaded_file($file['tmp_name'])) {
    error_log('Temporary file is not a valid uploaded file.');
    productError('The server did not receive a valid uploaded image.');
}

error_log('Upload diagnostic: ' . json_encode([
    'destination' => $imagePath,
    'directory_exists' => is_dir($uploadDir),
    'directory_writable' => is_writable($uploadDir),
    'temporary_file_exists' => file_exists($file['tmp_name']),
    'temporary_file_size' => file_exists($file['tmp_name'])
        ? filesize($file['tmp_name'])
        : null
]));

if (!move_uploaded_file($file['tmp_name'], $imagePath)) {
    error_log('Image move failed. Destination: ' . $imagePath);
    error_log('Temporary file: ' . $file['tmp_name']);
    productError('Image upload failed. Check the server error log.');
}

$controller = new ProductController();

try {
    // Expected order:
    // category, brand, title, price, description, image, keywords
    $result = $controller->addProduct(
        $categoryId,
        $brandId,
        $title,
        (float) $price,
        $description,
        $imageName,
        $keywords
    );

    if (!$result) {
        if (is_file($imagePath)) {
            unlink($imagePath);
        }

        productError('Could not add the product. Check the selected brand and category.');
    }

    $_SESSION['success'] = 'Product added successfully.';
    header('Location: ../views/admin/product.php');
    exit;

} catch (Throwable $e) {
    if (is_file($imagePath)) {
        unlink($imagePath);
    }

    error_log('Add product error: ' . $e->getMessage());
    productError('An error occurred while adding the product.');
}