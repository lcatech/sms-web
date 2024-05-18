<?php
require '../vendor/autoload.php';

use Google_Client;
use Google_Service_Sheets;

function getGoogleClient() {
    $client = new Google_Client();
    $client->setApplicationName('ChurchSms');
    $client->setScopes(Google_Service_Sheets::SPREADSHEETS_READONLY);
    $client->setAuthConfig('../credentials.json');
    $client->setAccessType('offline');

    return $client;
}

function getSheetData($spreadsheetId, $range) {
    $client = getGoogleClient();
    $service = new Google_Service_Sheets($client);

    $response = $service->spreadsheets_values->get($spreadsheetId, $range);
    $values = $response->getValues();

    return $values;
}
?>
