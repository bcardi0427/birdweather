<h1>BirdWeather WordPress Plugin</h1>

<p>Version: 1.0.0<br>
Author: Gerald Haygood</p>

<p>A WordPress plugin for displaying BirdWeather station data using shortcodes.</p>

<h2>Installation</h2>

<ol>
    <li>Press the green code button and download the zip, install that like any other plugin
    <li>Activate the plugin through the 'Plugins' menu in WordPress</li>
    <li>Go to Settings > BirdWeather to:
        <ul>
            <li>Configure your station token</li>
            <li>Enable/disable debug mode (helps with troubleshooting)</li>
        </ul>
    </li>
</ol>

<h2>Shortcodes</h2>

<h3>Station Stats</h3>
<pre><code>[bw_location]</code></pre>
<p>Displays today's overall detection statistics for your station.</p>
<ul>
    <li>Output: Shows total number of detections and unique species for the day</li>
</ul>

<h3>Bird Observations</h3>
<pre><code>[bw_observations limit="5" show_scientific="true" show_thumbnails="true"]</code></pre>
<p>Shows detailed bird observations with optional scientific names and thumbnails.</p>
<ul>
    <li><code>limit</code>: Number of observations to display (default: 7)</li>
    <li><code>show_scientific</code>: Whether to show scientific names (true/false, default: false)</li>
    <li><code>show_thumbnails</code>: Whether to show bird thumbnails (true/false, default: false)</li>
</ul>

<h3>Top Species</h3>
<pre><code>[bw_top_species period="day" limit="10" sort="top" order="desc"]</code></pre>
<p>Displays the most frequently detected species.</p>
<ul>
    <li><code>period</code>: Time period to show stats for (day/week/month, default: day)</li>
    <li><code>limit</code>: Number of species to show (default: 10)</li>
    <li><code>sort</code>: How to sort results (top/common_name/scientific_name, default: top)</li>
    <li><code>order</code>: Sort direction (asc/desc, default: desc)</li>
</ul>

<h3>Recent Detections</h3>
<pre><code>[bw_recent_detections limit="10" species=""]</code></pre>
<p>Shows the most recent bird detections.</p>
<ul>
    <li><code>limit</code>: Number of detections to show (default: 10)</li>
    <li><code>species</code>: Filter by species name (optional)</li>
</ul>

<h3>Period Statistics</h3>
<pre><code>[bw_period_stats period="day"]</code></pre>
<p>Shows detection statistics for a specific time period.</p>
<ul>
    <li><code>period</code>: Time period to show stats for (day/week/month, default: day)</li>
</ul>

<h2>Examples</h2>

<h3>Display today's top 5 species:</h3>
<pre><code>[bw_top_species limit="5"]</code></pre>

<h3>Show recent detections of a specific species:</h3>
<pre><code>[bw_recent_detections limit="5" species="Eastern Bluebird"]</code></pre>

<h3>Display detailed observations with scientific names:</h3>
<pre><code>[bw_observations limit="10" show_scientific="true"]</code></pre>

<h3>Show monthly statistics:</h3>
<pre><code>[bw_period_stats period="month"]</code></pre>

<h4>BirdWeather is at <a="https://www.birdweather.com"/a>https://www.birdweather.com<h4>
