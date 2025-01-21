<?php
class BirdWeather_API_Client {
    private $token;
    
    public function __construct() {
        $this->token = trim(get_option('bw_station_token'));
    }

    public function get_station_info() {
        if (empty($this->token)) {
            return new WP_Error('no_token', 'Please configure your BirdWeather station token');
        }

        $url = "https://app.birdweather.com/api/v1/stations/{$this->token}/stats/?period=1";
        $response = wp_remote_get($url);
        
        if (is_wp_error($response)) {
            return $response;
        }

        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);

        if (!$data || !isset($data['success']) || !$data['success']) {
            return new WP_Error('api_error', 'Could not fetch station data');
        }

        return $data;
    }

    public function get_observations($limit = 7, $show_scientific = false, $show_thumbnails = false) {
        if (empty($this->token)) {
            return new WP_Error('no_token', 'Please configure your BirdWeather station token');
        }

        // Exact URL format that worked
        $url = "https://app.birdweather.com/api/v1/stations/{$this->token}/species/?limit={$limit}";

        $response = wp_remote_get($url);
        
        if (is_wp_error($response)) {
            return $response;
        }

        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);

        if (!$data || !isset($data['success']) || !$data['success']) {
            return new WP_Error('api_error', 'Could not fetch bird data');
        }

        return $data;
    }

    public static function test_token($token) {
        if (empty($token)) {
            return false;
        }

        $url = "https://app.birdweather.com/api/v1/stations/{$token}/species/?limit=1";
        $response = wp_remote_get($url);

        if (is_wp_error($response)) {
            return false;
        }

        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);

        return isset($data['success']) && $data['success'] === true;
    }
}
