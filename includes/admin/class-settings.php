<?php
class BirdWeather_Settings {
    private static $option_name = 'bw_station_token';

    public static function init() {
        // Settings are now registered in BirdWeather class
    }

    public static function render_settings_page() {
        ?>
        <div class="wrap">
            <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
            <form action="options.php" method="post">
                <?php
                settings_fields('bw_settings_group');
                do_settings_sections('birdweather-settings');
                submit_button();
                ?>
            </form>
        </div>
        <?php
    }

    public static function render_section_header() {
        echo '<p>' . esc_html__('Enter your BirdWeather station token below. You can find this in your station settings.', 'birdweather') . '</p>';
    }

    public static function render_token_field() {
        $value = get_option(self::$option_name);
        ?>
        <input type="text"
            id="<?php echo esc_attr(self::$option_name); ?>"
            name="<?php echo esc_attr(self::$option_name); ?>"
            value="<?php echo esc_attr($value); ?>"
            class="regular-text"
        />
        <?php
    }

    public static function sanitize_token($token) {
        $token = trim($token);
        bw_log('Validating token: ' . $token);
        
        if (empty($token)) {
            return '';
        }

        // Test the token
        if (!BirdWeather_API_Client::test_token($token)) {
            add_settings_error(
                self::$option_name,
                'invalid_token',
                __('Could not verify token. Please try again.', 'birdweather')
            );
            return get_option(self::$option_name);
        }

        return $token;
    }
}