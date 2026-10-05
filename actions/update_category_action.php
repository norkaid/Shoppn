<?php
require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../Controllers/ProductController.php';

require_admin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('../views/admin/category.php');
}

$id = filter_input(INPUT_POST, 'cat_id', FILTER_VALIDATE_INT);
$name = trim($_POST['cat_name'] ?? '');

if (!$id || $id < 1 || strlen($name) < 2) {
    $_SESSION['error'] = 'Please provide a valid category ID and a category name of at least 2 characters.';
    redirect('../views/admin/category.php');
}

$controller = new ProductController();

if ($controller->updateCategory($id, $name)) {
    $_SESSION['success'] = 'Category updated successfully.';
} else {
    $_SESSION['error'] = 'Could not update category.';
}

redirect('../views/admin/category.php');
?>