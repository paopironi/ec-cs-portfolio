<?php
session_start();
$title = 'Orders';
$user = $_SESSION['first_name'] ?? null;
$user_id = $_SESSION['user_id'] ?? null;

function group_by_order($orders)
{
    $grouped_orders = array();
    foreach ($orders as $order) {
        if (isset($grouped_orders[$order['order_id']])) {
            array_push($grouped_orders[$order['order_id']]['items'], $order['items']);
        } else {
            $grouped_orders[$order['order_id']]['order_date'] = $order['order_date'];
            $grouped_orders[$order['order_id']]['total'] = $order['total'];
            $grouped_orders[$order['order_id']]['items'][0] = $order['items'];
        }
    }
    return $grouped_orders;
}

if (!$user) {
    header("location: https://" . $_SERVER["HTTP_HOST"] . "/login.php");
    exit;
}
require("connect_db.php");
require('includes/nav.php');
?>
<section class="container my-5">
    <?php
    $sql = "select o.order_id, o.order_date, o.total, json_array(p.item_name, oc.quantity, p.item_price) as items
    from
    users u join orders o on u.user_id=o.user_id
    join order_contents oc on o.order_id=oc.order_id
    join products p on oc.item_id=p.item_id
    where u.user_id=?;";
    // $sql = "select * from orders where user_id=?";
    $r = mysqli_execute_query($link, $sql, [$user_id]);
    if ($r) {
        $rows = mysqli_fetch_all($r, MYSQLI_ASSOC);
        if (empty($rows)) {
            echo "<p>You have no active orders. Continue <a href='products.php' class='link-secondary'>shopping</a>.";
    ?>
</section>
<?php
        } else {
            $grouped_orders = group_by_order($rows);
?>
    <h3 class="text-uppercase">Your Orders</h3>
    <div style="max-width: 768px;">
        <?php
            foreach ($grouped_orders as $key => $order) {
        ?>
            <div class="pb-3">
                <table class="table table-borderless mt-5">
                    <thead>
                        <tr>
                            <th scope="col" style="text-align: left;">Order No.</th>
                            <th scope="col" style="text-align: center;">Date</th>
                            <th scope="col" colspan="2" style="text-align: right;">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td style="text-align: left;"><?= $key; ?></td>
                            <td style="text-align: center;"><?= date_format(date_create($order['order_date']), 'D, jS F o') ?></td>
                            <td style="text-align: right;" colspan="2">&pound;<?= number_format($order['total'], 2) ?></td>
                        </tr>
                        <tr class="small">
                            <td class="font-weight-bold" style="width: 30%; text-align: left; text-decoration: underline;">Product</td>
                            <td class="font-weight-bold" style="width: 30%; text-align: center; text-decoration: underline;">Quantity</td>
                            <td class="font-weight-bold" style="width: 20%; text-align: right; text-decoration: underline;">Price</td>
                            <td class="font-weight-bold" style="width: 20%; text-align: right; text-decoration: underline;">Subtotal</td>
                        </tr>
                        <?php
                        foreach ($order['items'] as $item) {
                            $details = json_decode($item);
                        ?>
                            <tr class="small">
                                <td class="text-muted" style="width: 30%; text-align: left;"><?= $details[0]; ?></td>
                                <td class="text-muted" style="width: 30%; text-align: center;"><?= $details[1]; ?></td>
                                <td class="text-muted" style="width: 20%; text-align: right;">&pound;<?= number_format($details[2], 2); ?></td>
                                <td class="text-muted" style="width: 20%; text-align: right;">&pound;<?= number_format($details[2] * $details[1], 2); ?></td>
                            </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        <?php
            }
        ?>
    </div>
    </section>
<?php
        }
        require("includes/footer.php");
    }
?>
