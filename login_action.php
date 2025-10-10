<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require("connect_db.php");
    $errors = [];
    $email = $_POST['email'];
    $password = $_POST['password'];
    require('login_tools.php');
    list($check, $data) = validate($link, $email, $password);
    if ($check) {
        session_start();
        $_SESSION['user_id'] = $data['user_id'];
        $_SESSION['first_name'] = $data['first_name'];
        $_SESSION['last_name'] = $data['last_name'];
        $_SESSION['cart'] = array();
        mysqli_close($link);
        load('');
    } else {
        $errors = $data;
        require('includes/nav.php');
?>
        <main class="container py-3 mt-5">
            <p class="alert alert-danger">The following error(s) occurred:</p>
            <div class="mt-3">
                <?php
                foreach ($errors as $msg) {
                    echo " - $msg<br>";
                }
                ?>
                <p class="mt-3">Please try again.</p>
            </div>
        </main>
<?php
        require("includes/footer.php");
        mysqli_close($link);
    }
}
