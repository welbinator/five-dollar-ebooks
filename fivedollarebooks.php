<?php
/**
 * Plugin Name: Five Dollar eBooks
 * Description: Allows eBook submissions and browsing for downloadable or purchasable eBooks.
 * Version: 1.0.0
 * Author: James Welbes
 */

require_once plugin_dir_path(__FILE__) . 'includes/post-types.php';
require_once plugin_dir_path(__FILE__) . 'includes/enqueue.php';
require_once plugin_dir_path(__FILE__) . 'includes/mime-types.php';
require_once plugin_dir_path(__FILE__) . 'includes/shortcodes/submission-form.php';
require_once plugin_dir_path(__FILE__) . 'includes/shortcodes/search-grid.php';
