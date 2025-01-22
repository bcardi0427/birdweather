<?php
class BirdWeather_Settings {
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
        echo '<p>' . esc_html__('Configure your BirdWeather integration settings below.', 'birdweather') . '</p>';
    }

    public static function render_token_field() {
        $value = get_option('bw_station_token');
        ?>
        <input type="text"
            id="bw_station_token"
            name="bw_station_token"
            value="<?php echo esc_attr($value); ?>"
            class="regular-text"
        />
        <p class="description">
            <?php esc_html_e('Enter your BirdWeather station token. You can find this in your station settings.', 'birdweather'); ?>
        </p>
        <?php
    }

    public static function render_debug_field() {
        ?>
        <label class="bw-toggle">
            <input type="checkbox"
                id="bw_enable_debug"
                name="bw_enable_debug"
                value="1"
                <?php checked(get_option('bw_enable_debug'), '1'); ?>
            />
            <span class="bw-toggle-slider"></span>
        </label>
        <p class="description">
            <?php esc_html_e('Enable debug logging', 'birdweather'); ?>
        </p>
        <style>
            .bw-toggle {
                position: relative;
                display: inline-block;
                width: 60px;
                height: 34px;
            }
            .bw-toggle input {
                opacity: 0;
                width: 0;
                height: 0;
            }
            .bw-toggle-slider {
                position: absolute;
                cursor: pointer;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background-color: #ccc;
                transition: .4s;
                border-radius: 34px;
            }
            .bw-toggle-slider:before {
                position: absolute;
                content: "";
                height: 26px;
                width: 26px;
                left: 4px;
                bottom: 4px;
                background-color: white;
                transition: .4s;
                border-radius: 50%;
            }
            .bw-toggle input:checked + .bw-toggle-slider {
                background-color: #2196F3;
            }
            .bw-toggle input:checked + .bw-toggle-slider:before {
                transform: translateX(26px);
            }
        </style>
        <?php
    }

    public static function sanitize_token($token) {
        $token = trim($token);
        if (get_option('bw_enable_debug')) {
            bw_log('Validating token: ' . $token);
        }
        
        if (empty($token)) {
            return '';
        }

        // Test the token
        if (!BirdWeather_API_Client::test_token($token)) {
            add_settings_error(
                'bw_station_token',
                'invalid_token',
                __('Could not verify token. Please try again.', 'birdweather')
            );
            return get_option('bw_station_token');
        }

        return $token;
    }
}