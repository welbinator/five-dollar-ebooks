<?php
$price = get_post_meta(get_the_ID(), 'ebook_price', true);
$url = get_post_meta(get_the_ID(), 'ebook_url', true);
$ebook_file_id = get_post_meta(get_the_ID(), 'ebook_file', true);
$ebook_file = wp_get_attachment_url($ebook_file_id);
$categories = get_the_terms(get_the_ID(), 'ebook-category');
$author_id = get_the_author_meta('ID');
$first_name = get_the_author_meta('first_name', $author_id);
$last_name = get_the_author_meta('last_name', $author_id);
$display_name = trim($first_name . ' ' . $last_name);
if (empty($first_name) || empty($last_name)) {
    $display_name = get_the_author_meta('user_login', $author_id);
}
?>
<div class="h-full flex flex-col overflow-hidden rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 shadow-sm">
    <div class="p-4 pb-0 flex-shrink-0">
    <a href="<?php the_permalink(); ?>">

            <div class="relative w-full aspect-[2/3] mx-auto mb-4">
                <?php if (has_post_thumbnail()): ?>
                    <?php the_post_thumbnail('medium', ['class' => 'object-cover rounded-md w-full h-full']); ?>
                <?php else: ?>
                    <img src="/placeholder.svg" alt="Cover Placeholder" class="object-cover rounded-md w-full h-full" />
                <?php endif; ?>
            </div>
        </a>
        <h3 class="font-semibold text-lg line-clamp-2 text-gray-900 dark:text-white hover:underline">
            <?php the_title(); ?>
        </h3>
        <p class="text-sm text-gray-600 dark:text-gray-300">by <strong><?php echo esc_html($display_name); ?></strong></p>
    </div>
    <div class="p-4 pt-2 flex-grow">
        <p class="text-sm line-clamp-3 mb-2 text-gray-700 dark:text-gray-300"><?php echo get_the_excerpt(); ?></p>
        <div class="flex flex-wrap gap-1 mt-2">
            <?php if (!empty($categories)) {
                foreach ($categories as $cat) {
                    echo '<span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200">' . esc_html($cat->name) . '</span>';
                }
            } ?>
        </div>
    </div>
    <div class="p-4 flex justify-between items-center border-t border-gray-100 dark:border-gray-700">
        <span class="font-bold text-lg text-gray-900 dark:text-white">$<?php echo esc_html(number_format((float)$price, 2)); ?></span>
        <?php if (!empty($url)): ?>
            <a href="<?php echo esc_url($url); ?>" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1 px-3 py-1.5 !bg-blue-600 !hover:bg-blue-700 !text-white text-sm font-medium rounded-md transition-colors">
                Buy
            </a>
        <?php elseif (!empty($ebook_file)): ?>
            <a href="<?php echo esc_url($ebook_file); ?>" download class="inline-flex items-center gap-1 px-3 py-1.5 !bg-green-600 !hover:bg-green-700 !text-white text-sm font-medium rounded-md transition-colors">
                Download
            </a>
        <?php endif; ?>
    </div>
</div>
