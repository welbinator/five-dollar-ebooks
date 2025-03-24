<?php
function ebook_search_shortcode() {
    ob_start();
    include __DIR__ . '/search-ui.php';
    return ob_get_clean();
}
add_shortcode('ebook_search', 'ebook_search_shortcode');

function ebook_search_ajax() {
    $search = sanitize_text_field($_POST['search']);
    $category = sanitize_text_field($_POST['category']);

    $args = [
        'post_type' => 'ebook',
        'post_status' => 'publish',
        's' => $search,
        'posts_per_page' => -1,
        'tax_query' => [],
    ];

    if (!empty($category)) {
        $args['tax_query'][] = [
            'taxonomy' => 'ebook-category',
            'field' => 'slug',
            'terms' => $category,
        ];
    }

    $query = new WP_Query($args);

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            include __DIR__ . '/search-item.php';
        }
    } else {
        echo 'no_results';
    }

    wp_die();
}
add_action('wp_ajax_ebook_search', 'ebook_search_ajax');
add_action('wp_ajax_nopriv_ebook_search', 'ebook_search_ajax');
