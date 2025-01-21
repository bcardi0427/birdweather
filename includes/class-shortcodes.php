<?php
class BirdWeather_Shortcodes {
    private static $api;

    public static function init() {
        self::$api = new BirdWeather_API_Client();
        add_shortcode('bw_observations', [self::class, 'render_observations']);
        add_shortcode('bw_location', [self::class, 'render_location']);
        add_shortcode('bw_top_species', [self::class, 'render_top_species']);
        add_shortcode('bw_recent_detections', [self::class, 'render_recent_detections']);
        add_shortcode('bw_period_stats', [self::class, 'render_period_stats']);
        add_action('wp_enqueue_scripts', [self::class, 'add_styles']);
    }

    public static function render_top_species($atts) {
        $atts = shortcode_atts([
            'period' => 'day',
            'limit' => 10,
            'sort' => 'top',
            'order' => 'desc'
        ], $atts);

        $data = self::$api->get_top_species(
            $atts['period'],
            intval($atts['limit']),
            $atts['sort'],
            $atts['order']
        );

        if (is_wp_error($data)) {
            return sprintf('<div class="bw-error">%s</div>',
                esc_html($data->get_error_message())
            );
        }

        ob_start(); ?>
        <div class="bw-species-list">
            <h3>Top Species - <?php echo esc_html(ucfirst($atts['period'])); ?></h3>
            <ul>
            <?php foreach ($data['species'] as $species): ?>
                <li class="bw-species-item">
                    <span class="species-name"><?php echo esc_html($species['commonName']); ?></span>
                    <span class="species-count"><?php
                        echo esc_html(sprintf(
                            _n('%d detection', '%d detections',
                            $species['detections']['total'], 'birdweather'),
                            $species['detections']['total']
                        ));
                    ?></span>
                </li>
            <?php endforeach; ?>
            </ul>
        </div>
        <?php
        return ob_get_clean();
    }

    public static function render_recent_detections($atts) {
        $atts = shortcode_atts([
            'limit' => 10,
            'species' => ''
        ], $atts);

        $data = self::$api->get_recent_detections(
            intval($atts['limit']),
            $atts['species']
        );

        if (is_wp_error($data)) {
            return sprintf('<div class="bw-error">%s</div>',
                esc_html($data->get_error_message())
            );
        }

        ob_start(); ?>
        <div class="bw-detections-list">
            <h3>Recent Detections</h3>
            <ul>
            <?php foreach ($data['detections'] as $detection): ?>
                <li class="bw-detection-item">
                    <span class="detection-species"><?php
                        bw_log('Detection data: ' . print_r($detection, true));
                        echo esc_html($detection['species']['commonName'] ?? $detection['commonName'] ?? 'Unknown');
                    ?></span>
                    <span class="detection-time">
                        <?php echo esc_html(human_time_diff(strtotime($detection['timestamp'] ?? $detection['detectedAt'])) . ' ago'); ?>
                    </span>
                </li>
            <?php endforeach; ?>
            </ul>
        </div>
        <?php
        return ob_get_clean();
    }

    public static function render_period_stats($atts) {
        $atts = shortcode_atts([
            'period' => 'day'
        ], $atts);

        $data = self::$api->get_period_stats($atts['period']);

        if (is_wp_error($data)) {
            return sprintf('<div class="bw-error">%s</div>',
                esc_html($data->get_error_message())
            );
        }

        return sprintf(
            '<div class="bw-stats">%s: %d detections across %d species</div>',
            esc_html(ucfirst($atts['period'])),
            esc_html($data['detections']),
            esc_html($data['species'])
        );
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
            .bw-species-list, .bw-detections-list {
                background: #f8f9fa;
                padding: 15px;
                border-radius: 8px;
                border: 1px solid #ddd;
                margin: 10px 0;
            }
            .bw-species-list ul, .bw-detections-list ul {
                list-style: none;
                padding: 0;
                margin: 10px 0;
            }
            .bw-species-item, .bw-detection-item {
                display: flex;
                justify-content: space-between;
                padding: 8px;
                border-bottom: 1px solid #eee;
            }
            .bw-species-item:last-child, .bw-detection-item:last-child {
                border-bottom: none;
            }
            .species-count, .detection-time {
                color: #666;
                font-size: 0.9em;
            }
            .bw-error {
                color: #dc3545;
                padding: 10px;
                border: 1px solid currentColor;
                border-radius: 4px;
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