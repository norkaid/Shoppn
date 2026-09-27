<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../Controllers/CustomerController.php';


if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

    redirect(
        'http://169.239.251.102:442/~donna.omaboe/e-commerce/shoppn/views/register.php'
    );
}


$name =
    trim(strip_tags($_POST['name'] ?? ''));

$email =
    filter_var(
        trim($_POST['email'] ?? ''),
        FILTER_SANITIZE_EMAIL
    );

$password =
    $_POST['password'] ?? '';

$country =
    trim(strip_tags($_POST['country'] ?? ''));

$city =
    trim(strip_tags($_POST['city'] ?? ''));

$contact =
    trim(strip_tags($_POST['contact'] ?? ''));


if (strlen($name) < 2) {

    $_SESSION['error'] =
        'Name must be at least 2 characters.';

    redirect(
        'http://169.239.251.102:442/~donna.omaboe/e-commerce/shoppn/views/register.php'
    );
}


if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

    $_SESSION['error'] =
        'Please enter a valid email address.';

    redirect(
        'http://169.239.251.102:442/~donna.omaboe/e-commerce/shoppn/views/register.php'
    );
}


if (strlen($password) < 8) {

    $_SESSION['error'] =
        'Password must be at least 8 characters.';

    redirect(
        'http://169.239.251.102:442/~donna.omaboe/e-commerce/shoppn/views/register.php'
    );
}


$data = [

    'name' => $name,
    'email' => $email,
    'pass' => $password,
    'country' => $country,
    'city' => $city,
    'contact' => $contact

];


$controller =
    new CustomerController();

$result =
    $controller->register($data);


if (!$result['success']) {

    $_SESSION['error'] =
        $result['error'];

    redirect(
        'http://169.239.251.102:442/~donna.omaboe/e-commerce/shoppn/views/register.php'
    );
}


$loginResult =
    $controller->login(
        $email,
        $password
    );


if ($loginResult['success']) {

    $customer =
        $loginResult['customer'];

    $_SESSION['customer_id'] =
        $customer['customer_id'];

    $_SESSION['customer_name'] =
        $customer['customer_name'];

    $_SESSION['customer_email'] =
        $customer['customer_email'];

    $_SESSION['user_role'] =
        $customer['user_role'];
}


redirect(
    'http://169.239.251.102:442/~donna.omaboe/e-commerce/shoppn/views/account/my_account.php'
);

?>