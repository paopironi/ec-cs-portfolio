<?php
session_start();
$title = 'Shop';
$user = $_SESSION['first_name'] ?? null;
if (!$user) {
    header("location: https://" . $_SERVER["HTTP_HOST"] . "/login.php");
    exit;
}
require('connect_db.php');
if (isset($_POST['item_id'])) {
    $id = $_POST['item_id'];
    $query = mysqli_prepare($link, "select * from products where item_id=?");
    mysqli_stmt_bind_param($query, "s", $id);
    mysqli_stmt_execute($query);
    $r = mysqli_stmt_get_result($query);
    if ($r->num_rows == 1) {
        $item = mysqli_fetch_assoc($r);
        if (isset($_SESSION['cart'][$id])) {
            # Add one more of this product.
            $_SESSION['cart'][$id]['quantity']++;
        } else {
            $_SESSION['cart'][$id] = array('quantity' => 1, 'price' => $item['item_price']);
        }
        header("location: https://" . $_SERVER["HTTP_HOST"] . "/added.php?id=$id");
        exit;
    }
}
$query = "select * from products limit 18";
$r = mysqli_query($link, $query);
$rows = mysqli_fetch_all($r, MYSQLI_ASSOC);
mysqli_close($link);
$nprods = $r->num_rows;
$table_rows = $nprods % 4 == 0 ? $nprods / 4 : floor($nprods / 4) + 1;
require('includes/nav.php');
?>
<!-- Products -->
<section class="container my-5">
    <h3 class="text-uppercase text-center">Our collection</h3>
    <?php
    $n = 1;
    foreach ($rows as $row) {
        $item_in_cart = array_key_exists(
            $row['item_id'],
            $_SESSION['cart']

        );
        $n_stars = random_int(3, 5);
        $n_reviews = random_int(35, 105);
        if ($n % 4 == 1) {
    ?>
            <div class="row mt-3 g-3">
            <?php
        }
            ?>
            <div class="col-lg-3 col-md-6 d-flex flex-column justify-content-between">
                <div class="mt-3 text-center border border-secondary-subtle border-opacity-25 py-4">
                    <img src="assets/<?= $row['item_img']; ?>" alt="<?= $row['item_name']; ?>" class="img-fluid" style="height: 150px;">
                </div>
                <div class="mt-3 px-5 px-md-4">
                    <p class="fst-italic"><?= $row['item_name']; ?></p>
                    <p class="mt-3 fw-bold">£<?= $row['item_price']; ?></p>
                    <p class="feat-product-text lh-sm text-secondary"><?= $row['item_desc']; ?></p>
                    <div>
                        <?php
                        for ($i = 1; $i <= $n_stars; $i++) {
                            echo '<i class="bi bi-star-fill"></i>';
                        }
                        for ($i = 5 - $n_stars; $i >= 1; $i--) {
                            echo '<i class="bi bi-star"></i>';
                        }
                        ?>
                        <span class="ms-2">(<?= $n_reviews; ?>)</span>
                    </div>
                    <div>
                        <!-- <a href="added.php?id=<?= $row['item_id']; ?>" class="<?= $item_in_cart ? 'in-cart-button' : 'hero-button'; ?> btn btn-outline text-uppercase mt-4">Add to Cart</a> -->
                        <form action="" method="POST">
                            <input type="hidden" name="item_id" value="<?= $row['item_id']; ?>">
                            <button type="submit" class="<?= $item_in_cart ? 'in-cart-button' : 'hero-button'; ?> btn btn-outline text-uppercase mt-4">Add To Cart</button>
                        </form>
                    </div>
                </div>
            </div>
            <?php
            if ($n % 4 == 0) {
            ?>
            </div>
    <?php
            }
            $n = $n + 1;
        }
    ?>
</section>
<?php
require('includes/footer.php');
?>