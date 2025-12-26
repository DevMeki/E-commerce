<?php
// Include DB config
if (file_exists('config.php')) {
    require_once 'config.php';
}

// Fetch categories
$categories = ['All'];
if (isset($conn) && $conn) {
    $stmt = $conn->prepare('SELECT DISTINCT category FROM Product WHERE status = "active" ORDER BY category');
    if ($stmt) {
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $categories[] = $row['category'];
        }
        $stmt->close();
    }
}

// Fetch products
$products = [];
if (isset($conn) && $conn) {
    $stmt = $conn->prepare('SELECT p.id, p.name, p.slug, p.category, p.price, p.main_image, b.brand_name FROM Product p JOIN Brand b ON p.brand_id = b.id WHERE p.status = "active" AND p.visibility = "public" AND b.status = "active" ORDER BY p.created_at DESC');
    if ($stmt) {
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $products[] = [
                'id' => $row['id'],
                'name' => $row['name'],
                'brand' => $row['brand_name'],
                'category' => $row['category'],
                'price' => $row['price'],
                'currency' => '₦',
                'badge' => '', // No badge in DB, set empty
                'main_image' => $row['main_image'],
            ];
        }
        $stmt->close();
    }
}

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
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'brand-forest': '#1E3932',
                        'brand-orange': '#F36A1D',
                        'brand-parchment': '#FCFBF7',
                        'brand-ink': '#1A1A1A',
                        'brand-cream': '#F3F0E6',
                    }
                }
            }
        }
    </script>
    <style>
        :root {
            --lt-forest: #1E3932;
            --lt-orange: #F36A1D;
            --lt-parchment: #FCFBF7;
            --lt-ink: #1A1A1A;
            --lt-cream: #F3F0E6;
        }
    </style>
</head>

<body class="bg-brand-parchment text-brand-ink font-sans">
    <div class="min-h-screen flex flex-col">

        <!-- HEADER -->
        <?php $currentPage = 'marketplace';
        include 'header.php'; ?>

        <!-- MAIN -->
        <main class="flex-1 py-6 sm:py-10">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

                <!-- Title + search -->
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-5 sm:mb-7">
                    <div>
                        <h1 class="text-xl sm:text-2xl font-bold mb-1 text-brand-forest">
                            Marketplace
                        </h1>
                        <p class="text-xs sm:text-sm text-brand-ink/60">
                            Discover products from verified Nigerian brands across fashion, beauty, electronics and
                            more.
                        </p>
                    </div>
                    <div class="w-full md:w-80">
                        <div
                            class="bg-white border border-brand-forest/10 rounded-full px-4 py-2 flex items-center gap-2 shadow-sm focus-within:border-brand-orange/30 transition-all">
                            <span class="text-brand-ink/40 text-sm">🔍</span>
                            <input id="searchInput" type="text" placeholder="Search products or brands..."
                                class="flex-1 bg-transparent border-0 text-xs sm:text-sm text-brand-ink placeholder-brand-ink/30 focus:outline-none" />
                        </div>
                    </div>
                </div>

                <!-- Filters + content -->
                <div class="grid gap-6 lg:grid-cols-[minmax(0,260px)_minmax(0,1fr)]">

                    <!-- FILTERS -->
                    <aside class="bg-white border border-brand-forest/5 rounded-2xl p-4 sm:p-5 h-max shadow-sm">
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-sm font-bold text-brand-forest uppercase tracking-wider">Filters</h2>
                            <button id="clearFilters" class="text-[11px] text-brand-orange hover:underline font-bold">
                                Clear all
                            </button>
                        </div>

                        <div class="space-y-6">
                            <!-- Category -->
                            <div>
                                <p class="text-[11px] font-bold text-brand-ink/40 uppercase tracking-widest mb-3">
                                    Category</p>
                                <div class="flex flex-wrap gap-2">
                                    <?php foreach ($categories as $cat): ?>
                                        <button
                                            class="cat-chip text-[11px] px-3 py-1.5 rounded-full border border-brand-forest/10 bg-brand-parchment text-brand-forest hover:border-brand-orange transition-all"
                                            data-category="<?php echo htmlspecialchars($cat); ?>">
                                            <?php echo htmlspecialchars($cat); ?>
                                        </button>
                                    <?php endforeach; ?>
                                </div>
                            </div>

                            <!-- Price range -->
                            <div>
                                <p class="text-[11px] font-bold text-brand-ink/40 uppercase tracking-widest mb-3">Price
                                    range (₦)</p>
                                <div class="flex gap-2 items-center">
                                    <input id="minPrice" type="number" placeholder="Min"
                                        class="w-full bg-brand-parchment border border-brand-forest/10 rounded-xl px-3 py-2 text-xs text-brand-ink focus:outline-none focus:border-brand-orange/30" />
                                    <span class="text-brand-ink/20">–</span>
                                    <input id="maxPrice" type="number" placeholder="Max"
                                        class="w-full bg-brand-parchment border border-brand-forest/10 rounded-xl px-3 py-2 text-xs text-brand-ink focus:outline-none focus:border-brand-orange/30" />
                                </div>
                            </div>

                            <!-- Sort -->
                            <div>
                                <p class="text-[11px] font-bold text-brand-ink/40 uppercase tracking-widest mb-3">Sort
                                    by</p>
                                <select id="sortSelect"
                                    class="w-full bg-brand-parchment border border-brand-forest/10 rounded-xl px-3 py-2 text-xs text-brand-ink focus:outline-none appearance-none cursor-pointer">
                                    <option value="featured">Featured</option>
                                    <option value="price-asc">Price: Low to High</option>
                                    <option value="price-desc">Price: High to Low</option>
                                </select>
                            </div>
                        </div>

                        <!-- Apply -->
                        <button id="applyFilters"
                            class="mt-8 w-full px-4 py-3 rounded-full text-xs font-bold text-white shadow-lg shadow-brand-orange/20 hover:scale-[1.02] transition-all"
                            style="background-color: var(--lt-orange);">
                            Apply filters
                        </button>

                        <p class="mt-4 text-[10px] text-brand-ink/40 text-center leading-relaxed">
                            Marketplace results verified for <br> Nigerian authenticity.
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
                                <a href="product?id=<?php echo (int) $p['id']; ?>"
                                    class="product-card bg-green-50 border border-brand-forest/5 hover:border-brand-orange/30 rounded-2xl p-3 sm:p-4 flex flex-col gap-2 transition-all shadow-sm hover:shadow-lg group"
                                    data-name="<?php echo htmlspecialchars($p['name']); ?>"
                                    data-brand="<?php echo htmlspecialchars($p['brand']); ?>"
                                    data-category="<?php echo htmlspecialchars($p['category']); ?>"
                                    data-price="<?php echo (int) $p['price']; ?>">
                                    <!-- Image wrapper -->
                                    <div
                                        class="aspect-[4/3] rounded-xl bg-brand-parchment flex items-center justify-center text-[11px] text-center px-2 overflow-hidden border border-brand-forest/5">
                                        <?php if (!empty($p['main_image'])): ?>
                                            <img src="<?php echo htmlspecialchars($p['main_image']); ?>"
                                                alt="<?php echo htmlspecialchars($p['name']); ?>"
                                                class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" />
                                        <?php else: ?>
                                            <span
                                                class="text-brand-forest/20 font-bold"><?php echo htmlspecialchars($p['brand']); ?></span>
                                        <?php endif; ?>
                                    </div>

                                    <!-- Info -->
                                    <p class="text-sm font-bold text-brand-forest line-clamp-2 mt-1">
                                        <?php echo htmlspecialchars($p['name']); ?>
                                    </p>
                                    <p class="text-[11px] text-brand-ink/50">
                                        <?php echo htmlspecialchars($p['category']); ?>
                                    </p>
                                    <p class="text-sm font-bold text-brand-forest pb-1 border-b border-brand-forest/5">
                                        <?php echo $currency . number_format($p['price']); ?>
                                    </p>

                                    <button
                                        class="mt-auto text-[11px] px-3 py-1.5 rounded-full bg-brand-orange text-white font-bold transition-all hover:scale-105 active:scale-100 shadow-sm shadow-brand-orange/20">
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
        <footer class="border-t border-brand-forest/10 bg-brand-cream/30 mt-12 py-8">
            <div
                class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 text-xs text-brand-ink/50 flex flex-col sm:flex-row gap-4 sm:items-center sm:justify-between">
                <p>© <span id="year"></span> LocalTrade. All rights reserved.</p>
                <div class="flex gap-6 font-medium">
                    <a href="#" class="hover:text-brand-orange transition-colors">Privacy</a>
                    <a href="#" class="hover:text-brand-orange transition-colors">Terms</a>
                    <a href="#" class="hover:text-brand-orange transition-colors">Support</a>
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

        // Initial category from URL or default
        const urlParams = new URLSearchParams(window.location.search);
        let activeCategory = urlParams.get('category') || 'All';

        // Set initial chip state
        document.addEventListener('DOMContentLoaded', () => {
            if (activeCategory !== 'All') {
                // Find and highlight correct chip
                catChips.forEach(c => {
                    if (c.dataset.category === activeCategory) {
                        c.classList.add('border-brand-orange', 'bg-brand-orange/5', 'font-bold');
                    } else {
                        c.classList.remove('border-brand-orange', 'bg-brand-orange/5', 'font-bold');
                    }
                });

                // If "All" was default active (first one), remove it if we have a specific cat
                if (activeCategory !== 'All') {
                    // Assuming "All" is the first one and might have been hardcoded active in HTML? 
                    // Actually, no chip has 'border-orange-400' by default in HTML loop, 
                    // but likely we want to set "All" if nothing else is pending.
                }

                // Trigger filter immediately
                applyFilters();
            } else {
                // Highlight "All" by default if no param
                // Assuming the first chip is "All" if the loop covers it, 
                // but checking the PHP, $categories = ['All', ...] so first chip is "All".
                if (catChips.length > 0) {
                    catChips[0].classList.add('border-brand-orange', 'bg-brand-orange/5', 'font-bold');
                }
                applyFilters();
            }
        });

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

                catChips.forEach(c => c.classList.remove('border-brand-orange', 'bg-brand-orange/5', 'font-bold'));
                chip.classList.add('border-brand-orange', 'bg-brand-orange/5', 'font-bold');

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

            catChips.forEach(c => c.classList.remove('border-brand-orange', 'bg-brand-orange/5', 'font-bold'));
            // Set "All" as active
            catChips[0].classList.add('border-brand-orange', 'bg-brand-orange/5', 'font-bold');

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