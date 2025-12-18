<?php
// Include configuration for DB connection
if (file_exists('config.php')) {
    require_once 'config.php';
}

// Define gradient pairs for deterministic assignment
$gradients = [
    ['from-orange-500', 'to-pink-500'],
    ['from-rose-500', 'to-amber-400'],
    ['from-blue-500', 'to-indigo-500'],
    ['from-emerald-500', 'to-teal-400'],
    ['from-yellow-500', 'to-orange-500'],
    ['from-fuchsia-500', 'to-purple-500'],
    ['from-cyan-500', 'to-blue-500'],
    ['from-violet-500', 'to-fuchsia-500'],
];

$brands = [];
$categoryFilterOptions = ['All'];

if (isset($conn) && $conn instanceof mysqli) {
    // Fetch brands with product count
    // Using LEFT JOIN to count active products
    // Assuming 'status' column exists and 'active' is the value for visible brands
    $sql = "
        SELECT 
            b.id, 
            b.brand_name, 
            b.slug, 
            b.location, 
            b.category, 
            b.rating, 
            b.created_at, 
            b.bio as description,
            COUNT(p.id) as real_product_count
        FROM Brand b
        LEFT JOIN Product p ON b.id = p.brand_id AND p.status = 'active'
        WHERE b.status = 'active'
        GROUP BY b.id
        HAVING real_product_count > 0
        ORDER BY b.brand_name ASC
    ";
    
    $result = $conn->query($sql);
    
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            // Calculate random-ish but consistent gradient based on ID
            $gradIndex = $row['id'] % count($gradients);
            $colors = $gradients[$gradIndex];
            
            // Format year
            $since = $row['created_at'] ? date('Y', strtotime($row['created_at'])) : date('Y');
            
            // Ensure description isn't empty
            $desc = $row['description'];
            if (empty($desc)) {
                $desc = "Trusted seller on LocalTrade offering quality " . strtolower($row['category']) . " products.";
            }

            // Categories - for now using the main category as an array to match template structure
            // If the brand has products with different categories, we could fetch them, but for now specific brand category + generic
            $brandCats = [$row['category']];
            
            $brands[] = [
                'id'           => $row['id'],
                'slug'         => $row['slug'],
                'name'         => $row['brand_name'],
                'location'     => $row['location'],
                'categories'   => $brandCats,
                'products'     => $row['real_product_count'],
                'rating'       => (float)$row['rating'],
                'since'        => $since,
                'description'  => $desc,
                'color_from'   => $colors[0],
                'color_to'     => $colors[1],
            ];
            
            // Populate filter options
            if (!in_array($row['category'], $categoryFilterOptions, true)) {
                $categoryFilterOptions[] = $row['category'];
            }
        }
        $result->free();
    }
}
// Sort filter options
sort($categoryFilterOptions);
// Ensure 'All' is first
if (($key = array_search('All', $categoryFilterOptions)) !== false) {
    unset($categoryFilterOptions[$key]);
    array_unshift($categoryFilterOptions, 'All');
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
    <?php $currentPage = 'brands_page'; include 'header.php'; ?>

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
                                href="store?slug=<?php echo urlencode($b['slug']); ?>"
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

// Initial category from URL
const urlParams = new URLSearchParams(window.location.search);
const activeCategory = urlParams.get('category');

if (activeCategory && activeCategory !== 'All') {
    // Set dropdown value if it exists
    // We iterate options to check if the category exists in the list to avoid setting invalid value
    let found = false;
    for (let i = 0; i < catFilter.options.length; i++) {
        if (catFilter.options[i].value === activeCategory) {
            catFilter.selectedIndex = i;
            found = true;
            break;
        }
    }
}

// Run filter immediately on load
applyBrandFilters();

searchInput.addEventListener('input', applyBrandFilters);
catFilter.addEventListener('change', applyBrandFilters);
</script>
</body>
</html>
