<?php
require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../Controllers/ProductController.php';

require_admin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('../views/admin/brand.php');
}

$id = filter_input(INPUT_POST, 'brand_id', FILTER_VALIDATE_INT);
$name = trim($_POST['brand_name'] ?? '');

if (!$id || $id < 1 || strlen($name) < 2) {
    $_SESSION['error'] = 'Please provide a valid brand ID and a brand name of at least 2 characters.';
    redirect('../views/admin/brand.php');
}

$controller = new ProductController();

if ($controller->updateBrand($id, $name)) {
    $_SESSION['success'] = 'Brand updated successfully.';
} else {
    $_SESSION['error'] = 'Could not update brand.';
}

redirect('../views/admin/brand.php');
?>