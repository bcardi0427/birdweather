<?php
// Direct access check
defined('ABSPATH') || die('Access denied.');

// Your known working token
$token = '1XFRNMBm4Ha7nZTYHeCo5uP8';

// Using your exact working URL
$url = "https://app.birdweather.com/api/v1/stations/{$token}/species/?limit=10";

echo "Testing BirdWeather API connection...\n";
echo "URL: {$url}\n\n";

// Make request
$response = wp_remote_get($url);

if (is_wp_error($response)) {
    echo "Error: " . $response->get_error_message() . "\n";
    exit;
}

$status = wp_remote_retrieve_response_code($response);
$body = wp_remote_retrieve_body($response);

echo "Status: {$status}\n";
echo "Response:\n{$body}\n";

// Parse response
$data = json_decode($body, true);
if (!$data) {
    echo "Error parsing JSON response\n";
    exit;
}

// Show results
if (isset($data['species']) && is_array($data['species'])) {
    echo "\nFound " . count($data['species']) . " species:\n";
    foreach ($data['species'] as $species) {
        echo "- {$species['commonName']} ({$species['detections']['total']} detections)\n";
    }
}