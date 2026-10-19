<?php
require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../Controllers/ProductController.php';

require_admin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('../views/admin/brand.php');
}

$name = trim($_POST['brand_name'] ?? '');

if (strlen($name) < 2) {
    $_SESSION['error'] = 'Brand name must contain at least 2 characters.';
    redirect('../views/admin/brand.php');
}

$controller = new ProductController();

if ($controller->addBrand($name)) {
    $_SESSION['success'] = 'Brand added successfully.';
} else {
    $_SESSION['error'] = 'Could not add brand.';
}

redirect('../views/admin/brand.php');
?>