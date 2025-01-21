# BirdWeather WordPress Plugin

Version: 1.0.0
Author: Gerald Haygood

A WordPress plugin for displaying BirdWeather station data using shortcodes.

## Installation

1. Upload the plugin files to `/wp-content/plugins/birdweather/`
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Go to Settings > BirdWeather to configure your station token

## Shortcodes

### Station Stats
```
[bw_location]
```
Displays today's overall detection statistics for your station.
- Output: Shows total number of detections and unique species for the day

### Bird Observations
```
[bw_observations limit="5" show_scientific="true" show_thumbnails="true"]
```
Shows detailed bird observations with optional scientific names and thumbnails.
- `limit`: Number of observations to display (default: 7)
- `show_scientific`: Whether to show scientific names (true/false, default: false)
- `show_thumbnails`: Whether to show bird thumbnails (true/false, default: false)

### Top Species
```
[bw_top_species period="day" limit="10" sort="top" order="desc"]
```
Displays the most frequently detected species.
- `period`: Time period to show stats for (day/week/month, default: day)
- `limit`: Number of species to show (default: 10)
- `sort`: How to sort results (top/common_name/scientific_name, default: top)
- `order`: Sort direction (asc/desc, default: desc)

### Recent Detections
```
[bw_recent_detections limit="10" species=""]
```
Shows the most recent bird detections.
- `limit`: Number of detections to show (default: 10)
- `species`: Filter by species name (optional)

### Period Statistics
```
[bw_period_stats period="day"]
```
Shows detection statistics for a specific time period.
- `period`: Time period to show stats for (day/week/month, default: day)

## Examples

Display today's top 5 species:
```
[bw_top_species limit="5"]
```

Show recent detections of a specific species:
```
[bw_recent_detections limit="5" species="Eastern Bluebird"]
```

Display detailed observations with scientific names:
```
[bw_observations limit="10" show_scientific="true"]
```

Show monthly statistics:
```
[bw_period_stats period="month"]