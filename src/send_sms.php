<?php
require '../vendor/autoload.php';

use Vonage\Client\Credentials\Basic;
use Vonage\Client;
use Dotenv\Dotenv;

// Specify the path to the .env file relative to the current PHP file
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

function sendSms($to, $name, $message) {
    // Get API key and secret from environment variables
    $apiKey = $_ENV['VONAGE_API_KEY'];
    $apiSecret = $_ENV['VONAGE_API_SECRET'];

    // Check if API key and secret are set
    if (!$apiKey || !$apiSecret) {
        return ['status' => 'error', 'message' => 'API key or secret not set'];
    }

    // Replace spaces with underscores in the sender name
    $name = str_replace(' ', '_', $name);

    // Create Vonage client with credentials
    $basic = new Basic($apiKey, $apiSecret);
    $client = new Client($basic);

    try {
        if (is_array($to)) {
            // Bulk SMS mode: Send SMS to multiple numbers
            $responses = [];
            foreach ($to as $phoneNumber) {
                $response = $client->sms()->send(
                    new \Vonage\SMS\Message\SMS($phoneNumber, $name, $message)
                );
                $responses[] = $response->current();
            }

            // Check response status for each message
            foreach ($responses as $message) {
                if ($message->getStatus() != 0) {
                    return ['status' => 'error', 'message' => "Message failed with status: " . $message->getStatus()];
                }
            }

            return ['status' => 'success', 'message' => 'Messages sent successfully'];
        } else {
            // Single SMS mode: Send SMS to a single number
            $response = $client->sms()->send(
                new \Vonage\SMS\Message\SMS($to, $name, $message)
            );

            // Check response status
            $message = $response->current();
            if ($message->getStatus() == 0) {
                return ['status' => 'success', 'message' => "Message sent to $to"];
            } else {
                return ['status' => 'error', 'message' => "Message failed with status: " . $message->getStatus()];
            }
        }
    } catch (\Exception $e) {
        // Handle exceptions
        return ['status' => 'error', 'message' => "An error occurred: " . $e->getMessage()];
    }
}
?>

