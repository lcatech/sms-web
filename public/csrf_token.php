<?php
session_start();

if (empty($_SESSION['token'])) {
    $_SESSION['token'] = bin2hex(random_bytes(32));
}

function get_csrf_token() {
    return $_SESSION['token'];
}

function validate_csrf_token($token) {
    return hash_equals($_SESSION['token'], $token);
}
?>

