<?php
// Direct script access check
if (!defined('ABSPATH')) {
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    
    // Get token from query string
    $token = isset($_GET['token']) ? trim($_GET['token']) : '';
    
    if (empty($token)) {
        die(json_encode(['error' => 'No token provided']));
    }

    // Test URL
    $url = "https://app.birdweather.com/api/v1/stations/{$token}/species/?limit=10";
    
    // Basic cURL request
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTPHEADER => ['Accept: application/json']
    ]);

    $response = curl_exec($ch);
    $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);

    // Return results
    die(json_encode([
        'success' => $status === 200,
        'url' => $url,
        'status' => $status,
        'error' => $error ?: null,
        'response' => $status === 200 ? json_decode($response, true) : $response
    ]));
}

// WordPress loaded
function bw_test_api() {
    $token = get_option('bw_station_token');
    
    if (empty($token)) {
        wp_die('No token configured. Please set up the BirdWeather plugin first.');
    }

    echo '<div style="max-width: 800px; margin: 20px auto; padding: 20px; background: #fff; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">';
    echo '<h2>BirdWeather API Test</h2>';
    echo '<p>Testing connection with token: <code>' . esc_html($token) . '</code></p>';

    $url = "https://app.birdweather.com/api/v1/stations/{$token}/species/?limit=10";
    echo '<p>URL: <code>' . esc_html($url) . '</code></p>';

    $response = wp_remote_get($url);

    if (is_wp_error($response)) {
        echo '<div style="color: #dc3545; padding: 10px; border: 1px solid currentColor; border-radius: 4px; margin: 10px 0;">';
        echo '<strong>Error:</strong> ' . esc_html($response->get_error_message());
        echo '</div>';
        wp_die();
    }

    $status = wp_remote_retrieve_response_code($response);
    $body = wp_remote_retrieve_body($response);

    echo '<h3>Response Details:</h3>';
    echo '<p><strong>Status:</strong> ' . esc_html($status) . '</p>';
    
    $data = json_decode($body, true);
    if ($data) {
        echo '<div style="background: #f8f9fa; padding: 15px; border-radius: 4px; margin: 10px 0;">';
        echo '<pre style="margin: 0; white-space: pre-wrap;">' . esc_html(json_encode($data, JSON_PRETTY_PRINT)) . '</pre>';
        echo '</div>';
        
        if (isset($data['species']) && is_array($data['species'])) {
            echo '<h3>Species Found:</h3>';
            echo '<ul>';
            foreach ($data['species'] as $species) {
                printf(
                    '<li><strong>%s</strong> - %d detections</li>',
                    esc_html($species['commonName']),
                    $species['detections']['total']
                );
            }
            echo '</ul>';
        }
    } else {
        echo '<p style="color: #dc3545;">Could not parse JSON response.</p>';
        echo '<pre>' . esc_html($body) . '</pre>';
    }

    echo '</div>';
}

// Add to admin menu
add_action('admin_menu', function() {
    add_submenu_page(
        'tools.php',
        'BirdWeather API Test',
        'BirdWeather Test',
        'manage_options',
        'birdweather-test',
        'bw_test_api'
    );
});