<?php
add_action('init', function () {
    register_post_type('ebook', [
        'labels' => [
            'name' => 'eBooks',
            'singular_name' => 'eBook',
        ],
        'public' => true,
        'has_archive' => true,
        'show_in_rest' => true,
        'supports' => ['title', 'editor', 'excerpt', 'thumbnail', 'author'],
    ]);

    register_taxonomy('ebook-category', 'ebook', [
        'label' => 'eBook Categories',
        'public' => true,
        'hierarchical' => true,
        'show_in_rest' => true,
    ]);
});
