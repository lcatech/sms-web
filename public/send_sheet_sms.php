<?php
require '../src/send_sms.php';
require '../src/google_sheets.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $spreadsheetId = $_POST['spreadsheet_id'];
    $range = $_POST['range'];
    $message = $_POST['message'];

    $data = getSheetData($spreadsheetId, $range);

    foreach ($data as $row) {
        $name = $row[0];
        $number = $row[1];
        $result = sendSms($number, $name, $message);
        echo "Message sent to $name ($number): " . $result['messages'][0]['status'] . "<br>";
    }
}
?>
