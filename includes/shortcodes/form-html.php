<?php
$terms = get_terms(['taxonomy' => 'ebook-category', 'hide_empty' => false]);
?>

<form method="post" enctype="multipart/form-data" class="space-y-6" id="ebook-submission-form">
    <?php wp_nonce_field('submit_ebook', 'ebook_submission_nonce'); ?>

    <div>
        <label class="block font-medium mb-1">Title</label>
        <input type="text" name="ebook_title" required class="w-full border px-3 py-2 rounded-md" />
    </div>

    <div>
        <label class="block font-medium mb-1">Excerpt</label>
        <textarea name="ebook_excerpt" required class="w-full border px-3 py-2 rounded-md"></textarea>
    </div>

    <div>
        <label class="block font-medium mb-1">Cover (jpg, png, pdf)</label>
        <input type="file" name="ebook_cover" accept=".jpg,.jpeg,.png,.pdf" id="ebook_cover_input" />
        <div id="cover_preview" class="mt-2"></div>
    </div>

    <div>
        <label class="block font-medium mb-1">Price</label>
        <input type="number" step="0.01" name="ebook_price" required class="border px-3 py-2 rounded-md" />
    </div>

    <div>
        <label class="block font-medium mb-1">Categories</label>
        <select name="ebook_categories[]" multiple id="ebook_categories" class="w-full border px-3 py-2 rounded-md">
            <?php foreach ($terms as $term): ?>
                <option value="<?php echo esc_attr($term->term_id); ?>"><?php echo esc_html($term->name); ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div>
        <label class="block font-medium mb-1">Distribution Method</label>
        <select name="ebook_buy_option" id="buy_option" required class="border px-3 py-2 rounded-md">
            <option value="upload">Upload eBook</option>
            <option value="url">Buy Button URL</option>
        </select>
    </div>

    <div id="file_upload_group">
        <label class="block font-medium mb-1">Upload eBook (epub, doc, docx, pdf)</label>
        <input type="file" name="ebook_file" accept=".epub,.doc,.docx,.pdf" />
    </div>

    <div id="url_input_group" style="display: none;">
        <label class="block font-medium mb-1">Buy Button URL</label>
        <input type="url" name="ebook_url" class="w-full border px-3 py-2 rounded-md" />
    </div>

    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
        Submit eBook
    </button>
</form>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const select = document.getElementById('buy_option');
    const fileGroup = document.getElementById('file_upload_group');
    const urlGroup = document.getElementById('url_input_group');
    const coverInput = document.getElementById('ebook_cover_input');
    const preview = document.getElementById('cover_preview');

    function toggleFields() {
        if (select.value === 'url') {
            urlGroup.style.display = 'block';
            fileGroup.style.display = 'none';
        } else {
            fileGroup.style.display = 'block';
            urlGroup.style.display = 'none';
        }
    }

    select.addEventListener('change', toggleFields);
    toggleFields();

    coverInput.addEventListener('change', function () {
        const file = this.files[0];
        preview.innerHTML = '';
        if (file && file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = e => {
                preview.innerHTML = '<img src=\"' + e.target.result + '\" class=\"ebook-cover-image-preview mt-2 max-w-xs rounded-md border\" />';
            };
            reader.readAsDataURL(file);
        }
    });

    if (window.jQuery) {
        jQuery('#ebook_categories').select2({
            tags: true,
            width: '100%',
            placeholder: 'Select or add categories',
            allowClear: true
        });
    }
});
</script>
