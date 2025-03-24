<?php
add_action('wp_enqueue_scripts', function () {
    // Enqueue Select2
    wp_enqueue_script('select2-js', 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js', ['jquery'], null, true);
    wp_enqueue_style('select2-css', 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css');

    // Enqueue jQuery + localize AJAX
    wp_enqueue_script('jquery');
    wp_localize_script('jquery', 'ajaxurl', admin_url('admin-ajax.php'));

    // Enqueue custom output CSS
    wp_enqueue_style('five-dollar-ebooks-css', plugins_url('../src/output.css', __FILE__));
});
