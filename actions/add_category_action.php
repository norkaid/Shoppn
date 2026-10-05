<?php
require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../Controllers/ProductController.php';

require_admin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('../views/admin/category.php');
}

$name = trim($_POST['cat_name'] ?? '');

if (strlen($name) < 2) {
    $_SESSION['error'] = 'Category name must contain at least 2 characters.';
    redirect('../views/admin/category.php');
}

$controller = new ProductController();

if ($controller->addCategory($name)) {
    $_SESSION['success'] = 'Category added successfully.';
} else {
    $_SESSION['error'] = 'Could not add category.';
}

redirect('../views/admin/category.php');
?>