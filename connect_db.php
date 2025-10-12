<?php
mysqli_report(MYSQLI_REPORT_OFF);
$link = @mysqli_connect('127.0.0.1', 'root', '', 'mktime');
if (!$link) {
    die('Could not connect to MySQL: ' . mysqli_connect_error());
}
