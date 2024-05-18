<?php
require '../vendor/autoload.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Fetch environment variables using $_ENV superglobal
$apiKey = $_ENV['VONAGE_API_KEY'] ?? 'Not set';
$apiSecret = $_ENV['VONAGE_API_SECRET'] ?? 'Not set';

// Output the environment variables
echo "API Key: " . $apiKey . "\n";
echo "API Secret: " . $apiSecret . "\n";

