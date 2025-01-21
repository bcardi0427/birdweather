<?php
class BirdWeather_Shortcodes {
    private static $api;

    public static function init() {
        self::$api = new BirdWeather_API_Client();
        add_shortcode('bw_observations', [self::class, 'render_observations']);
        add_shortcode('bw_location', [self::class, 'render_location']);
        add_action('wp_enqueue_scripts', [self::class, 'add_styles']);
    }

    public static function render_location() {
        $api = new BirdWeather_API_Client();
        $data = $api->get_station_info();
        
        if (is_wp_error($data)) {
            return sprintf('<div class="bw-error">%s</div>',
                esc_html($data->get_error_message())
            );
        }

        if (!isset($data['detections']) || !isset($data['species'])) {
            return '<div class="bw-error">No station data available</div>';
        }

        return sprintf(
            '<div class="bw-stats">Today: %d detections across %d species</div>',
            esc_html($data['detections']),
            esc_html($data['species'])
        );
    }

    public static function add_styles() {
        wp_add_inline_style('wp-block-library', '
            .bird-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
                gap: 20px;
                margin: 20px 0;
            }
            .bird-card {
                border: 1px solid #ddd;
                border-radius: 8px;
                padding: 15px;
                display: flex;
                gap: 15px;
                background: #fff;
            }
            .bird-image {
                width: 100px;
                height: 100px;
                border-radius: 4px;
            }
            .bird-info {
                flex: 1;
            }
            .bird-name {
                font-size: 1.2em;
                font-weight: bold;
                margin: 0 0 5px;
            }
            .bird-scientific {
                font-style: italic;
                color: #666;
                margin: 0 0 10px;
            }
            .bird-stats {
                font-size: 0.9em;
                color: #444;
            }
            .bird-time {
                font-size: 0.8em;
                color: #666;
                margin-top: 10px;
            }
            .bw-stats {
                background: #f8f9fa;
                padding: 15px;
                border-radius: 8px;
                border: 1px solid #ddd;
                font-size: 1.1em;
                color: #333;
                margin: 10px 0;
            }
        ');
    }

    public static function render_observations($atts) {
        $atts = shortcode_atts([
            'limit' => 7,
            'show_scientific' => false,
            'show_thumbnails' => false
        ], $atts);

        $data = self::$api->get_observations(
            intval($atts['limit']),
            filter_var($atts['show_scientific'], FILTER_VALIDATE_BOOLEAN),
            filter_var($atts['show_thumbnails'], FILTER_VALIDATE_BOOLEAN)
        );
        
        if (is_wp_error($data)) {
            return sprintf('<div style="color: #dc3545;">%s</div>', esc_html($data->get_error_message()));
        }

        ob_start(); ?>
        <div class="bird-grid">
            <?php foreach ($data['species'] as $bird): ?>
                <div class="bird-card">
                    <?php if ($atts['show_thumbnails'] && !empty($bird['thumbnailUrl'])): ?>
                        <img class="bird-image"
                             src="<?php echo esc_url($bird['thumbnailUrl']); ?>"
                             alt="<?php echo esc_attr($bird['commonName']); ?>"
                             loading="lazy">
                    <?php endif; ?>
                    
                    <div class="bird-info">
                        <h3 class="bird-name"><?php echo esc_html($bird['commonName']); ?></h3>
                        <?php if ($atts['show_scientific'] && !empty($bird['scientificName'])): ?>
                            <p class="bird-scientific"><?php echo esc_html($bird['scientificName']); ?></p>
                        <?php endif; ?>
                        
                        <div class="bird-stats">
                            <?php if (!empty($bird['detections'])): ?>
                                <?php echo esc_html(sprintf(
                                    _n('%d detection today', '%d detections today', 
                                    $bird['detections']['total'], 'birdweather'),
                                    $bird['detections']['total']
                                )); ?>
                            <?php endif; ?>
                        </div>

                        <?php if (!empty($bird['latestDetectionAt'])): ?>
                            <div class="bird-time">
                                <?php echo esc_html(sprintf(
                                    'Last seen: %s ago',
                                    human_time_diff(strtotime($bird['latestDetectionAt']))
                                )); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <?php
        return ob_get_clean();
    }
}