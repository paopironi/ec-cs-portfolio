<?php
session_start();
$user = $_SESSION['first_name'] ?? null;
if (!$user) {
    header("location: https://" . $_SERVER["HTTP_HOST"] . "/login.php");
    exit;
}
$title = 'Cart';
// Update the cart items based on the POST request.
// If the user has pressed the delete button next to an item, remove it from the cart.
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete-button'])) {
    $id = $_POST['item_id'];
    $_SESSION['cart'][$id]['quantity'] = 0;
    unset($_SESSION['cart'][$id]);
// If the user has changed the quantity of one of more items, update them in the cart. 
} else if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    foreach ($_POST['item_id'] as $key => $id) {
        $_SESSION['cart'][$id]['quantity'] = (int) $_POST['quantity'][$key];
        if ($_SESSION['cart'][$id]['quantity'] == 0) {
            unset($_SESSION['cart'][$id]);
        }
    }
}
require('includes/nav.php');
$nprods = count($_SESSION['cart']);
?>
<section class="container my-5">
    <?php
    if ($nprods == 0) {
        echo "<p>Your cart is empty. Continue <a href='products.php' class='link-secondary'>shopping</a>.";
    } else {
        // Retrieve the items in the cart from the database.
        require("connect_db.php");
        $parameters = str_repeat('?, ', $nprods - 1) . '?';
        $ids = array_keys($_SESSION['cart']);
        $sql = "select * from products where item_id in ($parameters)";
        $r = mysqli_execute_query($link, $sql, $ids);
        if ($r) {
            $rows = mysqli_fetch_all($r, MYSQLI_ASSOC);
        }
    ?>
    <!-- This form is used to delete items from the cart -->
        <form action="" method="POST" id="delete-from-cart"></form>
        <h3 class="text-uppercase">Your Cart</h3>
        <div class="">
            <div style="width: 768px;">
                <div class="pb-4 border-bottom">
                    <table class="table table-borderless mt-5">
                        <thead>
                            <tr>
                                <th scope="col" style="width: 40%; text-align: left;">Product</th>
                                <th scope="col" style="width: 10%; text-align: center;">Quantity</th>
                                <th scope="col" style="width: 35%; text-align: right;">Subtotal</th>
                                <th scope="col" style="width: 15%; text-align: right;"></th>
                            </tr>
                        </thead>
                        <!-- This form is used to update the cart -->
                        <form action="" method="POST" id="cart-update">
                            <tbody>
                                <?php
                                foreach ($rows as $row) {
                                    if ($_SESSION['cart'][$row['item_id']]['quantity'] > 0) {
                                ?>

                                        <input type="hidden" name="item_id[]" value="<?= $row['item_id']; ?>" form="cart-update">
                                        <tr class="">
                                            <td style="width: 40%; text-align: left; vertical-align:middle"><?= $row['item_name']; ?></td>

                                            <td style="width: 10%; text-align: center; vertical-align:middle"><input type="number" class="form-control" name="quantity[]" form="cart-update" min="0" value="<?= $_SESSION['cart'][$row['item_id']]['quantity']; ?>" style="text-align: center; width: 100px"></td>

                                            <td style="width: 35%; text-align: right; vertical-align:middle">&pound;<?= number_format($_SESSION['cart'][$row['item_id']]['quantity'] * $row['item_price'], 2); ?></td>

                                            <td style="width: 15%; text-align: right; font-size: small; vertical-align:middle">

                                                <input type="hidden" name="item_id" form="delete-from-cart" value="<?= $row['item_id']; ?>">
                                                <button type="submit" class="btn delete-button" form="delete-from-cart" name="delete-button"><i class="bi bi-trash"></i></button>

                                            </td>

                                        </tr>
                                <?php
                                    }
                                }
                                ?>
                            </tbody>
                        </form>
                    </table>
                    <div class="d-flex justify-content-end mt-5">
                        <button class="btn in-cart-button" type="submit" form="cart-update">Update Cart</button>
                    </div>
                </div>
                <!-- Calculate the cart total and diplay it under the cart content -->
                <?php
                $cart = $_SESSION['cart'];
                $cart_total = array_reduce($cart, fn($acc, $ele) => $acc + $ele['quantity'] * $ele['price'], 0)
                ?>
                <table class="table table-borderless mt-3">
                    <thead>
                        <tr>
                            <th scope="col" style="width: 40%; text-align: left;"></th>
                            <th scope="col" style="width: 10%; text-align: center;"></th>
                            <th scope="col" style="width: 35%; text-align: right;">Cart Total</th>
                            <th scope="col" style="width: 15%; text-align: right;"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td style="width: 40%; text-align: left;"></td>
                            <td style="width: 10%; text-align: center;"></td>
                            <td style="width: 35%; text-align: right;">&pound;<?= number_format($cart_total, 2) ?></td>
                            <td style="width: 15%; text-align: right;"></td>
                        </tr>
                    </tbody>
                </table>
                <!-- This form is used to place the order -->
                <div class="d-flex justify-content-end mt-2">
                    <form action="order.php" method="POST" id="cart-submit">
                        <button class="btn in-cart-button" type="submit" form="cart-submit">Place Order</button>
                    </form>
                </div>
            </div>

        </div>
    <?php
    }
    ?>
</section>
<?php
require("includes/footer.php");
?>