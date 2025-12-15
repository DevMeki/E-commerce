<?php
// -------- Mock categories data (replace with DB later) --------
$categories = [
    [
        'slug' => 'fashion',
        'name' => 'Fashion & Wearables',
        'short' => 'Streetwear, Ankara, accessories',
        'products' => 128,
        'tags' => ['Hoodies', 'Tees', 'Bags'],
        'color_from' => 'from-orange-500',
        'color_to' => 'to-pink-500',
    ],
    [
        'slug' => 'beauty',
        'name' => 'Beauty & Personal Care',
        'short' => 'Skincare, haircare, self-care',
        'products' => 96,
        'tags' => ['Shea butter', 'Oils', 'Soaps'],
        'color_from' => 'from-rose-500',
        'color_to' => 'to-amber-400',
    ],
    [
        'slug' => 'electronics',
        'name' => 'Electronics & Gadgets',
        'short' => 'Headphones, accessories, smart tech',
        'products' => 64,
        'tags' => ['Earbuds', 'Power banks', 'Smart home'],
        'color_from' => 'from-blue-500',
        'color_to' => 'to-indigo-500',
    ],
    [
        'slug' => 'home-living',
        'name' => 'Home & Living',
        'short' => 'Decor, kitchen, furniture',
        'products' => 72,
        'tags' => ['Throws', 'Baskets', 'Ceramics'],
        'color_from' => 'from-emerald-500',
        'color_to' => 'to-teal-400',
    ],
    [
        'slug' => 'food-drinks',
        'name' => 'Food & Drinks',
        'short' => 'Snacks, pantry, local specials',
        'products' => 54,
        'tags' => ['Spices', 'Snacks', 'Drinks'],
        'color_from' => 'from-yellow-500',
        'color_to' => 'to-orange-500',
    ],
    [
        'slug' => 'art-craft',
        'name' => 'Art & Craft',
        'short' => 'Handmade pieces from Nigerian artists',
        'products' => 39,
        'tags' => ['Paintings', 'Prints', 'Crafts'],
        'color_from' => 'from-fuchsia-500',
        'color_to' => 'to-purple-500',
    ],
];

// your existing $categories array is here...

// Add this (you can copy the exact $brands array from brands.php):
$brands = [
    [
        'slug' => 'lagos-streetwear-co',
        'name' => 'Lagos Streetwear Co.',
        'location' => 'Lagos, Nigeria',
        'categories' => ['Fashion', 'Wearables'],
    ],
    [
        'slug' => 'abuja-beauty-lab',
        'name' => 'Abuja Beauty Lab',
        'location' => 'Abuja, Nigeria',
        'categories' => ['Beauty', 'Skincare'],
    ],
    [
        'slug' => 'naija-tech-hub',
        'name' => 'Naija Tech Hub',
        'location' => 'Lagos, Nigeria',
        'categories' => ['Electronics', 'Gadgets'],
    ],
    [
        'slug' => 'abeokuta-crafts',
        'name' => 'Abeokuta Crafts',
        'location' => 'Ogun, Nigeria',
        'categories' => ['Home & Living', 'Crafts'],
    ],
    [
        'slug' => 'home-living-ng',
        'name' => 'Home & Living NG',
        'location' => 'Port Harcourt, Nigeria',
        'categories' => ['Home & Living', 'Kitchen'],
    ],
    [
        'slug' => 'lagos-art-collective',
        'name' => 'Lagos Art Collective',
        'location' => 'Lagos, Nigeria',
        'categories' => ['Art & Craft'],
    ],
];

// Build brands-by-category map
$brandsByCategory = [];
foreach ($brands as $brand) {
    foreach ($brand['categories'] as $cat) {
        $brandsByCategory[$cat][] = $brand;
    }
}
ksort($brandsByCategory);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Categories | LocalTrade</title>
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
        <?php $currentPage = 'categories'; include 'header.php'; ?>

        <!-- MAIN -->
        <main class="flex-1 py-6 sm:py-10">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

                <!-- Title + intro -->
                <section class="mb-6 sm:mb-8">
                    <h1 class="text-xl sm:text-2xl font-semibold mb-1">
                        Shop by category
                    </h1>
                    <p class="text-xs sm:text-sm text-gray-300 max-w-2xl">
                        Explore verified Nigerian brands across categories. Choose a category to see all products in the
                        marketplace.
                    </p>
                </section>

                <!-- Search/filter row -->
                <section class="mb-5 sm:mb-7">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                        <div class="w-full md:w-80">
                            <div
                                class="bg-[#111111] border border-white/15 rounded-full px-3 py-1.5 flex items-center gap-2">
                                <span class="text-gray-500 text-sm">🔍</span>
                                <input id="categorySearch" type="text" placeholder="Search categories..."
                                    class="flex-1 bg-transparent border-0 text-xs sm:text-sm text-white placeholder-gray-500 focus:outline-none" />
                            </div>
                        </div>
                        <p id="categoryCount" class="text-xs text-gray-400">
                            <?php echo count($categories); ?> categories available
                        </p>
                    </div>
                </section>

                <!-- CATEGORIES GRID -->
                <section>
                    <div id="categoriesGrid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-5">
                        <?php foreach ($categories as $cat): ?>
                            <a href="marketplace?category=<?php echo urlencode($cat['slug']); ?>"
                                class="category-card bg-[#111111] border border-white/10 hover:border-orange-500/70 rounded-2xl p-4 sm:p-5 flex flex-col gap-3 transition"
                                data-name="<?php echo htmlspecialchars($cat['name']); ?>">
                                <!-- Top: label + product count -->
                                <div class="flex items-start justify-between gap-3">
                                    <div>
                                        <h2 class="text-sm sm:text-base font-semibold">
                                            <?php echo htmlspecialchars($cat['name']); ?>
                                        </h2>
                                        <p class="text-[11px] sm:text-xs text-gray-400">
                                            <?php echo htmlspecialchars($cat['short']); ?>
                                        </p>
                                    </div>
                                    <span class="text-[11px] sm:text-xs px-2 py-1 rounded-full bg-white/5 text-gray-300">
                                        <?php echo (int) $cat['products']; ?> items
                                    </span>
                                </div>

                                <!-- Thumbnail / visual -->
                                <div
                                    class="mt-2 aspect-[5/2] rounded-xl bg-gradient-to-r <?php echo $cat['color_from']; ?> <?php echo $cat['color_to']; ?> flex items-center justify-center text-[11px] text-center px-3">
                                    Discover products from local Nigerian brands in
                                    <span class="font-semibold ml-1"><?php echo htmlspecialchars($cat['name']); ?></span>
                                </div>

                                <!-- Tags -->
                                <?php if (!empty($cat['tags'])): ?>
                                    <div class="flex flex-wrap gap-1.5 mt-2">
                                        <?php foreach ($cat['tags'] as $tag): ?>
                                            <span class="text-[10px] px-2 py-0.5 rounded-full bg-white/5 text-gray-300">
                                                <?php echo htmlspecialchars($tag); ?>
                                            </span>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>

                                <!-- CTA -->
                                <div class="mt-3 flex items-center justify-between text-[11px] text-orange-300">
                                    <span>Browse products</span>
                                    <span>→</span>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </section>

                <!-- ALL BRANDS BY CATEGORY -->
                <section class="mt-8 sm:mt-10 border-t border-white/10 pt-6 sm:pt-8">
                    <div class="max-w-6xl mx-auto">
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-lg sm:text-xl font-semibold">All brands by category</h2>
                            <a href="brands" class="text-xs text-orange-400 hover:underline">
                                View all brands
                            </a>
                        </div>

                        <div class="space-y-4 sm:space-y-5">
                            <?php foreach ($brandsByCategory as $catName => $catBrands): ?>
                                <div class="bg-[#111111] border border-white/10 rounded-2xl p-4 sm:p-5">
                                    <div class="flex items-center justify-between gap-2 mb-3">
                                        <div>
                                            <h3 class="text-sm sm:text-base font-semibold">
                                                <?php echo htmlspecialchars($catName); ?>
                                            </h3>
                                            <p class="text-[11px] sm:text-xs text-gray-400">
                                                <?php echo count($catBrands); ?>
                                                brand<?php echo count($catBrands) === 1 ? '' : 's'; ?> in this category
                                            </p>
                                        </div>
                                        <a href="marketplace?category=<?php echo urlencode($catName); ?>"
                                            class="hidden sm:inline text-[11px] text-orange-300 hover:underline">
                                            View products →
                                        </a>
                                    </div>

                                    <!-- Brand chips -->
                                    <div class="flex flex-wrap gap-2">
                                        <?php foreach ($catBrands as $brand): ?>
                                            <a href="store?brand=<?php echo urlencode($brand['slug']); ?>"
                                                class="inline-flex items-center gap-1 px-3 py-1.5 rounded-full bg-white/5 border border-white/10 hover:border-orange-400 text-[11px] text-gray-100">
                                                <span
                                                    class="w-5 h-5 rounded-full bg-[#0D0D0D] flex items-center justify-center text-[10px]">
                                                    <?php echo strtoupper(substr($brand['name'], 0, 1)); ?>
                                                </span>
                                                <span><?php echo htmlspecialchars($brand['name']); ?></span>
                                                <?php if (!empty($brand['location'])): ?>
                                                    <span class="text-[10px] text-gray-400">
                                                        · <?php echo htmlspecialchars($brand['location']); ?>
                                                    </span>
                                                <?php endif; ?>
                                            </a>
                                        <?php endforeach; ?>
                                    </div>

                                    <!-- Mobile "view products" link -->
                                    <a href="marketplace?category=<?php echo urlencode($catName); ?>"
                                        class="mt-3 inline-block sm:hidden text-[11px] text-orange-300 hover:underline">
                                        View products in <?php echo htmlspecialchars($catName); ?> →
                                    </a>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </section>
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
        // Footer year
        document.getElementById('year').textContent = new Date().getFullYear();

        const searchInput = document.getElementById('categorySearch');
        const categoryCards = Array.from(document.querySelectorAll('.category-card'));
        const categoryCount = document.getElementById('categoryCount');

        function applyCategoryFilter() {
            const term = (searchInput.value || '').toLowerCase().trim();
            let visible = 0;

            categoryCards.forEach(card => {
                const name = card.dataset.name.toLowerCase();
                const show = !term || name.includes(term);

                card.classList.toggle('hidden', !show);
                if (show) visible++;
            });

            categoryCount.textContent = `${visible} categor${visible === 1 ? 'y' : 'ies'} available`;
        }

        searchInput.addEventListener('input', applyCategoryFilter);
    </script>
</body>

</html>