<?php
session_start();
$user = $_SESSION['first_name'] ?? null;
$title = 'Contact';
require("includes/nav.php");
?>
<!-- Contact Section -->
<section class="container my-5">
    <h3 class="text-uppercase">Contact Us</h3>
    <div class="mt-5 row gap-3">
        <div
            class="col-6 offset-3 col-lg-4 offset-lg-2 p-4 border border-secondary border-opacity-50 d-flex flex-column align-items-center justify-content-between">
            <div>
                <div class="text-uppercase text-center fs-4 fw-semibold">Talk to sales</div>
                <p class="fst-italic text-center" style="font-size: smaller;">Just pick up the phone to chat to our
                    sales team.
                </p>
            </div>
            <p class="text-secondary fw-semibold fs-4">0131 123 4567</p>
        </div>
        <div
            class="col-6 offset-3 offset-lg-0 col-lg-4 p-4 border border-secondary border-opacity-50 d-flex flex-column align-items-center justify-content-between">
            <div>
                <div class="text-uppercase text-center fs-4 fw-semibold">Service & Repair Team</div>
                <p class="fst-italic text-center" style="font-size: smaller;">Sometimes you need a little help from
                    our support
                    reps. Don't worry, we are here for you.
                </p>
            </div>
            <p class="text-secondary fw-semibold fs-4">0131 234 5678</p>
        </div>
    </div>
</section>
<?php
require("includes/footer.php");
?>