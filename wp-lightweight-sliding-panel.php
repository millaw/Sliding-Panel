<?php
/*
Plugin Name: MW Sliding Panel
Plugin URI: https://github.com/millaw/mw-sliding-panel
Description: A fully functional lightweight sliding panel for WordPress.
Version: 1.0.3
Author: Milla Wynn
Author URI: https://github.com/millaw
License: GPL2
Text Domain: mw-sliding-panel
*/

defined('ABSPATH') or die('Direct access not allowed');

class MW_Sliding_Panel {

    private static $instance;

    public static function get_instance() {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        // Frontend hooks
        add_action('wp_enqueue_scripts', [$this, 'enqueue_assets']);
        add_action('wp_footer', [$this, 'render_panel']);
        
        // Admin hooks
        add_action('admin_init', [$this, 'register_settings']);
        add_action('admin_menu', [$this, 'add_admin_page']);
        add_filter('plugin_action_links_' . plugin_basename(__FILE__), [$this, 'add_settings_link']);
        
        // Widget area
        add_action('widgets_init', [$this, 'register_widget_area']);
    }

    public function enqueue_assets() {
        // Frontend CSS
        wp_enqueue_style(
            'mw-sliding-panel-frontend',
            plugins_url('assets/css/frontend.css', __FILE__),
            [],
            filemtime(plugin_dir_path(__FILE__) . 'assets/css/frontend.css')
        );

        // Frontend JS
        wp_enqueue_script(
            'mw-sliding-panel-frontend',
            plugins_url('assets/js/frontend.js', __FILE__),
            ['jquery'],
            filemtime(plugin_dir_path(__FILE__) . 'assets/js/frontend.js'),
            true
        );
    }

    public function render_panel() {
        $position = get_option('mw_sliding_panel_position', 'right');
        $tab_text = get_option('mw_sliding_panel_tab_text', 'Contact');
        $panel_width = get_option('mw_sliding_panel_width', 400);
        $tab_color = get_option('mw_sliding_panel_tab_color', '#2c3e50');
        ?>
        <div id="mw-sliding-panel" class="mw-sliding-panel mw-position-<?php echo esc_attr($position); ?>" 
             style="--panel-width: <?php echo absint($panel_width); ?>px; --tab-color: <?php echo esc_attr($tab_color); ?>;">
            <div class="mw-panel-content">
                <button class="mw-panel-close" aria-label="<?php esc_attr_e('Close panel', 'mw-sliding-panel'); ?>">×</button>
                <?php dynamic_sidebar('mw-sliding-panel'); ?>
            </div>
            <div class="mw-panel-tab">
                <span><?php echo esc_html($tab_text); ?></span>
            </div>
        </div>
        <?php
    }

    public function register_widget_area() {
        register_sidebar([
            'name'          => __('Sliding Panel Content', 'mw-sliding-panel'),
            'id'            => 'mw-sliding-panel',
            'description'   => __('Add widgets here to appear in the sliding panel', 'mw-sliding-panel'),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h3 class="widget-title">',
            'after_title'   => '</h3>'
        ]);
    }

    public function register_settings() {
        register_setting('mw_sliding_panel_settings', 'mw_sliding_panel_position');
        register_setting('mw_sliding_panel_settings', 'mw_sliding_panel_tab_text');
        register_setting('mw_sliding_panel_settings', 'mw_sliding_panel_width');
        register_setting('mw_sliding_panel_settings', 'mw_sliding_panel_tab_color');
    }

    public function add_admin_page() {
        add_options_page(
            __('Sliding Panel Settings', 'mw-sliding-panel'),
            __('Sliding Panel', 'mw-sliding-panel'),
            'manage_options',
            'mw-sliding-panel',
            [$this, 'render_admin_page']
        );
    }

    public function render_admin_page() {
        wp_enqueue_style('wp-color-picker');
        wp_enqueue_script(
            'mw-sliding-panel-admin',
            plugins_url('assets/js/admin.js', __FILE__),
            ['wp-color-picker'],
            filemtime(plugin_dir_path(__FILE__) . 'assets/js/admin.js'),
            true
        );
        ?>
        <div class="wrap">
            <h1><?php esc_html_e('Sliding Panel Settings', 'mw-sliding-panel'); ?></h1>
            <form method="post" action="options.php">
                <?php settings_fields('mw_sliding_panel_settings'); ?>
                <table class="form-table">
                    <tr>
                        <th scope="row"><?php esc_html_e('Panel Position', 'mw-sliding-panel'); ?></th>
                        <td>
                            <select name="mw_sliding_panel_position">
                                <option value="right" <?php selected(get_option('mw_sliding_panel_position'), 'right'); ?>>
                                    <?php esc_html_e('Right', 'mw-sliding-panel'); ?>
                                </option>
                                <option value="left" <?php selected(get_option('mw_sliding_panel_position'), 'left'); ?>>
                                    <?php esc_html_e('Left', 'mw-sliding-panel'); ?>
                                </option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php esc_html_e('Tab Text', 'mw-sliding-panel'); ?></th>
                        <td>
                            <input type="text" name="mw_sliding_panel_tab_text" 
                                   value="<?php echo esc_attr(get_option('mw_sliding_panel_tab_text', 'Contact')); ?>">
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php esc_html_e('Panel Width (px)', 'mw-sliding-panel'); ?></th>
                        <td>
                            <input type="number" name="mw_sliding_panel_width" min="200" max="800"
                                   value="<?php echo absint(get_option('mw_sliding_panel_width', 400)); ?>">
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php esc_html_e('Tab Color', 'mw-sliding-panel'); ?></th>
                        <td>
                            <input type="text" name="mw_sliding_panel_tab_color" class="color-picker"
                                   value="<?php echo esc_attr(get_option('mw_sliding_panel_tab_color', '#2c3e50')); ?>">
                        </td>
                    </tr>
                </table>
                <?php submit_button(); ?>
            </form>
        </div>
        <?php
    }

    public function add_settings_link($links) {
        $settings_link = sprintf(
            '<a href="%s">%s</a>',
            admin_url('options-general.php?page=mw-sliding-panel'),
            __('Settings', 'mw-sliding-panel')
        );
        array_unshift($links, $settings_link);
        return $links;
    }
}

// Initialize the plugin
MW_Sliding_Panel::get_instance();

// Uninstallation handler
register_uninstall_hook(__FILE__, 'mw_sliding_panel_uninstall');
function mw_sliding_panel_uninstall() {
    delete_option('mw_sliding_panel_position');
    delete_option('mw_sliding_panel_tab_text');
    delete_option('mw_sliding_panel_width');
    delete_option('mw_sliding_panel_tab_color');
    
    if (is_active_sidebar('mw-sliding-panel')) {
        unregister_sidebar('mw-sliding-panel');
    }
}