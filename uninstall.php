<?php
if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

// Delete options
delete_option('mw_sliding_panel_position');
delete_option('mw_sliding_panel_tab_text');
delete_option('mw_sliding_panel_width');
delete_option('mw_sliding_panel_tab_color');

// Remove widget area
if (is_active_sidebar('mw-sliding-panel')) {
    unregister_sidebar('mw-sliding-panel');
}

// Clean up transients
$transients = [
    'mw_sliding_panel_transient'
];

foreach ($transients as $transient) {
    delete_transient($transient);
}

// Multisite cleanup
if (is_multisite()) {
    $sites = get_sites(['fields' => 'ids']);
    
    foreach ($sites as $site_id) {
        switch_to_blog($site_id);
        
        delete_option('mw_sliding_panel_position');
        delete_option('mw_sliding_panel_tab_text');
        delete_option('mw_sliding_panel_width');
        delete_option('mw_sliding_panel_tab_color');
        
        if (is_active_sidebar('mw-sliding-panel')) {
            unregister_sidebar('mw-sliding-panel');
        }
        
        restore_current_blog();
    }
    
    delete_site_option('mw_sliding_panel_network_settings');
}