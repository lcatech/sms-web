<?php
require '../src/send_sms.php';
require 'csrf_token.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $csrf_token = sanitize_input($_POST['csrf_token']);
    
    if (!validate_csrf_token($csrf_token)) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid CSRF token']);
        exit;
    }

    $number = sanitize_input($_POST['number']);
    $name = sanitize_input($_POST['name']);
    $message = sanitize_input($_POST['message']);

    try {
        $result = sendSms($number, $name, $message);
        echo json_encode($result);
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => "An error occurred: " . $e->getMessage()]);
    }
    exit;
}

function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>

