<?php
/**
 * Plugin Name: BirdWeather Integration
 * Description: Display BirdWeather station data in WordPress
 * Version: 1.0.0
 * Author: Gerald Haygood
 * Text Domain: birdweather
 */

// Prevent direct access
defined('ABSPATH') || exit;

// Define plugin constants
define('BW_VERSION', '1.0.0');
define('BW_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('BW_PLUGIN_URL', plugin_dir_url(__FILE__));

// Debug helper
if (!function_exists('bw_log')) {
    function bw_log($message) {
        if (WP_DEBUG === true) {
            if (is_array($message) || is_object($message)) {
                error_log('BirdWeather Debug: ' . print_r($message, true));
            } else {
                error_log('BirdWeather Debug: ' . $message);
            }
        }
    }
}

// Plugin activation
function bw_activate() {
    bw_log('Activating BirdWeather plugin...');

    // Check PHP version
    if (version_compare(PHP_VERSION, '7.0', '<')) {
        deactivate_plugins(plugin_basename(__FILE__));
        wp_die('BirdWeather requires PHP 7.0 or higher.');
    }

    // Check WordPress version
    if (version_compare($GLOBALS['wp_version'], '5.0', '<')) {
        deactivate_plugins(plugin_basename(__FILE__));
        wp_die('BirdWeather requires WordPress 5.0 or higher.');
    }

    // Initialize options
    add_option('bw_station_token', '');
    add_option('bw_version', BW_VERSION);

    // Clear any cached data
    global $wpdb;
    $wpdb->query("DELETE FROM $wpdb->options WHERE option_name LIKE '_transient_bw_%'");

    // Force refresh
    flush_rewrite_rules();

    bw_log('Plugin activated successfully');
}
register_activation_hook(__FILE__, 'bw_activate');

// Plugin deactivation
function bw_deactivate() {
    bw_log('Deactivating BirdWeather plugin...');

    // Clear cached data
    global $wpdb;
    $wpdb->query("DELETE FROM $wpdb->options WHERE option_name LIKE '_transient_bw_%'");

    // Force refresh
    flush_rewrite_rules();

    bw_log('Plugin deactivated successfully');
}
register_deactivation_hook(__FILE__, 'bw_deactivate');

// Load required files
require_once BW_PLUGIN_DIR . 'includes/class-api-client.php';
require_once BW_PLUGIN_DIR . 'includes/class-shortcodes.php';
require_once BW_PLUGIN_DIR . 'includes/admin/class-settings.php';
require_once BW_PLUGIN_DIR . 'includes/class-birdweather.php';

// Initialize plugin
function bw_init() {
    bw_log('Initializing BirdWeather plugin...');

    // Load translations
    load_plugin_textdomain('birdweather', false, dirname(plugin_basename(__FILE__)) . '/languages');

    // Initialize components
    BirdWeather::init();

    // Add settings link to plugins page
    add_filter('plugin_action_links_' . plugin_basename(__FILE__), function($links) {
        $settings_link = sprintf(
            '<a href="%s">%s</a>',
            admin_url('options-general.php?page=birdweather-settings'),
            __('Settings', 'birdweather')
        );
        array_unshift($links, $settings_link);
        return $links;
    });

    bw_log('Plugin initialized successfully');
}
add_action('plugins_loaded', 'bw_init');