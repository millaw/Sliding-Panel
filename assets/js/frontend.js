jQuery(function($) {
    const panel = $('#mw-sliding-panel');
    if (!panel.length) return;
    
    const tab = panel.find('.mw-panel-tab');
    const closeBtn = panel.find('.mw-panel-close');
    const storageKey = 'mw_sliding_panel_state';
    
    // Initialize panel state
    const savedState = localStorage.getItem(storageKey);
    if (savedState === 'open') {
        panel.addClass('open');
    }
    
    // Toggle panel on tab click
    tab.on('click', function(e) {
        e.preventDefault();
        panel.toggleClass('open');
        localStorage.setItem(storageKey, panel.hasClass('open') ? 'open' : 'closed');
    });
    
    // Close panel on button click
    closeBtn.on('click', function(e) {
        e.preventDefault();
        panel.removeClass('open');
        localStorage.setItem(storageKey, 'closed');
    });
    
    // Close panel when clicking outside
    $(document).on('click', function(e) {
        if (!panel.is(e.target) && 
            panel.has(e.target).length === 0 && 
            !tab.is(e.target) && 
            panel.hasClass('open')) {
            panel.removeClass('open');
            localStorage.setItem(storageKey, 'closed');
        }
    });
    
    // Close with ESC key
    $(document).on('keyup', function(e) {
        if (e.key === 'Escape' && panel.hasClass('open')) {
            panel.removeClass('open');
            localStorage.setItem(storageKey, 'closed');
        }
    });
});