<?php
function sp_add_admin_menu() {
    add_options_page('Sliding Panel Settings', 'Sliding Panel', 'manage_options', 'sliding-panel', 'sp_settings_page');
}
add_action('admin_menu', 'sp_add_admin_menu');

function sp_settings_page() {
    ?>
    <div class="wrap">
        <h1>Sliding Panel Settings</h1>
        <form method="post" action="options.php">
            <?php
            settings_fields('sp_settings_group');
            do_settings_sections('sp_settings_group');
            ?>
            <textarea name="sp_panel_content" rows="5" style="width:100%;"><?php echo esc_textarea(get_option('sp_panel_content', '<h3>Welcome</h3><p>Customize this in the settings.</p>')); ?></textarea>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}

function sp_register_settings() {
    register_setting('sp_settings_group', 'sp_panel_content');
}
add_action('admin_init', 'sp_register_settings');
