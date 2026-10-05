<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../Controllers/CustomerController.php';


if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

    redirect(
        'http://169.239.251.102:442/~donna.omaboe/e-commerce/shoppn/views/login.php'
    );
}


$email =
    filter_var(
        trim($_POST['email'] ?? ''),
        FILTER_SANITIZE_EMAIL
    );

$password =
    $_POST['password'] ?? '';


$controller =
    new CustomerController();


$result =
    $controller->login(
        $email,
        $password
    );


if (!$result['success']) {

    $_SESSION['error'] =
        $result['error'];

    redirect(
        'http://169.239.251.102:442/~donna.omaboe/e-commerce/shoppn/views/login.php'
    );
}


$customer =
    $result['customer'];


$_SESSION['customer_id'] =
    $customer['customer_id'];

$_SESSION['customer_name'] =
    $customer['customer_name'];

$_SESSION['customer_email'] =
    $customer['customer_email'];

$_SESSION['user_role'] =
    $customer['user_role'];


redirect(
    'http://169.239.251.102:442/~donna.omaboe/e-commerce/shoppn/index.php'
);

?>