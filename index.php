<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>LocalTrade – Buy Local. Sell Global.</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <!-- Tailwind CDN -->
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
        /* Brand helpers (optional) */
        :root {
            --lt-forest: #1E3932;
            --lt-orange: #F36A1D;
            --lt-parchment: #FCFBF7;
            --lt-ink: #1A1A1A;
            --lt-cream: #F3F0E6;
        }
    </style>
</head>

<body class="bg-brand-parchment text-brand-ink">
    <!-- Page wrapper -->
    <div class="min-h-screen flex flex-col">

        <!-- HEADER / NAVBAR -->
        <?php
        $currentPage = 'home';
        include 'header.php';

        // Include DB config
        if (file_exists('config.php')) {
            require_once 'config.php';
        }

        // Fetch featured products
        $products = [];
        $totalProducts = 0;
        if (isset($conn) && $conn) {
            $stmt = $conn->prepare('SELECT p.id, p.name, p.slug, p.category, p.price, p.main_image, b.brand_name FROM Product p JOIN Brand b ON p.brand_id = b.id WHERE p.status = "active" AND p.visibility = "public" AND b.status = "active" ORDER BY p.created_at DESC LIMIT 4');
            if ($stmt) {
                $stmt->execute();
                $result = $stmt->get_result();
                while ($row = $result->fetch_assoc()) {
                    $products[] = $row;
                }
                $stmt->close();
            }

            // Get total products count
            $stmt = $conn->prepare('SELECT COUNT(*) as total FROM Product p JOIN Brand b ON p.brand_id = b.id WHERE p.status = "active" AND p.visibility = "public" AND b.status = "active"');
            if ($stmt) {
                $stmt->execute();
                $result = $stmt->get_result();
                $row = $result->fetch_assoc();
                $totalProducts = $row['total'];
                $stmt->close();
            }

            // Fetch categories
            $categories = [];
            $stmt = $conn->prepare('SELECT DISTINCT category FROM Product WHERE status = "active" ORDER BY category LIMIT 8');
            if ($stmt) {
                $stmt->execute();
                $result = $stmt->get_result();
                while ($row = $result->fetch_assoc()) {
                    $categories[] = $row['category'];
                }
                $stmt->close();
            }

            // Fetch brands
            $brands = [];
            $stmt = $conn->prepare('SELECT id, brand_name, slug, category, logo FROM Brand WHERE status = "active" ORDER BY created_at DESC LIMIT 4');
            if ($stmt) {
                $stmt->execute();
                $result = $stmt->get_result();
                while ($row = $result->fetch_assoc()) {
                    $brands[] = $row;
                }
                $stmt->close();
            }
        }
        ?>

        <!-- MAIN CONTENT -->
        <main class="flex-1">
            <!-- HERO SECTION -->
            <section class="py-10 sm:py-14">
                <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 grid lg:grid-cols-2 gap-10 items-center">
                    <!-- Hero copy -->
                    <div>
                        <p class="text-xs font-semibold tracking-[0.25em] uppercase text-brand-orange mb-3">
                            Nigeria · Marketplace
                        </p>
                        <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold leading-tight mb-4 text-brand-forest">
                            Buy from real Nigerian brands.<br class="hidden sm:block" />
                            <span class="text-brand-orange">Support local. Shop global.</span>
                        </h1>
                        <p class="text-sm sm:text-base text-brand-ink/70 mb-6">
                            LocalTrade connects you with authentic Nigerian sellers—from fashion and beauty
                            to tech and home essentials. Discover trusted brands, secure payments, and fast delivery.
                        </p>

                        <!-- Search bar -->
                        <div
                            class="bg-white border border-brand-forest/50 rounded-full p-1.5 flex items-center mb-4 relative shadow-sm">
                            <input type="text" id="searchInput"
                                placeholder="Search for products, brands, or categories..."
                                class="flex-1 bg-transparent border-0 text-sm text-brand-ink placeholder-brand-ink/40 px-3 py-2 focus:outline-none" />
                            <button id="searchButton"
                                class="px-4 py-2 rounded-full text-sm font-semibold text-white shadow-md shadow-brand-orange/20"
                                style="background-color: var(--lt-orange);">
                                Search
                            </button>
                            <!-- Search dropdown -->
                            <div id="searchDropdown"
                                class="absolute top-full left-0 right-0 mt-1 bg-white border border-white/10 rounded-2xl shadow-xl max-h-80 overflow-y-auto hidden z-10">
                                <!-- Results will be populated here -->
                            </div>
                        </div>

                        <!-- Stats / badges -->
                        <div class="flex flex-wrap gap-4 text-xs text-brand-ink/60">
                            <div class="flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-brand-forest"></span>
                                <span>Verified Nigerian brands</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-brand-orange"></span>
                                <span>Secure escrow payments</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-brand-cream border border-brand-forest/10"></span>
                                <span>Nationwide delivery</span>
                            </div>
                        </div>
                    </div>


                    <div class="lg:justify-self-end w-full max-w-[400px]">
                        <div
                            class="bg-brand-forest border border-white/10 rounded-3xl p-5 sm:p-6 shadow-2xl shadow-brand-forest/30">
                            <p class="text-xs text-brand-orange mb-4 font-bold uppercase tracking-[0.2em]">Trending this
                                week</p>
                            <div class="grid grid-cols-2 gap-3 text-xs">
                                <?php if (!empty($products)): ?>
                                    <?php foreach ($products as $product): ?>
                                        <a href="product?id=<?= $product['id'] ?>"
                                            class="bg-green-50 rounded-2xl p-3 flex flex-col gap-2 hover:scale-[1.02] transition-all group shadow-sm">
                                            <div
                                                class="aspect-[4/3] rounded-xl bg-brand-forest/5 flex items-center justify-center text-[10px] font-semibold text-brand-forest/30 overflow-hidden">
                                                <?php if (!empty($product['main_image'])): ?>
                                                    <img src="<?= htmlspecialchars($product['main_image']) ?>"
                                                        alt="<?= htmlspecialchars($product['name']) ?>"
                                                        class="w-full h-full object-cover" onload="this.style.opacity='1'"
                                                        style="opacity:0; transition: opacity 0.3s;">
                                                <?php else: ?>
                                                    <?php echo htmlspecialchars($product['brand_name']); ?>
                                                <?php endif; ?>
                                            </div>
                                            <div class="flex-1">
                                                <p class="font-semibold text-sm text-brand-forest">
                                                    <?php echo htmlspecialchars($product['name']); ?>
                                                </p>
                                                <p class="text-[11px] text-brand-ink/60">
                                                    <?php echo htmlspecialchars($product['category']); ?>
                                                </p>
                                            </div>
                                            <div class="flex items-center justify-between mt-1">
                                                <p class="font-bold text-sm text-brand-forest">
                                                    ₦<?php echo number_format($product['price']); ?></p>
                                                <div
                                                    class="text-[10px] px-2 py-1 rounded-full bg-brand-orange text-white font-medium shadow-sm shadow-brand-orange/20">
                                                    View
                                                </div>
                                            </div>
                                        </a>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <p class="col-span-full text-center text-gray-400">No products available.</p>
                                <?php endif; ?>
                            </div>
                            <p class="mt-4 text-[11px] text-white/40 text-center">
                                Over <span
                                    class="text-brand-orange font-bold"><?= number_format($totalProducts) ?>+</span>
                                products from verified
                                Nigerian brands.
                            </p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- CATEGORIES SECTION -->
            <section class="py-6 sm:py-8 border-y border-brand-forest/5 bg-brand-cream/20">
                <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-lg sm:text-xl font-bold text-brand-forest">Shop by category</h2>
                        <a href="categories"
                            class="text-xs text-brand-orange hover:underline uppercase tracking-wider font-semibold">View
                            all</a>
                    </div>

                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3 sm:gap-4 text-xs">
                        <?php if (!empty($categories)): ?>
                            <?php foreach ($categories as $category): ?>
                                <a href="marketplace?category=<?= urlencode($category) ?>"
                                    class="bg-green-50 hover:bg-brand-parchment border border-brand-forest/5 rounded-2xl p-3 flex flex-col items-start gap-1 block text-left transition-all shadow-sm hover:shadow-md">
                                    <span
                                        class="text-sm font-semibold text-brand-forest"><?= htmlspecialchars($category) ?></span>
                                    <span class="text-[11px] text-brand-ink/40">Explore products</span>
                                </a>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="col-span-full text-center text-gray-400">No categories available.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </section>


            <!-- AVAILABLE PRODUCTS SECTION -->
            <section class="py-8 sm:py-10">
                <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-lg sm:text-xl font-bold text-brand-forest">Available Products</h2>
                        <a href="marketplace"
                            class="text-xs text-brand-orange hover:underline uppercase tracking-wider font-semibold">View
                            all</a>
                    </div>

                    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4 text-xs">

                        <?php if (!empty($products)): ?>
                            <?php foreach ($products as $product): ?>
                                <a href="product?id=<?= $product['id'] ?>"
                                    class="bg-green-50 border border-brand-forest/5 hover:border-brand-orange/30 rounded-2xl p-3 sm:p-4 flex flex-col gap-2 transition-all shadow-sm hover:shadow-lg group">
                                    <div
                                        class="aspect-[4/3] rounded-xl bg-gradient-to-br from-brand-forest/5 to-brand-orange/5
                                    flex items-center justify-center text-[11px] font-semibold text-brand-forest/30 overflow-hidden">
                                        <?php if (!empty($product['main_image'])): ?>
                                            <img src="<?= htmlspecialchars($product['main_image']) ?>"
                                                alt="<?= htmlspecialchars($product['name']) ?>"
                                                class="w-full h-full object-cover" />
                                        <?php else: ?>
                                            <?= htmlspecialchars($product['brand_name']) ?>
                                        <?php endif; ?>
                                    </div>
                                    <p class="text-sm font-semibold line-clamp-2 text-brand-forest">
                                        <?= htmlspecialchars($product['name']) ?>
                                    </p>
                                    <p class="text-[11px] text-brand-ink/40"><?= htmlspecialchars($product['category']) ?></p>
                                    <p class="text-sm font-bold text-brand-forest">₦<?= number_format($product['price']) ?>
                                    </p>
                                    <button
                                        class="mt-auto text-[11px] px-3 py-1.5 rounded-full bg-brand-orange text-white font-medium hover:bg-brand-orange/90 transition-all shadow-sm shadow-brand-orange/10">
                                        View product
                                    </button>
                                </a>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="col-span-full text-center text-gray-400">No products available.</p>
                        <?php endif; ?>

                    </div>

                    <p class="mt-4 text-[11px] text-brand-ink/40 text-center">
                        Showing <?= count($products) ?> of many products · <a href="marketplace"
                            class="text-brand-forest hover:underline font-semibold">Explore more</a>
                    </p>

                </div>
            </section>

            <!-- FEATURED BRANDS -->
            <section class="py-8 sm:py-10">
                <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-lg sm:text-xl font-bold text-brand-forest">Featured Nigerian brands</h2>
                        <a href="brands_page"
                            class="text-xs text-brand-orange hover:underline uppercase tracking-wider font-semibold">See
                            all brands</a>
                    </div>

                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-xs">
                        <?php if (!empty($brands)): ?>
                            <?php foreach ($brands as $brand): ?>
                                <a href="store?slug=<?= htmlspecialchars($brand['slug']) ?>"
                                    class="bg-green-50 rounded-2xl p-4 border border-brand-forest/5 flex flex-col gap-2 hover:border-brand-forest/20 transition-all shadow-sm">
                                    <?php if (!empty($brand['logo'])): ?>
                                        <img src="<?= htmlspecialchars($brand['logo']) ?>"
                                            alt="<?= htmlspecialchars($brand['brand_name']) ?>"
                                            class="w-12 h-12 rounded-lg object-cover mb-2 ring-1 ring-brand-forest/5" />
                                    <?php endif; ?>
                                    <p class="text-sm font-semibold text-brand-forest">
                                        <?= htmlspecialchars($brand['brand_name']) ?>
                                    </p>
                                    <p class="text-[11px] text-brand-ink/40"><?= htmlspecialchars($brand['category']) ?></p>
                                    <span
                                        class="mt-auto inline-flex items-center gap-1 text-[11px] text-brand-orange font-semibold">
                                        View store →
                                    </span>
                                </a>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="col-span-full text-center text-gray-400">No brands available.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </section>

            <!-- SELLER CTA SECTION -->
            <section class="py-10 sm:py-12 bg-brand-forest text-white">
                <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                    <p class="text-xs uppercase tracking-[0.2em] text-brand-orange mb-2 font-bold">
                        For Nigerian brands
                    </p>
                    <h2 class="text-2xl sm:text-3xl font-semibold mb-3">
                        Sell on LocalTrade and reach customers across Nigeria.
                    </h2>
                    <p class="text-sm sm:text-base text-white/90 mb-6">
                        Whether you’re a solo creator or a growing brand, LocalTrade gives you
                        secure payments, logistics partners, and tools to grow your business.
                    </p>
                    <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
                        <a href="signup?type=brand"
                            class="px-8 py-3 rounded-full text-sm font-bold bg-brand-orange text-white hover:opacity-90 transition-all shadow-lg shadow-brand-orange/20">
                            Start selling
                        </a>
                        <a href="Brands/brand-help.php">
                            <button
                                class="px-8 py-3 rounded-full text-sm border border-white/30 hover:bg-white/10 transition-colors font-medium">
                                Learn how it works
                            </button>
                        </a>
                    </div>
                </div>
            </section>
        </main>

        <!-- FOOTER -->
        <footer class="border-t border-brand-forest/10 bg-brand-cream/30 mt-12 py-8">
            <div
                class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 text-xs text-brand-ink/50 flex flex-col sm:flex-row gap-4 sm:items-center sm:justify-between">
                <p>© <span id="year"></span> LocalTrade. All rights reserved.</p>
                <div class="flex gap-6 font-medium">
                    <a href="#" class="hover:text-brand-orange transition-colors">Privacy Policy</a>
                    <a href="#" class="hover:text-brand-orange transition-colors">Terms of Service</a>
                    <a href="#" class="hover:text-brand-orange transition-colors">Help Center</a>
                </div>
            </div>
        </footer>
    </div>

    <script>
        // Live search functionality
        const searchInput = document.getElementById('searchInput');
        const searchDropdown = document.getElementById('searchDropdown');
        const searchButton = document.getElementById('searchButton');
        let searchTimeout;

        // Debounced search
        searchInput.addEventListener('input', (e) => {
            const query = e.target.value.trim();
            clearTimeout(searchTimeout);
            if (query.length >= 2) {
                searchTimeout = setTimeout(() => performSearch(query), 300);
            } else {
                searchDropdown.classList.add('hidden');
            }
        });

        // Hide dropdown when clicking outside
        document.addEventListener('click', (e) => {
            if (!searchInput.contains(e.target) && !searchDropdown.contains(e.target)) {
                searchDropdown.classList.add('hidden');
            }
        });

        // Search button redirects to marketplace
        searchButton.addEventListener('click', () => {
            const query = searchInput.value.trim();
            if (query) {
                window.location.href = `marketplace?q=${encodeURIComponent(query)}`;
            }
        });

        // Enter key also searches
        searchInput.addEventListener('keydown', (e) => {
            if (e.key === 'Enter') {
                searchButton.click();
            }
        });

        async function performSearch(query) {
            try {
                const res = await fetch(`process/process-search?q=${encodeURIComponent(query)}`);
                const results = await res.json();
                displayResults(results);
            } catch (err) {
                console.error('Search error:', err);
                searchDropdown.classList.add('hidden');
            }
        }

        function displayResults(results) {
            if (results.length === 0) {
                searchDropdown.classList.add('hidden');
                return;
            }

            searchDropdown.innerHTML = results.map(item => {
                if (item.type === 'product') {
                    return `
                        <a href="product?id=${item.id}" class="block px-4 py-3 hover:bg-green-50 border-b border-brand-forest/5 last:border-b-0">
                            <div class="flex items-center gap-3">
                                <div class="flex-1">
                                    <p class="text-sm font-semibold text-brand-forest">${item.name}</p>
                                    <p class="text-xs text-brand-ink/50">${item.brand_name} · ${item.category}</p>
                                </div>
                                <p class="text-sm font-semibold text-brand-forest">₦${item.price.toLocaleString()}</p>
                            </div>
                        </a>
                    `;
                } else {
                    return `
                        <a href="store?slug=${item.slug}" class="block px-4 py-3 hover:bg-green-50 border-b border-brand-forest/5 last:border-b-0">
                            <div class="flex items-center gap-3">
                                <div class="flex-1">
                                    <p class="text-sm font-semibold text-brand-forest">${item.brand_name}</p>
                                    <p class="text-xs text-brand-ink/50">Brand · ${item.category}</p>
                                </div>
                            </div>
                        </a>
                    `;
                }
            }).join('');
            searchDropdown.classList.remove('hidden');
        }

        // Year in footer
        // Already handled by PHP: <?= date('Y') ?>
    </script>
</body>

</html>