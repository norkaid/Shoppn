<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Shoppn</title>

    <!-- Main CSS -->
    <link rel="stylesheet"
          href="http://169.239.251.102:442/~donna.omaboe/e-commerce/shoppn/css/style.css">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap"
          rel="stylesheet">

    <!-- Google Material Symbols -->
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined&icon_names=shopping_cart,add_shopping_cart,home,search,login,inventory_2&display=block">
</head>

<body>

<header class="site-header">

    <nav class="navbar">

        <!-- Website Logo -->
        <a class="brand-logo"
           href="http://169.239.251.102:442/~donna.omaboe/e-commerce/shoppn/index.php">
            SHOPPN
        </a>

        <!-- Search Form -->
        <form class="search-form"
              action="http://169.239.251.102:442/~donna.omaboe/e-commerce/shoppn/views/search_results.php"
              method="GET">

            <input
                type="text"
                name="user_query"
                placeholder="Search products..."
                required
            >

            <button
                type="submit"
                class="search-button"
                aria-label="Search products"
            >
                <span class="material-symbols-outlined">search</span>
            </button>

        </form>

        <!-- Navigation Links -->
        <div class="nav-links">

            <!-- Home -->
            <a class="nav-link"
               href="http://169.239.251.102:442/~donna.omaboe/e-commerce/shoppn/index.php">
                <span class="material-symbols-outlined">home</span>
                <span>Home</span>
            </a>

            
        
           
            <!-- Shopping Cart -->
            <a href="views/cart.php"
                class="cart-link"
                aria-label="View shopping cart">

                <span class="material-symbols-outlined">shopping_cart</span>
                <span>Cart</span>

            </a>





            <!-- Login and Registration -->
            <?php if (!is_logged_in()): ?>

                <a class="nav-link"
                   href="http://169.239.251.102:442/~donna.omaboe/e-commerce/shoppn/views/register.php">
                    Register
                </a>

                <a class="nav-link"
                   href="http://169.239.251.102:442/~donna.omaboe/e-commerce/shoppn/views/login.php">

                    <span class="material-symbols-outlined">login</span>
                    <span>Login</span>

                </a>

            <?php else: ?>

                <!-- Logged-in Customer -->
                <span class="welcome-message">
                    Welcome,
                    <?= htmlspecialchars($_SESSION['customer_name'] ?? 'Customer') ?>
                </span>

                <a class="nav-link"
                   href="http://169.239.251.102:442/~donna.omaboe/e-commerce/shoppn/views/account/my_account.php">
                    My Account
                </a>

                <a class="nav-link"
                   href="http://169.239.251.102:442/~donna.omaboe/e-commerce/shoppn/logout.php">
                    Logout
                </a>

            <?php endif; ?>

            <!-- Admin Navigation -->
            <?php if (is_admin()): ?>

                <a class="nav-link"
                   href="http://169.239.251.102:442/~donna.omaboe/e-commerce/shoppn/views/admin/brand.php">
                    Brand
                </a>

                <a class="nav-link"
                   href="http://169.239.251.102:442/~donna.omaboe/e-commerce/shoppn/views/admin/category.php">
                    Category
                </a>

                <a class="nav-link"
                   href="http://169.239.251.102:442/~donna.omaboe/e-commerce/shoppn/views/admin/product.php">

                    <span class="material-symbols-outlined">inventory_2</span>
                    <span>Products</span>

                </a>

            <?php endif; ?>

        </div>

    </nav>

</header>
