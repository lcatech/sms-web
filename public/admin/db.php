<?php
$servername = "localhost";
$username = "it-man";
$password = "yo.51I1zqtpX[g.QS";
$dbname = "sms_web";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
