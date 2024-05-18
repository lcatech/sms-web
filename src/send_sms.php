<?php
require '../vendor/autoload.php';

use Vonage\Client\Credentials\Basic;
use Vonage\Client;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

function sendSms($to, $name, $message) {
    $apiKey = $_ENV['VONAGE_API_KEY'];
    $apiSecret = $_ENV['VONAGE_API_SECRET'];

    if (!$apiKey || !$apiSecret) {
        return ['status' => 'error', 'message' => 'API key or secret not set'];
    }

    $name = str_replace(' ', '_', $name);

    $basic = new Basic($apiKey, $apiSecret);
    $client = new Client($basic);

    try {
        $response = $client->sms()->send(
            new \Vonage\SMS\Message\SMS($to, $name, $message)
        );

        $message = $response->current();
        if ($message->getStatus() == 0) {
            return ['status' => 'success', 'message' => "Message sent to $to"];
        } else {
            return ['status' => 'error', 'message' => "Message failed with status: " . $message->getStatus()];
        }
    } catch (\Exception $e) {
        return ['status' => 'error', 'message' => "An error occurred: " . $e->getMessage()];
    }
}
?>
