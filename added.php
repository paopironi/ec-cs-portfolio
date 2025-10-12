<?php
session_start();
$title = 'Added';
$user = $_SESSION['first_name'] ?? null;
if (!$user) {
    header("location: https://" . $_SERVER["HTTP_HOST"] . "/login.php");
    exit;
}
// This is the page shown after an item has been added to the cart.
// The item is retrieved from the database in order to display its name.
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    require("connect_db.php");
    $query = mysqli_prepare($link, "select * from products where item_id=?");
    mysqli_stmt_bind_param($query, "s", $id);
    mysqli_stmt_execute($query);
    $r = mysqli_stmt_get_result($query);
    if ($r->num_rows == 1) {
        $item = mysqli_fetch_assoc($r);
        if (isset($_SESSION['cart'][$id]) && $_SESSION['cart'][$id]['quantity'] > 1) {
            require("includes/nav.php");
?>
            <div class="container py-3">
                <div class="alert alert-secondary" role="alert">
                    <p>Another <?= $item['item_name']; ?> has been added to your cart.</p>
                    <a href="products.php" class="link link-secondary">Continue Shopping</a> | <a href="cart.php" class="link link-secondary">View Your Cart</a>
                </div>
            </div>
        <?php
        } else {
            require("includes/nav.php");
        ?>
            <div class="container py-3">
                <div class="alert alert-secondary" role="alert">
                    <p>Item <?= $item['item_name']; ?> has been added to your cart.</p>
                    <a href="products.php" class="link link-secondary">Continue Shopping</a> | <a href="cart.php" class="link link-secondary">View Your Cart</a>
                </div>
            </div>
<?php
        }
    }
    mysqli_close($link);
    require("includes/footer.php");
} else {
    die();
}
