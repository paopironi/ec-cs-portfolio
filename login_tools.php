<?php
function validate(mysqli $link, string $email, string $password)
{
    $errors = array();
    if ($email == "") {
        $errors[] = "Enter your email.";
    }
    if ($password == "") {
        $errors[] = "Enter a valid password.";
    }
    if (empty($errors)) {
        $query = mysqli_prepare($link, "select user_id, first_name, last_name, pass from users where email=?");
        mysqli_stmt_bind_param($query, "s", $email);
        @mysqli_stmt_execute($query);
        $r = mysqli_stmt_get_result($query);
        if ($r->num_rows == 0) {
            $errors[] = "Email not found.";
            return array(false, $errors);
        }
        $row = mysqli_fetch_assoc($r);
        $pass = $row['pass'];
        if ($pass != $password) {
            $errors[] = "Wrong credentials.";
            return array(false, $errors);
        }
        return array(true, $row);
    }
    return array(false, $errors);
}
function load(string $url)
{
    header("location: https://" . $_SERVER["HTTP_HOST"] . "/" . $url);
    exit;
}
