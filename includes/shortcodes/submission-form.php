<?php
function ebook_submission_form_shortcode() {
    ob_start();

    if (!is_user_logged_in()) {
        echo '<div class="mb-4 text-red-600 font-medium">Please login to submit your eBook.</div>';
        wp_login_form();
        return ob_get_clean();
    }

    if (!function_exists('media_handle_upload')) {
        require_once ABSPATH . 'wp-admin/includes/image.php';
        require_once ABSPATH . 'wp-admin/includes/file.php';
        require_once ABSPATH . 'wp-admin/includes/media.php';
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ebook_submission_nonce']) && wp_verify_nonce($_POST['ebook_submission_nonce'], 'submit_ebook')) {
        $title       = sanitize_text_field($_POST['ebook_title']);
        $excerpt     = sanitize_textarea_field($_POST['ebook_excerpt']);
        $price       = floatval($_POST['ebook_price']);
        $buy_option  = $_POST['ebook_buy_option'];
        $buy_url     = esc_url_raw($_POST['ebook_url']);
        $submitted_categories = $_POST['ebook_categories'] ?? [];
        $final_categories = [];

        foreach ($submitted_categories as $cat) {
            $cat = sanitize_text_field($cat);
            if (is_numeric($cat)) {
                $final_categories[] = (int) $cat;
            } else {
                $term = term_exists($cat, 'ebook-category');
                if ($term) {
                    $final_categories[] = (int) $term['term_id'];
                } else {
                    $new_term = wp_insert_term($cat, 'ebook-category');
                    if (!is_wp_error($new_term)) {
                        $final_categories[] = (int) $new_term['term_id'];
                    }
                }
            }
        }

        $file_id = null;
        $cover_id = null;

        $post_id = wp_insert_post([
            'post_type'    => 'ebook',
            'post_title'   => $title,
            'post_excerpt' => $excerpt,
            'post_status'  => 'pending',
        ]);

        if ($post_id && !is_wp_error($post_id)) {
            wp_set_post_terms($post_id, $final_categories, 'ebook-category');
            update_post_meta($post_id, 'ebook_price', $price);

            if ($buy_option === 'url') {
                update_post_meta($post_id, 'ebook_url', $buy_url);
            } elseif ($buy_option === 'upload' && !empty($_FILES['ebook_file']['name'])) {
                $file_id = media_handle_upload('ebook_file', $post_id);
                if (!is_wp_error($file_id)) {
                    update_post_meta($post_id, 'ebook_file', $file_id);
                }
            }

            if (!empty($_FILES['ebook_cover']['name'])) {
                $cover_id = media_handle_upload('ebook_cover', $post_id);
                if (!is_wp_error($cover_id)) {
                    set_post_thumbnail($post_id, $cover_id);
                }
            }

            echo '<div class="text-green-600 font-medium mb-4">eBook submitted successfully!</div>';
        } else {
            echo '<div class="text-red-600 font-medium mb-4">Error submitting eBook. Please try again.</div>';
        }
    }

    include __DIR__ . '/form-html.php';

    return ob_get_clean();
}
add_shortcode('ebook_submission_form', 'ebook_submission_form_shortcode');
