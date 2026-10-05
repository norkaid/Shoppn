<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Shoppn</title>

    <link rel="stylesheet"
          href="http://169.239.251.102:442/~donna.omaboe/e-commerce/shoppn/css/style.css">

</head>

<body>

<header>

    <nav>

        <a href="http://169.239.251.102:442/~donna.omaboe/e-commerce/shoppn/index.php">
        Home
        </a>

        <?php if (!is_logged_in()): ?>

            <a href="http://169.239.251.102:442/~donna.omaboe/e-commerce/shoppn/views/register.php">
            Register
            </a>

            <a href="http://169.239.251.102:442/~donna.omaboe/e-commerce/shoppn/views/login.php">
            Login
            </a>

        <?php else: ?>

            <span>
                Welcome, <?= htmlspecialchars($_SESSION['customer_name']) ?>
            </span>

            <a href="http://169.239.251.102:442/~donna.omaboe/e-commerce/shoppn/views/account/my_account.php">
                My Account
            </a>

            <a href="http://169.239.251.102:442/~donna.omaboe/e-commerce/shoppn/logout.php">
            Logout
            </a>

        <?php endif; ?>


        <?php if (is_admin()): ?>

            <a href="http://169.239.251.102:442/~donna.omaboe/e-commerce/shoppn/views/admin/brand.php">
            Brand
            </a>

            <a href="http://169.239.251.102:442/~donna.omaboe/e-commerce/shoppn/views/admin/category.php">
            Category
            </a>

        <?php endif; ?>

</nav>

</header>