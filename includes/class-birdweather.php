<?php
/**
 * Core plugin class
 */
class BirdWeather {
    /**
     * Initialize plugin functionality
     */
    public static function init() {
        bw_log('BirdWeather class initializing...');

        // Set up admin area
        if (is_admin()) {
            add_action('admin_menu', [self::class, 'setup_admin']);
            add_action('admin_init', [self::class, 'register_settings']);
            add_action('admin_notices', [self::class, 'check_token']);
        }

        // Set up frontend
        add_action('init', [BirdWeather_Shortcodes::class, 'init']);
        
        bw_log('BirdWeather initialization complete');
    }

    /**
     * Set up admin menu
     */
    public static function setup_admin() {
        bw_log('Setting up admin menu...');
        
        add_options_page(
            __('BirdWeather Settings', 'birdweather'),
            __('BirdWeather', 'birdweather'),
            'manage_options',
            'birdweather-settings',
            [BirdWeather_Settings::class, 'render_settings_page']
        );

        bw_log('Admin menu setup complete');
    }

    /**
     * Register plugin settings
     */
    public static function register_settings() {
        bw_log('Registering settings...');
        
        register_setting(
            'bw_settings_group',
            'bw_station_token',
            [
                'type' => 'string',
                'sanitize_callback' => [BirdWeather_Settings::class, 'sanitize_token'],
                'default' => ''
            ]
        );

        add_settings_section(
            'bw_main_section',
            __('Station Configuration', 'birdweather'),
            [BirdWeather_Settings::class, 'render_section_header'],
            'birdweather-settings'
        );

        add_settings_field(
            'bw_station_token',
            __('Station Token', 'birdweather'),
            [BirdWeather_Settings::class, 'render_token_field'],
            'birdweather-settings',
            'bw_main_section'
        );

        bw_log('Settings registration complete');
    }

    /**
     * Check token and show admin notice if missing
     */
    public static function check_token() {
        // Don't show on settings page
        if (get_current_screen()->id === 'settings_page_birdweather-settings') {
            return;
        }

        $token = get_option('bw_station_token');
        if (empty($token)) {
            printf(
                '<div class="notice notice-warning is-dismissible"><p>%s <a href="%s">%s</a></p></div>',
                esc_html__('BirdWeather needs configuration:', 'birdweather'),
                esc_url(admin_url('options-general.php?page=birdweather-settings')),
                esc_html__('Enter your station token', 'birdweather')
            );
        }
    }

    /**
     * Plugin activation
     */
    public static function activate() {
        bw_log('Plugin activation starting...');

        // Create/update options
        update_option('bw_station_token', '');
        update_option('bw_version', BW_VERSION);

        // Clear any cached data
        global $wpdb;
        $wpdb->query("DELETE FROM $wpdb->options WHERE option_name LIKE '_transient_bw_%'");

        // Force permalinks refresh
        flush_rewrite_rules();

        bw_log('Plugin activation complete');
    }

    /**
     * Plugin deactivation
     */
    public static function deactivate() {
        bw_log('Plugin deactivation starting...');

        // Clear cached data
        global $wpdb;
        $wpdb->query("DELETE FROM $wpdb->options WHERE option_name LIKE '_transient_bw_%'");

        // Clear version
        delete_option('bw_version');

        // Force permalinks refresh
        flush_rewrite_rules();

        bw_log('Plugin deactivation complete');
    }
}