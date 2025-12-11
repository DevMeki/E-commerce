<?php
// -------- Mock brands data (replace with DB later) --------
$brands = [
    [
        'slug'         => 'lagos-streetwear-co',
        'name'         => 'Lagos Streetwear Co.',
        'location'     => 'Lagos, Nigeria',
        'categories'   => ['Fashion', 'Wearables'],
        'products'     => 32,
        'rating'       => 4.8,
        'since'        => '2021',
        'description'  => 'Urban fashion label blending African prints with modern silhouettes.',
        'color_from'   => 'from-orange-500',
        'color_to'     => 'to-pink-500',
    ],
    [
        'slug'         => 'abuja-beauty-lab',
        'name'         => 'Abuja Beauty Lab',
        'location'     => 'Abuja, Nigeria',
        'categories'   => ['Beauty', 'Skincare'],
        'products'     => 24,
        'rating'       => 4.7,
        'since'        => '2020',
        'description'  => 'Clean skincare and self-care products made with Nigerian ingredients.',
        'color_from'   => 'from-rose-500',
        'color_to'     => 'to-amber-400',
    ],
    [
        'slug'         => 'naija-tech-hub',
        'name'         => 'Naija Tech Hub',
        'location'     => 'Lagos, Nigeria',
        'categories'   => ['Electronics', 'Gadgets'],
        'products'     => 18,
        'rating'       => 4.6,
        'since'        => '2019',
        'description'  => 'Affordable gadgets and smart accessories for everyday Nigerians.',
        'color_from'   => 'from-blue-500',
        'color_to'     => 'to-indigo-500',
    ],
    [
        'slug'         => 'abeokuta-crafts',
        'name'         => 'Abeokuta Crafts',
        'location'     => 'Ogun, Nigeria',
        'categories'   => ['Home & Living', 'Crafts'],
        'products'     => 21,
        'rating'       => 4.9,
        'since'        => '2018',
        'description'  => 'Handwoven textiles, home decor and artisan-made pieces.',
        'color_from'   => 'from-emerald-500',
        'color_to'     => 'to-teal-400',
    ],
    [
        'slug'         => 'home-living-ng',
        'name'         => 'Home & Living NG',
        'location'     => 'Port Harcourt, Nigeria',
        'categories'   => ['Home & Living', 'Kitchen'],
        'products'     => 16,
        'rating'       => 4.5,
        'since'        => '2022',
        'description'  => 'Everyday home and kitchen essentials with a Nigerian touch.',
        'color_from'   => 'from-yellow-500',
        'color_to'     => 'to-orange-500',
    ],
    [
        'slug'         => 'lagos-art-collective',
        'name'         => 'Lagos Art Collective',
        'location'     => 'Lagos, Nigeria',
        'categories'   => ['Art & Craft'],
        'products'     => 12,
        'rating'       => 4.9,
        'since'        => '2017',
        'description'  => 'Prints, paintings and craft pieces from Lagos-based artists.',
        'color_from'   => 'from-fuchsia-500',
        'color_to'     => 'to-purple-500',
    ],
];

// Build a flat list of categories for filter
$categoryFilterOptions = ['All'];
foreach ($brands as $b) {
    foreach ($b['categories'] as $cat) {
        if (!in_array($cat, $categoryFilterOptions, true)) {
            $categoryFilterOptions[] = $cat;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Brands | LocalTrade</title>
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
                <a href="marketplace.php" class="hover:text-orange-400">Marketplace</a>
                <a href="categories.php" class="hover:text-orange-400">Categories</a>
                <a href="brands.php" class="text-orange-400">Brands</a>
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

            <!-- Title + intro -->
            <section class="mb-6 sm:mb-8">
                <h1 class="text-xl sm:text-2xl font-semibold mb-1">
                    All brands
                </h1>
                <p class="text-xs sm:text-sm text-gray-300 max-w-2xl">
                    Browse verified Nigerian brands selling on LocalTrade. Click a brand to view its storefront and products.
                </p>
            </section>

            <!-- Search + filters -->
            <section class="mb-5 sm:mb-7">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                    <!-- Search -->
                    <div class="w-full md:w-80">
                        <div class="bg-[#111111] border border-white/15 rounded-full px-3 py-1.5 flex items-center gap-2">
                            <span class="text-gray-500 text-sm">🔍</span>
                            <input
                                id="brandSearch"
                                type="text"
                                placeholder="Search brands by name or location..."
                                class="flex-1 bg-transparent border-0 text-xs sm:text-sm text-white placeholder-gray-500 focus:outline-none"
                            />
                        </div>
                    </div>

                    <!-- Category filter -->
                    <div class="flex items-center gap-2 text-xs">
                        <span class="text-gray-300">Filter by category:</span>
                        <select
                            id="brandCategoryFilter"
                            class="bg-[#111111] border border-white/15 rounded-full px-3 py-1.5 text-xs focus:outline-none"
                        >
                            <?php foreach ($categoryFilterOptions as $opt): ?>
                                <option value="<?php echo htmlspecialchars($opt); ?>">
                                    <?php echo htmlspecialchars($opt); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <p id="brandCount" class="text-xs text-gray-400">
                        <?php echo count($brands); ?> brands available
                    </p>
                </div>
            </section>

            <!-- BRANDS GRID -->
            <section>
                <div id="brandsGrid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-5">
                    <?php foreach ($brands as $b): ?>
                        <a
                            href="store.php?brand=<?php echo urlencode($b['slug']); ?>"
                            class="brand-card bg-[#111111] border border-white/10 hover:border-orange-500/70 rounded-2xl p-4 sm:p-5 flex flex-col gap-3 transition"
                            data-name="<?php echo htmlspecialchars($b['name']); ?>"
                            data-location="<?php echo htmlspecialchars($b['location']); ?>"
                            data-categories="<?php echo htmlspecialchars(implode(',', $b['categories'])); ?>"
                        >
                            <!-- Header row -->
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <h2 class="text-sm sm:text-base font-semibold">
                                        <?php echo htmlspecialchars($b['name']); ?>
                                    </h2>
                                    <p class="text-[11px] sm:text-xs text-gray-400">
                                        📍 <?php echo htmlspecialchars($b['location']); ?>
                                    </p>
                                    <p class="mt-1 text-[11px] sm:text-xs text-gray-400">
                                        Since <?php echo htmlspecialchars($b['since']); ?>
                                    </p>
                                </div>
                                <div class="text-right text-[11px] sm:text-xs text-gray-300">
                                    <p class="mb-1">
                                        ⭐ <?php echo number_format($b['rating'], 1); ?>
                                    </p>
                                    <p class="px-2 py-1 rounded-full bg-white/5">
                                        <?php echo (int)$b['products']; ?> products
                                    </p>
                                </div>
                            </div>

                            <!-- Gradient band -->
                            <div class="mt-2 aspect-[5/2] rounded-xl bg-gradient-to-r <?php echo $b['color_from']; ?> <?php echo $b['color_to']; ?> flex items-center justify-center text-[11px] text-center px-3">
                                <?php echo htmlspecialchars($b['description']); ?>
                            </div>

                            <!-- Categories -->
                            <?php if (!empty($b['categories'])): ?>
                                <div class="flex flex-wrap gap-1.5 mt-2">
                                    <?php foreach ($b['categories'] as $cat): ?>
                                        <span class="text-[10px] px-2 py-0.5 rounded-full bg-white/5 text-gray-200">
                                            <?php echo htmlspecialchars($cat); ?>
                                        </span>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>

                            <!-- CTA -->
                            <div class="mt-3 flex items-center justify-between text-[11px] text-orange-300">
                                <span>Visit store</span>
                                <span>→</span>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
            </section>
        </div>
    </main>

    <!-- FOOTER -->
    <footer class="border-t border-white/10 bg-black mt-6">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-5 text-xs text-gray-400 flex flex-col sm:flex-row gap-3 sm:items-center sm:justify-between">
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

const searchInput  = document.getElementById('brandSearch');
const catFilter    = document.getElementById('brandCategoryFilter');
const brandCards   = Array.from(document.querySelectorAll('.brand-card'));
const brandCountEl = document.getElementById('brandCount');

function applyBrandFilters() {
    const term      = (searchInput.value || '').toLowerCase().trim();
    const catFilterValue = catFilter.value;
    let visible = 0;

    brandCards.forEach(card => {
        const name   = card.dataset.name.toLowerCase();
        const loc    = card.dataset.location.toLowerCase();
        const cats   = (card.dataset.categories || '').toLowerCase();

        // Search by name or location
        if (term && !name.includes(term) && !loc.includes(term)) {
            card.classList.add('hidden');
            return;
        }

        // Category filter
        if (catFilterValue !== 'All') {
            const filterVal = catFilterValue.toLowerCase();
            if (!cats.includes(filterVal.toLowerCase())) {
                card.classList.add('hidden');
                return;
            }
        }

        card.classList.remove('hidden');
        visible++;
    });

    brandCountEl.textContent = `${visible} brand${visible === 1 ? '' : 's'} available`;
}

searchInput.addEventListener('input', applyBrandFilters);
catFilter.addEventListener('change', applyBrandFilters);
</script>
</body>
</html>
