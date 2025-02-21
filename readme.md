# BirdWeather WordPress Plugin

**Version:** 2.0.0  
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
![Screenshot 2025-02-21 082313](https://github.com/user-attachments/assets/12bfc676-645c-49aa-9ed2-b7000ba5467c)


Displays today's overall detection statistics for your station.

* Output: Shows total number of detections and unique species for the day

### Bird Observations

```shortcode
[bw_observations limit="5" show_scientific="true" show_thumbnails="true"]
```
![Screenshot 2025-02-21 082325](https://github.com/user-attachments/assets/42575482-7d2c-43a8-aff9-7a0f5e5d247a)


Shows detailed bird observations with optional scientific names and thumbnails.

* `limit`: Number of observations to display (default: 7)
* `show_scientific`: Whether to show scientific names (true/false, default: false)
* `show_thumbnails`: Whether to show bird thumbnails (true/false, default: false)

### Top Species

```shortcode
[bw_top_species period="day" limit="10" sort="top" order="desc"]
```

Displays the most frequently detected species.

* `period`: Time period to show stats for (day/week/month, default: day)
* `limit`: Number of species to show (default: 10)
* `sort`: How to sort results (top/common_name/scientific_name, default: top)
* `order`: Sort direction (asc/desc, default: desc)

### Recent Detections

```shortcode
[bw_recent_detections limit="10" species=""]
```

Shows the most recent bird detections.

* `limit`: Number of detections to show (default: 10)
* `species`: Filter by species name (optional)

### Period Statistics

```shortcode
[bw_period_stats period="day"]
```
![Screenshot 2025-02-21 083422](https://github.com/user-attachments/assets/55e9e74e-1869-4883-b342-b49ff2489e0e)

Shows detection statistics for a specific time period.

* `period`: Time period to show stats for (day/week/month, default: day)

### BirdWeather Species Probability Table

```shortcode
[bw_species_table period="week" limit="10" show_thumbnails="true"]
```
![Screenshot 2025-02-21 082521](https://github.com/user-attachments/assets/67f1d859-fc74-41d5-8139-1411564a34e3)

Displays a table of bird species detections and probabilities from your BirdWeather station.

Parameters:
- `period`: Time frame for data (day/week/month/all, default: week)
- `limit`: Number of species to display (default: 10)
- `show_thumbnails`: Whether to show bird thumbnails (true/false, default: false)

Example output includes:
- Species name
- Number of detections
- Probability percentage

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
