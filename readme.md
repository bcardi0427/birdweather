# BirdWeather WordPress Plugin

**Version:** 1.0.0  
**Author:** [Gerald Haygood](https://github.com/bcardi0427/)

A WordPress plugin for displaying BirdWeather station data using shortcodes.

## Installation

1. Press the green Code button on GitHub and download the zip file, then install it like any other WordPress plugin
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Go to Settings > BirdWeather to:
   * Configure your station token
   * Enable/disable debug mode (helps with troubleshooting)

## Shortcodes

### Station Stats

```shortcode
[bw_location]
```
![Screenshot 2025-01-22 125505](https://github.com/user-attachments/assets/423ee3cc-601c-4b43-8082-52f1dc9f288c)


Displays today's overall detection statistics for your station.

* Output: Shows total number of detections and unique species for the day

### Bird Observations

```shortcode
[bw_observations limit="5" show_scientific="true" show_thumbnails="true"]
```
![Screenshot 2025-01-22 125518](https://github.com/user-attachments/assets/a8f99527-56aa-45f2-828d-df01856fda4a)


Shows detailed bird observations with optional scientific names and thumbnails.

* `limit`: Number of observations to display (default: 7)
* `show_scientific`: Whether to show scientific names (true/false, default: false)
* `show_thumbnails`: Whether to show bird thumbnails (true/false, default: false)

### Top Species

```shortcode
[bw_top_species period="day" limit="10" sort="top" order="desc"]
```
![Screenshot 2025-01-22 125540](https://github.com/user-attachments/assets/e1c81e4c-7351-407b-b33d-eacdc79c585f)


Displays the most frequently detected species.

* `period`: Time period to show stats for (day/week/month, default: day)
* `limit`: Number of species to show (default: 10)
* `sort`: How to sort results (top/common_name/scientific_name, default: top)
* `order`: Sort direction (asc/desc, default: desc)

### Recent Detections

```shortcode
[bw_recent_detections limit="10" species=""]
```
![Screenshot 2025-01-22 125556](https://github.com/user-attachments/assets/dc81905b-fad4-4fde-a5ad-3d17a37899b7)


Shows the most recent bird detections.

* `limit`: Number of detections to show (default: 10)
* `species`: Filter by species name (optional)

### Period Statistics

```shortcode
[bw_period_stats period="day"]
```
![Screenshot 2025-01-22 125609](https://github.com/user-attachments/assets/904241a5-3967-4b12-8706-ec57a248ec86)


Shows detection statistics for a specific time period.

* `period`: Time period to show stats for (day/week/month, default: day)

## Examples

### Display today's top 5 species:

```shortcode
[bw_top_species limit="5"]
```

### Show recent detections of a specific species:

```shortcode
[bw_recent_detections limit="5" species="Eastern Bluebird"]
```

### Display detailed observations with scientific names:

```shortcode
[bw_observations limit="10" show_scientific="true"]
```

### Show monthly statistics:

```shortcode
[bw_period_stats period="month"]
