<?php
if (!isset($_SESSION)) {
    session_start();
}
// Use a default title if one is not provided
if (!isset($title)) {
    $title = 'Home';
}
// Count the number of items in the cart, to display on the nav bar if the user is logged in.
if (isset($_SESSION['cart'])) {
    $cart = $_SESSION['cart'];
    $cart_count = array_reduce($cart, fn($acc, $item) => $acc + $item['quantity'], 0);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MK TIME | <?= $title; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../styles.css">
    <link type="image/png" sizes="16x16" rel="icon" href="../assets/icons8-delivery-time-16.png">
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container-fluid text-uppercase" style="padding-left: 1.5rem;">
            <a href="/" class="navbar-brand">MK TIME</a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link <?= $_SERVER['REQUEST_URI'] == '/' ? 'active' : ''; ?>" href="/">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $_SERVER['REQUEST_URI'] == '/products.php' ? 'active' : ''; ?>" href="/products.php">Shop</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $_SERVER['REQUEST_URI'] == '/about.php' ? 'active' : ''; ?>" aria-current="page" href="about.php">Our Brand</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $_SERVER['REQUEST_URI'] == '/contact.php' ? 'active' : ''; ?>" href="contact.php">Contact Us</a>
                    </li>
                </ul>
                <ul class="navbar-nav mb-2 mb-lg-0">
                    <!-- Login and Register buttons -->
                    <?php if (!isset($user)) { ?>
                        <li class="nav-item">
                            <a class="nav-link <?= $_SERVER['REQUEST_URI'] == '/login.php' ? 'active' : ''; ?>" href="login.php">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= $_SERVER['REQUEST_URI'] == '/register.php' ? 'active' : ''; ?>" href="register.php">Register</a>
                        </li>
                    <?php } ?>
                    <!-- Show name of the user, logout button and number of items in cart -->
                    <?php if (isset($user)) { ?>
                        <li class="nav-item">
                            <span class="nav-link"><?= $user; ?></span>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="logout.php">Logout</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="cart.php">
                                <i class="bi bi-cart-fill"></i>
                            </a>
                        </li>
                        <li class="nav-item">
                            <span class="nav-link"><?= $cart_count ?? '0'; ?></span>
                        </li>
                    <?php } ?>
                </ul>
            </div>
        </div>
    </nav>