<?php
require('includes/nav.php');
?>
<!-- Login Form -->
<div class="container mb-5" style="max-width: 576px;">
    <h1 class="mt-5 mb-3 text-uppercase">My Account</h1>
    <p>Please log in to your account</p>
    <div class="mt-5">
        <form action="login_action.php" method="POST">
            <div class="mb-4">
                <!-- <label for="formGroupExampleInput" class="form-label text-body-tertiary">Username/Email</label> -->
                <input type="text" name="email" class="form-control" id="formGroupExampleInput" placeholder="Username/Email" value="<?= $email ?? ''; ?>">
            </div>
            <div class="mb-4">
                <!-- <label for="formGroupExampleInput2" class="form-label text-body-tertiary">Password</label> -->
                <input type="password" name="password" class="form-control" id="formGroupExampleInput2" placeholder="Password">
            </div>
            <!-- <div class="form-check mb-4">
                <input class="form-check-input" type="checkbox" id="autoSizingCheck">
                <label class="form-check-label" for="autoSizingCheck">
                    Remember me
                </label>
            </div> -->
            <div class="d-grid col-4 mx-auto">
                <button type="submit" class="btn btn-outline text-uppercase login-button">Login</button>
            </div>
            <p class="small mt-5">Dont't have an account? <a href="register.php"
                    class="link-secondary">Register</a></p>
        </form>
    </div>
</div>
<?php
require('includes/footer.php');
?>