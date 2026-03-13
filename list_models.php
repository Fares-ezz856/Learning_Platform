<?php

require __DIR__.'/vendor/autoload.php';

// Load .env manually
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$apiKey = $_ENV['GEMINI_API_KEY'] ?? null;

if (!$apiKey) {
    die("Error: GEMINI_API_KEY not found in .env\n");
}

echo "Using API Key: " . substr($apiKey, 0, 5) . "..." . substr($apiKey, -5) . "\n";

// Use the global Gemini class from the client package
$client = \Gemini::client($apiKey);

try {
    echo "Listing models...\n";
    $models = $client->models()->list();
    foreach ($models->models as $model) {
        echo " - " . $model->name . " (" . $model->displayName . ")\n";
    }
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
