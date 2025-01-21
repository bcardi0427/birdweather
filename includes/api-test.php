<?php
// Test token: 1XFRNMBm4Ha7nZTYHeCo5uP8

$token = '1XFRNMBm4Ha7nZTYHeCo5uP8';
$url = "https://app.birdweather.com/api/v1/stations/{$token}/species/?limit=10";

$response = wp_remote_get($url, [
    'timeout' => 30,
    'headers' => [
        'Accept' => 'application/json'
    ]
]);

if (is_wp_error($response)) {
    error_log('BirdWeather Test Error: ' . $response->get_error_message());
    return;
}

$status = wp_remote_retrieve_response_code($response);
$body = wp_remote_retrieve_body($response);

error_log('BirdWeather Test Status: ' . $status);
error_log('BirdWeather Test Response: ' . $body);