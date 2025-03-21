<?php
function sp_display_panel() {
    $panel_content = get_option('sp_panel_content', '<h3>Welcome</h3><p>Customize this in the settings.</p>');
    ?>
    <div id="sliding-panel">
        <button id="close-panel">&times;</button>
        <div id="panel-content">
            <?php echo wp_kses_post($panel_content); ?>
        </div>
    </div>
    <button id="open-panel">☰ Open Panel</button>
    <?php
}
add_action('wp_footer', 'sp_display_panel');
