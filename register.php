<?php
require('includes/nav.php');
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require("connect_db.php");
    $errors = [];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    // Validate the input. Required fields should not be empty. Email should be unique. The password confirmation should pass.
    if ($first_name == "") {
        $errors[] = "Enter your first name.";
    }
    if ($last_name == "") {
        $errors[] = "Enter your last name.";
    }
    if ($email == "") {
        $errors[] = "Enter your email.";
    }
    if ($password == "") {
        $errors[] = "Enter a password.";
    }
    if ($confirm_password == "") {
        $errors[] = "Confirm your password.";
    }
    if ($password != $confirm_password) {
        $errors[] = "Passwords do not match.";
    }
    if (empty($errors)) {
        $query = mysqli_prepare($link, "select user_id from users where email=?");
        mysqli_stmt_bind_param($query, "s", $email);
        @mysqli_stmt_execute($query);
        $r = mysqli_stmt_get_result($query);
        if (mysqli_num_rows($r) != 0)
            $errors[] =
                'Email address already registered.';;
    }
    if (empty($errors)) {
        $query = mysqli_prepare($link, "insert into users (first_name, last_name, email, pass, reg_date ) values (?, ?, ?, ?, ?)");
        $now = date('Y-m-d H:i:s');
        mysqli_stmt_bind_param($query, "sssss", $first_name, $last_name, $email, $password, $now);
        $created = @mysqli_stmt_execute($query);
        if ($created) {
?>
            <main class="container py-3 mt-5">
                <p class="alert alert-success">Successfully registered!</p>
                <div class="mt-3">
                    Please <a href="login.php">login</a>
                </div>
            </main>
        <?php
            require("includes/footer.php");
            mysqli_close($link);
            die();
        }
    } else { ?>
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
        # Close database connection.
        mysqli_close($link);
    }
}
?>
<!-- Register Form -->
<div class="container mb-5" style="max-width: 576px;">
    <h1 class="mt-5 mb-3 text-uppercase">Create an Account</h1>
    <div class="mt-5">
        <form method="POST">
            <div class="input-group mb-4">
                <span class="input-group-text bg-white">First and last name</span>
                <input type="text" name="first_name" aria-label="First name" class="form-control" value="<?= $first_name ?? ''; ?>">
                <input type="text" name="last_name" aria-label="Last name" class="form-control" value="<?= $last_name ?? ''; ?>">
            </div>
            <!-- <div class="mb-3">
                    <label for="formGroupExampleInput" class="form-label">Name</label>
                    <input type="text" class="form-control" id="formGroupExampleInput" placeholder="Name">
                </div>
                <div class="mb-3">
                    <label for="formGroupExampleInput2" class="form-label">Surname</label>
                    <input type="text" class="form-control" id="formGroupExampleInput2" placeholder="Surname">
                </div> -->
            <div class="mb-4">
                <!-- <label for="formGroupExampleInput3" class="form-label">Username/Email</label> -->
                <input type="text" name="email" class="form-control" id="formGroupExampleInput3" placeholder="Username/Email" value="<?= $email ?? ''; ?>">
            </div>
            <div class="mb-4">
                <!-- <label for="formGroupExampleInput4" class="form-label">Password</label> -->
                <input type="password" name="password" class="form-control" id="formGroupExampleInput4" placeholder="Password">
            </div>
            <div class="mb-4">
                <!-- <label for="formGroupExampleInput5" class="form-label">Confirm Password</label> -->
                <input type="password" name="confirm_password" class="form-control" id="formGroupExampleInput5"
                    placeholder="Confirm Password">
            </div>
            <div class="d-grid col-4 mx-auto">
                <button type="submit" class="btn btn-outline text-uppercase login-button">Register</button>
            </div>
            <p class="small mt-5">Already registered? <a href="login.php" class="link-secondary">Login</a></p>
        </form>
    </div>
</div>
<?php
require('includes/footer.php');
?>