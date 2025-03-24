<?php
$terms = get_terms(['taxonomy' => 'ebook-category', 'hide_empty' => true]);
?>

<div id="ebook-search-app" class="space-y-6">
    <div class="relative">
        <svg xmlns="http://www.w3.org/2000/svg"
             class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 h-4 w-4"
             fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
        </svg>
        <input
            type="search"
            id="ebook-search-input"
            placeholder="Search by title, author, or description..."
            class="w-full !pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-700 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-800 dark:text-white"
        />
    </div>

    <div id="ebook-category-filter" class="w-full overflow-auto">
        <div class="flex space-x-2 border-b border-gray-200 dark:border-gray-700">
            <button data-category="" class="ebook-cat-btn !bg-transparent px-4 py-2 text-sm font-medium whitespace-nowrap !text-blue-600 text-gray-600 !border-b-2 !border-blue-500  !dark:text-blue-400">
                All
            </button>
            <?php foreach ($terms as $term): ?>
                <button data-category="<?php echo esc_attr($term->slug); ?>"
                        class="ebook-cat-btn !bg-transparent px-4 py-2 text-sm font-medium whitespace-nowrap text-gray-600 !dark:text-gray-300 hover:text-gray-900 dark:hover:text-white">
                    <?php echo esc_html($term->name); ?>
                </button>
            <?php endforeach; ?>
        </div>
    </div>

    <div id="ebook-grid" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        <!-- AJAX results go here -->
    </div>

    <div id="ebook-no-results" class="col-span-full text-center py-12 hidden">
        <p class="text-gray-500 dark:text-gray-400">No books found matching your search criteria.</p>
    </div>
</div>

<script>
if (!window.ebookSearchInitialized) {
    window.ebookSearchInitialized = true;

    document.addEventListener('DOMContentLoaded', function () {
        const input = document.getElementById('ebook-search-input');
        const buttons = document.querySelectorAll('.ebook-cat-btn');
        const resultsContainer = document.getElementById('ebook-grid');
        const noResults = document.getElementById('ebook-no-results');

        let currentCategory = '';
        let searchTerm = '';
        let debounceTimer;

        const fetchResults = () => {
            const formData = new FormData();
            formData.append('action', 'ebook_search');
            formData.append('search', searchTerm);
            formData.append('category', currentCategory);

            fetch(ajaxurl, {
                method: 'POST',
                body: formData
            })
            .then(res => res.text())
            .then(html => {
                if (html.trim() === 'no_results') {
                    resultsContainer.innerHTML = '';
                    noResults.classList.remove('hidden');
                } else {
                    resultsContainer.innerHTML = html;
                    noResults.classList.add('hidden');
                }
            });
        };

        input.addEventListener('input', (e) => {
            searchTerm = e.target.value;
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(fetchResults, 300);
        });

        buttons.forEach(btn => {
            btn.addEventListener('click', () => {
                buttons.forEach(b => b.classList.remove('!border-b-2', '!border-blue-500', '!text-blue-600', '!dark:text-blue-400'));
                btn.classList.add('!border-b-2', '!border-blue-500', '!text-blue-600', '!dark:text-blue-400');

                currentCategory = btn.dataset.category || '';
                fetchResults();
            });
        });

        fetchResults();
    });
}
</script>
