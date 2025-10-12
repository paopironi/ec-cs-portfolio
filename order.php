<?php
session_start();
$title = 'Order';
$user = $_SESSION['first_name'] ?? null;
$user_id = $_SESSION['user_id'] ?? null;
if (!$user) {
    header("location: https://" . $_SERVER["HTTP_HOST"] . "/login.php");
    exit;
}
// If the HTTP method is POST, it means that the user has placed an order.
if ($_SERVER['REQUEST_METHOD'] == 'POST' && $user_id) {
    $cart = $_SESSION['cart'];
    // Prepare quantities to be inserted into the database
    $items_total = array_reduce($cart, fn($acc, $ele) => $acc + $ele['quantity'], 0);
    $cart_total = array_reduce($cart, fn($acc, $ele) => $acc + $ele['quantity'] * $ele['price'], 0);
    $now = date('Y-m-d H:i:s');

    require("connect_db.php");
    // Create a new order in the orders table
    $query = mysqli_prepare($link, "insert into orders (user_id, total, order_date) values (?, ?, ?)");
    mysqli_stmt_bind_param($query, "ids", $user_id, $cart_total, $now);
    $created = @mysqli_stmt_execute($query);
    if ($created) {
        // Get the ID of the newly created order
        $created_order_id = mysqli_insert_id($link);
        // Insert the contents of the cart into the order_contents table and remove it from the cart
        foreach ($cart as $id => $item) {
            $query = mysqli_prepare($link, "insert into order_contents (order_id, item_id, quantity, price) values (?, ?, ?, ?)");
            mysqli_stmt_bind_param($query, "iiid", $created_order_id, $id, $item['quantity'], $item['price']);
            $inserted = @mysqli_stmt_execute($query);
            if ($inserted) {
                unset($_SESSION['cart'][$id]);
            } else {
                $item_insertion_error[] = $id;
            }
        }
    } else {
        $ordering_error = true;
    }
    require("includes/nav.php");
    mysqli_close($link);
    // If there are not errors, display a success message and show a link to the orders page.
    if (!isset($ordering_error) && empty($item_insertion_error)) {
?>
        <div class="container py-3">
            <div class="alert alert-success" role="alert">
                <p>Success: Order #<?= $created_order_id ?> placed for <?= $items_total; ?> item(s). </p>
                <p>Go to your <a href="orders.php">orders.</a></p>
            </div>
        </div>
    <?php
    } else if (isset($ordering_error)) {
    ?>
        <div class="container py-3">
            <div class="alert alert-warning" role="alert">
                <p>There was a problem creating an order for your items. Plese try again later.</p>
            </div>
        </div>
    <?php
    } else {
    ?>
        <div class="container py-3">
            <div class="alert alert-warning" role="alert">
                <p>There was a problem creating an order for the following item(s):</p>
                <ul>
                    <?php
                    foreach ($item_insertion_error as $id) {
                    ?>
                        <li>
                            <?= $id; ?>
                        </li>
                    <?php
                    }
                    ?>
                </ul>
                <p>Plese try again later.</p>
            </div>
        </div>
<?php
    }
    require("includes/footer.php");
} else {
    die();
}
