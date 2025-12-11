<?php
// ---------- Mock marketplace data (replace with DB later) ----------
$categories = ['All', 'Fashion', 'Beauty', 'Electronics', 'Home & Living'];

$products = [
    [
        'id' => 1,
        'name' => 'Ankara Panel Hoodie',
        'brand' => 'Lagos Streetwear Co.',
        'category' => 'Fashion',
        'price' => 18500,
        'currency' => '₦',
        'badge' => 'Trending',
    ],
    [
        'id' => 2,
        'name' => 'Shea Butter Glow Kit',
        'brand' => 'Abuja Beauty Lab',
        'category' => 'Beauty',
        'price' => 9900,
        'currency' => '₦',
        'badge' => 'Bestseller',
    ],
    [
        'id' => 3,
        'name' => 'Wireless Earbuds Pro',
        'brand' => 'Naija Tech Hub',
        'category' => 'Electronics',
        'price' => 14200,
        'currency' => '₦',
        'badge' => 'New',
    ],
    [
        'id' => 4,
        'name' => 'Handwoven Throw Blanket',
        'brand' => 'Abeokuta Crafts',
        'category' => 'Home & Living',
        'price' => 11000,
        'currency' => '₦',
        'badge' => 'Handmade',
    ],
    [
        'id' => 5,
        'name' => 'Naija Drip Tee',
        'brand' => 'Lagos Streetwear Co.',
        'category' => 'Fashion',
        'price' => 7500,
        'currency' => '₦',
        'badge' => 'Hot',
    ],
    [
        'id' => 6,
        'name' => 'Coconut Hair Nourish Oil',
        'brand' => 'Abuja Beauty Lab',
        'category' => 'Beauty',
        'price' => 6500,
        'currency' => '₦',
        'badge' => 'Natural',
    ],
    [
        'id' => 7,
        'name' => 'Smart Home Lamp',
        'brand' => 'Naija Tech Hub',
        'category' => 'Electronics',
        'price' => 18900,
        'currency' => '₦',
        'badge' => 'Smart',
    ],
    [
        'id' => 8,
        'name' => 'Bamboo Serving Set',
        'brand' => 'Home & Living NG',
        'category' => 'Home & Living',
        'price' => 8800,
        'currency' => '₦',
        'badge' => 'Eco',
    ],
];

$currency = '₦';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Marketplace | LocalTrade</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        :root {
            --lt-orange: #F36A1D;
            --lt-black: #0D0D0D;
        }
    </style>
</head>

<body class="bg-[#0D0D0D] text-white">
    <div class="min-h-screen flex flex-col">

        <!-- HEADER -->
        <header class="border-b border-white/10 bg-black/60 backdrop-blur">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
                <a href="index.php" class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center"
                        style="background-color: var(--lt-orange);">
                        <div class="w-4 h-3 border-2 border-white border-b-0 rounded-sm relative">
                            <span class="w-1 h-1 bg-white rounded-full absolute -bottom-1 left-0.5"></span>
                            <span class="w-1 h-1 bg-white rounded-full absolute -bottom-1 right-0.5"></span>
                        </div>
                    </div>
                    <span class="font-semibold tracking-tight text-lg">LocalTrade</span>
                </a>
                <nav class="hidden sm:flex items-center gap-6 text-xs sm:text-sm">
                    <a href="marketplace.php" class="text-orange-400">Marketplace</a>
                    <a href="store.php" class="hover:text-orange-400">Stores</a>
                    <a href="#" class="hover:text-orange-400">Sell</a>
                </nav>
                <a href="cart.php" class="text-xs sm:text-sm text-gray-300 hover:text-orange-400">
                    🛒 Cart
                </a>
            </div>
        </header>

        <!-- MAIN -->
        <main class="flex-1 py-6 sm:py-10">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

                <!-- Title + search -->
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-5 sm:mb-7">
                    <div>
                        <h1 class="text-xl sm:text-2xl font-semibold mb-1">
                            Marketplace
                        </h1>
                        <p class="text-xs sm:text-sm text-gray-300">
                            Discover products from verified Nigerian brands across fashion, beauty, electronics and
                            more.
                        </p>
                    </div>
                    <div class="w-full md:w-80">
                        <div
                            class="bg-[#111111] border border-white/15 rounded-full px-3 py-1.5 flex items-center gap-2">
                            <span class="text-gray-500 text-sm">🔍</span>
                            <input id="searchInput" type="text" placeholder="Search products or brands..."
                                class="flex-1 bg-transparent border-0 text-xs sm:text-sm text-white placeholder-gray-500 focus:outline-none" />
                        </div>
                    </div>
                </div>

                <!-- Filters + content -->
                <div class="grid gap-6 lg:grid-cols-[minmax(0,260px)_minmax(0,1fr)]">

                    <!-- FILTERS -->
                    <aside class="bg-[#111111] border border-white/10 rounded-2xl p-4 sm:p-5 h-max">
                        <div class="flex items-center justify-between mb-3">
                            <h2 class="text-sm font-semibold">Filters</h2>
                            <button id="clearFilters" class="text-[11px] text-gray-400 hover:text-orange-400">
                                Clear all
                            </button>
                        </div>

                        <!-- Category -->
                        <div class="mb-4">
                            <p class="text-xs text-gray-400 mb-2">Category</p>
                            <div class="flex flex-wrap gap-2">
                                <?php foreach ($categories as $cat): ?>
                                    <button
                                        class="cat-chip text-[11px] px-3 py-1.5 rounded-full border border-white/15 bg-transparent hover:border-orange-400"
                                        data-category="<?php echo htmlspecialchars($cat); ?>">
                                        <?php echo htmlspecialchars($cat); ?>
                                    </button>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <!-- Price range -->
                        <div class="mb-4">
                            <p class="text-xs text-gray-400 mb-2">Price range (₦)</p>
                            <div class="flex gap-2 items-center text-xs">
                                <input id="minPrice" type="number" placeholder="Min"
                                    class="w-full bg-[#0B0B0B] border border-white/15 rounded-xl px-2 py-1.5 text-xs focus:outline-none" />
                                <span class="text-gray-500">–</span>
                                <input id="maxPrice" type="number" placeholder="Max"
                                    class="w-full bg-[#0B0B0B] border border-white/15 rounded-xl px-2 py-1.5 text-xs focus:outline-none" />
                            </div>
                        </div>

                        <!-- Sort -->
                        <div class="mb-4">
                            <p class="text-xs text-gray-400 mb-2">Sort by</p>
                            <select id="sortSelect"
                                class="w-full bg-[#0B0B0B] border border-white/15 rounded-xl px-2 py-1.5 text-xs focus:outline-none">
                                <option value="featured">Featured</option>
                                <option value="price-asc">Price: Low to High</option>
                                <option value="price-desc">Price: High to Low</option>
                            </select>
                        </div>

                        <!-- Apply -->
                        <button id="applyFilters" class="mt-2 w-full px-4 py-2 rounded-full text-xs font-semibold"
                            style="background-color: var(--lt-orange);">
                            Apply filters
                        </button>

                        <p class="mt-3 text-[11px] text-gray-500">
                            LocalTrade shows results from verified Nigerian sellers only.
                        </p>
                    </aside>

                    <!-- PRODUCT GRID -->
                    <section>
                        <div class="flex items-center justify-between mb-3 text-xs text-gray-400">
                            <p id="resultsCount">
                                <?php echo count($products); ?> products
                            </p>
                            <p>Showing results for all categories</p>
                        </div>

                        <div id="productsGrid" class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4 text-xs">
                            <?php foreach ($products as $p): ?>
                                <a href="product.php?id=<?php echo (int) $p['id']; ?>"
                                    class="product-card bg-[#111111] border border-white/10 hover:border-orange-500/70 rounded-2xl p-3 sm:p-4 flex flex-col gap-2"
                                    data-name="<?php echo htmlspecialchars($p['name']); ?>"
                                    data-brand="<?php echo htmlspecialchars($p['brand']); ?>"
                                    data-category="<?php echo htmlspecialchars($p['category']); ?>"
                                    data-price="<?php echo (int) $p['price']; ?>">
                                    <!-- Image placeholder -->
                                    <div
                                        class="aspect-[4/3] rounded-xl bg-gradient-to-br from-orange-500/60 to-pink-500/60 flex items-center justify-center text-[11px] text-center px-2">
                                        <?php echo htmlspecialchars($p['brand']); ?>
                                    </div>

                                    <!-- Badge -->
                                    <?php if (!empty($p['badge'])): ?>
                                        <span
                                            class="inline-flex w-max items-center px-2 py-0.5 rounded-full bg-white/5 text-[10px] text-orange-300">
                                            <?php echo htmlspecialchars($p['badge']); ?>
                                        </span>
                                    <?php endif; ?>

                                    <!-- Info -->
                                    <p class="text-sm font-semibold line-clamp-2">
                                        <?php echo htmlspecialchars($p['name']); ?>
                                    </p>
                                    <p class="text-[11px] text-gray-400">
                                        <?php echo htmlspecialchars($p['category']); ?>
                                    </p>
                                    <p class="text-sm font-semibold text-orange-400">
                                        <?php echo $currency . number_format($p['price']); ?>
                                    </p>

                                    <button class="mt-auto text-[11px] px-2 py-1 rounded-full bg-white/5">
                                        View product
                                    </button>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </section>
                </div>
            </div>
        </main>

        <!-- FOOTER -->
        <footer class="border-t border-white/10 bg-black mt-6">
            <div
                class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-5 text-xs text-gray-400 flex flex-col sm:flex-row gap-3 sm:items-center sm:justify-between">
                <p>© <span id="year"></span> LocalTrade. All rights reserved.</p>
                <div class="flex gap-4">
                    <a href="#" class="hover:text-orange-400">Privacy</a>
                    <a href="#" class="hover:text-orange-400">Terms</a>
                    <a href="#" class="hover:text-orange-400">Support</a>
                </div>
            </div>
        </footer>
    </div>

    <script>
        // Year
        document.getElementById('year').textContent = new Date().getFullYear();

        const productsGrid = document.getElementById('productsGrid');
        const productCards = Array.from(document.querySelectorAll('.product-card'));
        const searchInput = document.getElementById('searchInput');
        const sortSelect = document.getElementById('sortSelect');
        const minPriceInput = document.getElementById('minPrice');
        const maxPriceInput = document.getElementById('maxPrice');
        const applyFiltersBtn = document.getElementById('applyFilters');
        const clearFiltersBtn = document.getElementById('clearFilters');
        const resultsCount = document.getElementById('resultsCount');
        const catChips = document.querySelectorAll('.cat-chip');

        let activeCategory = 'All';

        // Helpers
        function formatMoney(n) {
            return n.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        }

        function applyFilters() {
            const search = (searchInput.value || '').toLowerCase().trim();
            const sort = sortSelect.value;
            const min = minPriceInput.value ? parseInt(minPriceInput.value, 10) : null;
            const max = maxPriceInput.value ? parseInt(maxPriceInput.value, 10) : null;

            let visible = [];

            productCards.forEach(card => {
                const name = card.dataset.name.toLowerCase();
                const brand = card.dataset.brand.toLowerCase();
                const cat = card.dataset.category;
                const price = parseInt(card.dataset.price, 10);

                // Category filter
                if (activeCategory !== 'All' && cat !== activeCategory) {
                    card.classList.add('hidden');
                    return;
                }

                // Search filter
                if (search && !name.includes(search) && !brand.includes(search)) {
                    card.classList.add('hidden');
                    return;
                }

                // Price range filter
                if (min !== null && price < min) {
                    card.classList.add('hidden');
                    return;
                }
                if (max !== null && price > max) {
                    card.classList.add('hidden');
                    return;
                }

                card.classList.remove('hidden');
                visible.push(card);
            });

            // Sort visible
            visible.sort((a, b) => {
                const priceA = parseInt(a.dataset.price, 10);
                const priceB = parseInt(b.dataset.price, 10);

                if (sort === 'price-asc') return priceA - priceB;
                if (sort === 'price-desc') return priceB - priceA;
                return 0; // featured → keep original
            });

            visible.forEach(card => productsGrid.appendChild(card));

            resultsCount.textContent = `${visible.length} product${visible.length === 1 ? '' : 's'}`;
        }

        // Category chips
        catChips.forEach(chip => {
            chip.addEventListener('click', () => {
                activeCategory = chip.dataset.category;

                catChips.forEach(c => c.classList.remove('border-orange-400', 'bg-white/5'));
                chip.classList.add('border-orange-400', 'bg-white/5');

                applyFilters();
            });
        });

        // Apply button
        applyFiltersBtn.addEventListener('click', applyFilters);

        // Clear filters
        clearFiltersBtn.addEventListener('click', () => {
            activeCategory = 'All';
            searchInput.value = '';
            minPriceInput.value = '';
            maxPriceInput.value = '';
            sortSelect.value = 'featured';

            catChips.forEach(c => c.classList.remove('border-orange-400', 'bg-white/5'));
            // Set "All" as active
            catChips[0].classList.add('border-orange-400', 'bg-white/5');

            productCards.forEach(card => card.classList.remove('hidden'));
            resultsCount.textContent = `${productCards.length} products`;
        });

        // Live search typing
        searchInput.addEventListener('input', () => {
            // optional: only filter after >2 chars
            applyFilters();
        });
    </script>
</body>

</html>