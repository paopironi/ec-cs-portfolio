<?php

# Connect  on 'localhost, username, password, database name'.
mysqli_report(MYSQLI_REPORT_OFF);

$link = @mysqli_connect('127.0.0.1', 'root', '', 'mktime');

if (!$link) {

    # Otherwise fail gracefully and explain the error. 

    die('Could not connect to MySQL: ' . mysqli_connect_error());
}
?>
<!-- <p class="container">Connected to the database successfully!</p> -->