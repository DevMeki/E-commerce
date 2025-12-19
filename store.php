<?php
session_start(); // Start session to access user data
// Include DB config
if (file_exists('config.php')) {
    require_once 'config.php';
}


$store = [];
$products = [];
$categories = ['All'];

if (isset($conn) && $conn instanceof mysqli) {

    // Get slug from URL
    $slug = $_GET['slug'] ?? '';

    // Fallback: If no slug but we have an 'id', try to fetch slug (not standard but robust)
    if (empty($slug) && isset($_GET['id'])) {
        $id = (int) $_GET['id'];
        $stmt = $conn->prepare("SELECT slug FROM Brand WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($row = $res->fetch_assoc()) {
            $slug = $row['slug'];
        }
        $stmt->close();
    }

    if (empty($slug)) {
        // No identifier -> redirect to marketplace or show error
        header("Location: marketplace.php");
        exit;
    }

    // 1. Fetch Brand Profile
    // Note: status check to ensure we only show active brands
    $stmt = $conn->prepare("
        SELECT * FROM Brand 
        WHERE slug = ? AND status = 'active' 
        LIMIT 1
    ");
    $stmt->bind_param("s", $slug);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        // Brand not found
        header("Location: marketplace.php"); // Or 404 page
        exit;
    }

    $storeData = $result->fetch_assoc();
    $stmt->close();

    // Map DB fields to template structure
    $store = [
        'id' => $storeData['id'],
        'name' => $storeData['brand_name'],
        'slug' => $storeData['slug'],
        'location' => $storeData['location'],
        'rating' => (float) $storeData['rating'],
        'reviews' => (int) $storeData['total_reviews'],
        'followers' => (int) $storeData['followers'],
        'products_count' => (int) $storeData['products_count'],
        'since' => $storeData['since_year'] ?? date('Y', strtotime($storeData['created_at'])),
        'description' => $storeData['bio'] ?? "Welcome to " . htmlspecialchars($storeData['brand_name']) . "'s store.",
        'policies' => [
            'Shipping' => $storeData['shipping_policy'] ?? 'Standard shipping rates apply.',
            'Returns' => $storeData['return_policy'] ?? 'Contact seller for return information.',
            // Payments are platform wide usually
            'Payments' => 'All payments are processed securely via LocalTrade escrow.',
        ],
    ];

    // 2. Fetch Active Products for this Brand
    $brandId = $storeData['id'];

    $pStmt = $conn->prepare("
        SELECT id, name, category, price, main_image, brand_id 
        FROM Product 
        WHERE brand_id = ? AND status = 'active' AND visibility = 'public'
        ORDER BY created_at DESC
    ");
    $pStmt->bind_param("i", $brandId);
    $pStmt->execute();
    $pResult = $pStmt->get_result();

    while ($pRow = $pResult->fetch_assoc()) {
        $products[] = [
            'id' => $pRow['id'],
            'name' => $pRow['name'],
            'price' => (float) $pRow['price'],
            'category' => $pRow['category'],
            'main_image' => $pRow['main_image'],
            'brand_id' => $pRow['brand_id'] // useful for links
        ];

        // Collect categories
        if (!in_array($pRow['category'], $categories, true)) {
            $categories[] = $pRow['category'];
        }
    }
    $pStmt->close();

    // 3. Check if user is following
    $isFollowing = false;
    if (isset($_SESSION['user']['id']) && $_SESSION['user']['type'] === 'buyer') {
        $buyerId = $_SESSION['user']['id'];
        $fStmt = $conn->prepare("SELECT id FROM BrandFollower WHERE buyer_id = ? AND brand_id = ?");
        $fStmt->bind_param("ii", $buyerId, $brandId);
        $fStmt->execute();
        $fStmt->store_result();
        if ($fStmt->num_rows > 0) {
            $isFollowing = true;
        }
        $fStmt->close();
    }

    // Sort categories
    sort($categories);
    // Ensure All is first
    if (($key = array_search('All', $categories)) !== false) {
        unset($categories[$key]);
        array_unshift($categories, 'All');
    }

} else {
    // DB error handling - redirect/die
    die("Database connection failed.");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($store['name']); ?> – Store | LocalTrade</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <!-- Tailwind CDN -->
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
                <a href="index" class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center"
                        style="background-color: var(--lt-orange);">
                        <div class="w-4 h-3 border-2 border-white border-b-0 rounded-sm relative">
                            <span class="w-1 h-1 bg-white rounded-full absolute -bottom-1 left-0.5"></span>
                            <span class="w-1 h-1 bg-white rounded-full absolute -bottom-1 right-0.5"></span>
                        </div>
                    </div>
                    <span class="font-semibold tracking-tight text-lg">LocalTrade</span>
                </a>
                <a href="javascript:history.back()" class="text-xs sm:text-sm text-gray-300 hover:text-orange-400">
                    ← Back
                </a>
            </div>
        </header>

        <!-- MAIN -->
        <main class="flex-1 py-6 sm:py-10">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

                <!-- STORE HERO -->
                <section
                    class="bg-gradient-to-r from-[#151515] to-black border border-white/10 rounded-3xl p-4 sm:p-6 lg:p-7 mb-6 sm:mb-8">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 md:gap-6">
                        <!-- Left: avatar + store info -->
                        <div class="flex items-start gap-4">
                            <!-- Avatar / logo circle -->
                            <div
                                class="w-16 h-16 sm:w-20 sm:h-20 rounded-2xl bg-[#111111] flex items-center justify-center border border-white/10">
                                <span class="text-xl sm:text-2xl font-semibold">
                                    <?php echo strtoupper(substr($store['name'], 0, 2)); ?>
                                </span>
                            </div>
                            <div>
                                <h1 class="text-xl sm:text-2xl font-semibold mb-1">
                                    <?php echo htmlspecialchars($store['name']); ?>
                                </h1>
                                <div class="flex flex-wrap items-center gap-3 text-xs text-gray-300">
                                    <div class="flex items-center gap-1">
                                        <span class="text-yellow-400">★</span>
                                        <span><?php echo number_format($store['rating'], 1); ?></span>
                                        <span class="text-gray-500">·</span>
                                        <span><?php echo (int) $store['reviews']; ?> reviews</span>
                                    </div>
                                    <span class="text-gray-500 hidden sm:inline">·</span>
                                    <span><?php echo (int) $store['products_count']; ?> products</span>
                                    <span class="text-gray-500 hidden sm:inline">·</span>
                                    <span><?php echo (int) $store['followers']; ?> followers</span>
                                    <span class="text-gray-500 hidden sm:inline">·</span>
                                    <span>Since <?php echo htmlspecialchars($store['since']); ?></span>
                                </div>
                                <p class="mt-2 text-xs sm:text-sm text-gray-300 max-w-xl">
                                    <?php echo htmlspecialchars($store['description']); ?>
                                </p>
                                <p class="mt-1 text-[11px] text-gray-400">
                                    📍 <?php echo htmlspecialchars($store['location']); ?>
                                </p>
                            </div>
                        </div>

                        <!-- Right: actions -->
                        <div class="flex flex-wrap md:flex-col items-stretch md:items-end gap-2 md:gap-3 text-xs">
                            <button id="followBtn"
                                class="px-4 py-2 rounded-full font-semibold transition-colors <?php echo $isFollowing ? 'bg-gray-800 text-white border border-white/20' : 'bg-[#F36A1D] text-white'; ?>"
                                data-brand-id="<?php echo $store['id']; ?>">
                                <?php echo $isFollowing ? 'Following' : '+ Follow store'; ?>
                            </button>
                            <button id="shareBtn"
                                class="px-4 py-2 rounded-full border border-white/20 hover:bg-white/5 transition">
                                ↗ Share store
                            </button>
                        </div>
                    </div>
                </section>

                <!-- TABS: Products / About / Policies / Reviews -->
                <section class="mb-6 sm:mb-8">
                    <div class="border-b border-white/10 flex text-xs sm:text-sm">
                        <button class="store-tab-btn px-4 sm:px-6 py-3 border-b-2 border-orange-400"
                            data-tab="products">
                            Products
                        </button>
                        <button class="store-tab-btn px-4 sm:px-6 py-3 border-b-2 border-transparent" data-tab="about">
                            About
                        </button>
                        <button class="store-tab-btn px-4 sm:px-6 py-3 border-b-2 border-transparent"
                            data-tab="policies">
                            Policies
                        </button>
                        <button class="store-tab-btn px-4 sm:px-6 py-3 border-b-2 border-transparent"
                            data-tab="reviews">
                            Reviews
                        </button>
                    </div>

                    <!-- TAB CONTENT: PRODUCTS -->
                    <div id="store-tab-products" class="store-tab-content pt-4 sm:pt-6">
                        <!-- Filters row -->
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-4">
                            <div class="flex items-center gap-3 text-xs">
                                <label class="text-gray-300">Category:</label>
                                <select id="categoryFilter"
                                    class="bg-[#111111] border border-white/15 rounded-full px-3 py-1.5 text-xs focus:outline-none">
                                    <?php foreach ($categories as $cat): ?>
                                        <option value="<?php echo htmlspecialchars($cat); ?>">
                                            <?php echo htmlspecialchars($cat); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="flex items-center gap-3 text-xs">
                                <label class="text-gray-300">Sort by:</label>
                                <select id="sortSelect"
                                    class="bg-[#111111] border border-white/15 rounded-full px-3 py-1.5 text-xs focus:outline-none">
                                    <option value="featured">Featured</option>
                                    <option value="price-asc">Price: Low to High</option>
                                    <option value="price-desc">Price: High to Low</option>
                                </select>
                            </div>
                        </div>

                        <!-- Products grid -->
                        <div id="productsGrid" class="grid grid-cols-2 md:grid-cols-3 gap-4 text-xs">
                            <?php foreach ($products as $index => $p): ?>
                                <a href="product?id=<?php echo $p['id']; ?>"
                                    class="product-card bg-[#111111] border border-white/10 hover:border-orange-500/70 rounded-2xl p-3 sm:p-4 flex flex-col gap-2"
                                    data-category="<?php echo htmlspecialchars($p['category']); ?>"
                                    data-price="<?php echo (int) $p['price']; ?>">
                                    <div
                                        class="aspect-[4/3] rounded-xl bg-gradient-to-br from-orange-500/60 to-pink-500/60 flex items-center justify-center text-[11px] font-semibold text-center px-2">
                                        <?php echo htmlspecialchars($store['name']); ?>
                                    </div>
                                    <p class="text-sm font-semibold line-clamp-2">
                                        <?php echo htmlspecialchars($p['name']); ?>
                                    </p>
                                    <p class="text-[11px] text-gray-400">
                                        <?php echo htmlspecialchars($p['category']); ?>
                                    </p>
                                    <p class="text-sm font-semibold text-orange-400">
                                        ₦<?php echo number_format($p['price']); ?>
                                    </p>
                                    <button class="mt-auto text-[11px] px-2 py-1 rounded-full bg-white/5">
                                        View product
                                    </button>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- TAB CONTENT: ABOUT -->
                    <div id="store-tab-about" class="store-tab-content hidden pt-4 sm:pt-6 text-sm text-gray-200">
                        <p class="mb-3">
                            <?php echo htmlspecialchars($store['description']); ?>
                        </p>
                        <p class="text-xs text-gray-400">
                            Based in <?php echo htmlspecialchars($store['location']); ?> · Selling on LocalTrade since
                            <?php echo htmlspecialchars($store['since']); ?>.
                        </p>
                    </div>

                    <!-- TAB CONTENT: POLICIES -->
                    <div id="store-tab-policies" class="store-tab-content hidden pt-4 sm:pt-6 text-sm text-gray-200">
                        <dl class="space-y-3">
                            <?php foreach ($store['policies'] as $label => $policy): ?>
                                <div>
                                    <dt class="font-semibold text-gray-100 mb-1"><?php echo htmlspecialchars($label); ?>
                                    </dt>
                                    <dd class="text-gray-300 text-sm">
                                        <?php echo htmlspecialchars($policy); ?>
                                    </dd>
                                </div>
                            <?php endforeach; ?>
                        </dl>
                    </div>

                    <!-- TAB CONTENT: REVIEWS -->
                    <div id="store-tab-reviews" class="store-tab-content hidden pt-4 sm:pt-6 text-sm text-gray-200">
                        <p class="mb-2">
                            Store reviews coming soon. Buyers will be able to rate and review this seller after
                            purchases.
                        </p>
                        <p class="text-xs text-gray-400">
                            Current rating: <?php echo number_format($store['rating'], 1); ?> from
                            <?php echo (int) $store['reviews']; ?> reviews.
                        </p>
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

        // Tabs logic
        const storeTabBtns = document.querySelectorAll('.store-tab-btn');
        const storeTabContents = document.querySelectorAll('.store-tab-content');

        storeTabBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                const target = btn.dataset.tab;

                storeTabBtns.forEach(b => {
                    b.classList.remove('border-orange-400');
                    b.classList.add('border-transparent');
                });

                btn.classList.remove('border-transparent');
                btn.classList.add('border-orange-400');

                storeTabContents.forEach(c => {
                    c.classList.toggle('hidden', c.id !== 'store-tab-' + target);
                });
            });
        });

        // Category filter + sorting (front-end only)
        const categoryFilter = document.getElementById('categoryFilter');
        const sortSelect = document.getElementById('sortSelect');
        const productCards = Array.from(document.querySelectorAll('.product-card'));

        function applyFilters() {
            const category = categoryFilter.value;
            const sort = sortSelect.value;

            // Filter
            productCards.forEach(card => {
                const cardCat = card.dataset.category;
                const show = (category === 'All' || cardCat === category);
                card.classList.toggle('hidden', !show);
            });

            // Sort (simple client-side sort)
            let visible = productCards.filter(card => !card.classList.contains('hidden'));

            visible.sort((a, b) => {
                const priceA = parseInt(a.dataset.price, 10);
                const priceB = parseInt(b.dataset.price, 10);

                if (sort === 'price-asc') return priceA - priceB;
                if (sort === 'price-desc') return priceB - priceA;
                return 0; // 'featured' keeps original order
            });

            const grid = document.getElementById('productsGrid');
            visible.forEach(card => grid.appendChild(card));
        }



        // Share functionality
        document.getElementById('shareBtn').addEventListener('click', async () => {
            const url = window.location.href;
            if (navigator.share) {
                try {
                    await navigator.share({
                        title: '<?php echo htmlspecialchars($store['name']); ?> on LocalTrade',
                        url: url
                    });
                } catch (err) {
                    console.log('Share canceled');
                }
            } else {
                navigator.clipboard.writeText(url).then(() => {
                    showToast('Store link copied to clipboard!');
                });
            }
        });

        // Follow functionality
        const followBtn = document.getElementById('followBtn');
        followBtn.addEventListener('click', async () => {
            const brandId = followBtn.dataset.brandId;

            // Check login first (simple client check logic)
            <?php if (!isset($_SESSION['user'])): ?>
                window.location.href = 'login.php';
                return;
            <?php endif; ?>

            followBtn.disabled = true;
            followBtn.style.opacity = '0.7';

            try {
                const response = await fetch('process/follow-brand.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ brand_id: brandId })
                });

                const result = await response.json();

                if (result.success) {
                    if (result.following) {
                        followBtn.textContent = 'Following';
                        followBtn.className = 'px-4 py-2 rounded-full font-semibold transition-colors bg-gray-800 text-white border border-white/20';
                        showToast('You are now following this store');
                    } else {
                        followBtn.textContent = '+ Follow store';
                        followBtn.className = 'px-4 py-2 rounded-full font-semibold transition-colors bg-[#F36A1D] text-white';
                        showToast('Unfollowed store');
                    }

                    // Update follower count text
                    const followersSpan = Array.from(document.querySelectorAll('span')).find(el => el.textContent.includes('followers'));
                    if (followersSpan && result.newCount !== undefined) {
                        followersSpan.textContent = result.newCount + ' followers';
                    }

                } else {
                    if (result.redirect) {
                        window.location.href = result.redirect;
                    } else {
                        showToast(result.message || 'Action failed');
                    }
                }
            } catch (err) {
                console.error(err);
                showToast('Something went wrong. Please try again.');
            } finally {
                followBtn.disabled = false;
                followBtn.style.opacity = '1';
            }
        });

        function showToast(message) {
            const toast = document.createElement('div');
            toast.className = 'fixed bottom-5 right-5 bg-white text-black px-6 py-3 rounded-lg shadow-xl transform transition-all duration-300 translate-y-20 opacity-0 z-50 font-medium';
            toast.textContent = message;
            document.body.appendChild(toast);

            requestAnimationFrame(() => {
                toast.classList.remove('translate-y-20', 'opacity-0');
            });

            setTimeout(() => {
                toast.classList.add('translate-y-20', 'opacity-0');
                setTimeout(() => toast.remove(), 300);
            }, 3000);
        }
    </script>
</body>

</html>