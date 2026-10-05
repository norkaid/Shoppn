<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../Controllers/CustomerController.php';

$registerUrl = 'http://169.239.251.102:442/~donna.omaboe/e-commerce/shoppn/views/register.php';
$accountUrl = 'http://169.239.251.102:442/~donna.omaboe/e-commerce/shoppn/views/account/my_account.php';


// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect($registerUrl);
}


// Get and sanitise form data
$name = trim(strip_tags($_POST['name'] ?? ''));

$email = filter_var(
    trim($_POST['email'] ?? ''),
    FILTER_SANITIZE_EMAIL
);

$password = $_POST['password'] ?? '';

$country = trim(strip_tags($_POST['country'] ?? ''));

$city = trim(strip_tags($_POST['city'] ?? ''));

$contact = trim(strip_tags($_POST['contact'] ?? ''));


// Validate name
if (strlen($name) < 2) {
    $_SESSION['error'] = 'Name must be at least 2 characters.';
    redirect($registerUrl);
}


// Validate email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['error'] = 'Please enter a valid email address.';
    redirect($registerUrl);
}


// Validate password requirements
$passwordPattern = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^a-zA-Z\d]).{10,}$/';

if (!preg_match($passwordPattern, $password)) {
    $_SESSION['error'] = 'Password must be at least 10 characters and contain at least one uppercase letter, one lowercase letter, one number, and one special character.';
    redirect($registerUrl);
}


// Validate required fields
if (
    $country === '' ||
    $city === '' ||
    $contact === ''
) {
    $_SESSION['error'] = 'Please fill in all required fields.';
    redirect($registerUrl);
}


// Validate contact number
$phonePattern = '/^[0-9+\-\s]{7,15}$/';

if (!preg_match($phonePattern, $contact)) {
    $_SESSION['error'] = 'Please enter a valid phone number.';
    redirect($registerUrl);
}


// Prepare registration data
$data = [
    'name' => $name,
    'email' => $email,
    'pass' => $password,
    'country' => $country,
    'city' => $city,
    'contact' => $contact
];


// Register customer
$controller = new CustomerController();

$result = $controller->register($data);


if (!$result['success']) {
    $_SESSION['error'] = $result['error'];
    redirect($registerUrl);
}


// Automatically log in the newly registered customer
$loginResult = $controller->login($email, $password);


if ($loginResult['success']) {

    $customer = $loginResult['customer'];

    $_SESSION['customer_id'] = $customer['customer_id'];

    $_SESSION['customer_name'] = $customer['customer_name'];

    $_SESSION['customer_email'] = $customer['customer_email'];

    $_SESSION['user_role'] = $customer['user_role'];

    redirect($accountUrl);
}


// Registration succeeded, but automatic login failed
$_SESSION['success'] = 'Registration successful. Please log in.';
redirect($registerUrl);

?>