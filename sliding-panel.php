<?php
/**
 * Plugin Name: Sliding Panel
 * Plugin URI:  https://github.com/millaw/sliding-panel
 * Description: A lightweight, customizable sliding panel for WordPress to replace ClientEngage UberPanel.
 * Version: 1.0
 * Author: Your Name
 * Author URI: https://github.com/millaw
 * License: GPL2
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Enqueue styles and scripts
function sp_enqueue_assets() {
    wp_enqueue_style('sp-style', plugin_dir_url(__FILE__) . 'assets/panel-style.css');
    wp_enqueue_script('sp-script', plugin_dir_url(__FILE__) . 'assets/panel-script.js', array('jquery'), null, true);
}
add_action('wp_enqueue_scripts', 'sp_enqueue_assets');

// Include panel content
include_once plugin_dir_path(__FILE__) . 'includes/panel-content.php';

// Include admin settings
if (is_admin()) {
    include_once plugin_dir_path(__FILE__) . 'includes/panel-settings.php';
}
