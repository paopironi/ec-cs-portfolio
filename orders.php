<?php
session_start();
$title = 'Orders';
$user = $_SESSION['first_name'] ?? null;
$user_id = $_SESSION['user_id'] ?? null;
if (!$user) {
    header("location: https://" . $_SERVER["HTTP_HOST"] . "/login.php");
    exit;
}
require("connect_db.php");
require('includes/nav.php');
?>
<section class="container my-5">
    <?php
    $sql = "select * from orders where user_id=?";
    $r = mysqli_execute_query($link, $sql, [$user_id]);
    if ($r) {
        $rows = mysqli_fetch_all($r, MYSQLI_ASSOC);
        if (empty($rows)) {
            echo "<p>You have no active orders. Continue <a href='products.php' class='link-secondary'>shopping</a>.";
    ?>
</section>
<?php
        } else {
?>
    <h3 class="text-uppercase">Your Orders</h3>
    <div style="width: 768px;">
        <table class="table table-borderless mt-5">
            <thead>
                <tr>
                    <th scope="col" style="width: 20%; text-align: left;">Order No.</th>
                    <th scope="col" style="width: 40%; text-align: center;">Date</th>
                    <th scope="col" style="width: 40%; text-align: right;">Total</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($rows as $order) {
                ?>
                    <tr>
                        <td style="width: 20%; text-align: left;"><?= $order['order_id'] ?></td>
                        <td style="width: 40%; text-align: center;"><?= $order['order_date'] ?></td>
                        <td style="width: 40%; text-align: right;">&pound;<?= number_format($order['total'], 2) ?></td>
                    </tr>
                <?php
                }
                ?>
            </tbody>
        </table>
    </div>
    </section>
<?php
        }
        require("includes/footer.php");
    }
?>