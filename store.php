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
        <header class="border-b border-white/10 bg-brand-forest shadow-lg">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
                <a href="index.php" class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center bg-brand-parchment">
                        <div class="w-4 h-3 border-2 border-brand-forest border-b-0 rounded-sm relative">
                            <span class="w-1 h-1 bg-brand-forest rounded-full absolute -bottom-1 left-0.5"></span>
                            <span class="w-1 h-1 bg-brand-forest rounded-full absolute -bottom-1 right-0.5"></span>
                        </div>
                    </div>
                    <span class="font-bold tracking-tight text-lg text-white">LocalTrade</span>
                </a>
                <a href="javascript:history.back()"
                    class="text-xs sm:text-sm text-white/70 hover:text-white transition-colors">
                    ← Back
                </a>
            </div>
        </header>

        <!-- MAIN -->
        <main class="flex-1 py-6 sm:py-10">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

                <!-- STORE HERO -->
                <section class="bg-green-50 border border-brand-forest/5 rounded-3xl p-6 sm:p-8 lg:p-10 mb-8 shadow-sm">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                        <!-- Left: avatar + store info -->
                        <div
                            class="flex flex-col sm:flex-row items-center sm:items-start gap-6 text-center sm:text-left">
                            <!-- Avatar / logo circle -->
                            <div
                                class="w-20 h-20 sm:w-24 sm:h-24 rounded-3xl bg-brand-parchment flex items-center justify-center border border-brand-forest/5 shadow-inner">
                                <span class="text-2xl sm:text-3xl font-bold text-brand-forest">
                                    <?php echo strtoupper(substr($store['name'], 0, 2)); ?>
                                </span>
                            </div>
                            <div>
                                <h1 class="text-2xl sm:text-3xl font-bold mb-2 text-brand-forest">
                                    <?php echo htmlspecialchars($store['name']); ?>
                                </h1>
                                <div
                                    class="flex flex-wrap items-center justify-center sm:justify-start gap-4 text-xs font-medium text-brand-ink/60">
                                    <div class="flex items-center gap-1.5">
                                        <span class="text-brand-orange text-lg">★</span>
                                        <span
                                            class="text-brand-forest font-bold"><?php echo number_format($store['rating'], 1); ?></span>
                                        <span class="text-brand-ink/20">·</span>
                                        <span><?php echo (int) $store['reviews']; ?> reviews</span>
                                    </div>
                                    <span class="text-brand-ink/20 hidden sm:inline">·</span>
                                    <div class="flex items-center gap-1.5">
                                        <span class="text-brand-forest/30">📦</span>
                                        <span><?php echo (int) $store['products_count']; ?> products</span>
                                    </div>
                                    <span class="text-brand-ink/20 hidden sm:inline">·</span>
                                    <div class="flex items-center gap-1.5">
                                        <span class="text-brand-forest/30">👥</span>
                                        <span><?php echo (int) $store['followers']; ?> followers</span>
                                    </div>
                                </div>
                                <p class="mt-4 text-sm sm:text-base text-brand-ink/70 max-w-xl leading-relaxed">
                                    <?php echo htmlspecialchars($store['description']); ?>
                                </p>
                                <div
                                    class="mt-3 inline-flex items-center gap-2 px-3 py-1.5 bg-brand-forest/5 rounded-full text-[11px] font-bold text-brand-forest">
                                    📍 <?php echo htmlspecialchars($store['location']); ?> • Since
                                    <?php echo htmlspecialchars($store['since']); ?>
                                </div>
                            </div>
                        </div>

                        <!-- Right: actions -->
                        <div class="flex flex-col gap-3 min-w-[160px]">
                            <button id="followBtn"
                                class="w-full px-6 py-3 rounded-full font-bold text-sm transition-all shadow-lg <?php echo $isFollowing ? 'bg-brand-parchment text-brand-forest border border-brand-forest/10' : 'bg-brand-orange text-white shadow-brand-orange/20 hover:scale-[1.02]'; ?>"
                                data-brand-id="<?php echo $store['id']; ?>">
                                <?php echo $isFollowing ? 'Following' : 'Follow Store'; ?>
                            </button>
                            <button id="shareBtn"
                                class="w-full px-6 py-3 rounded-full border border-brand-forest/10 text-brand-forest font-bold text-sm hover:bg-brand-forest hover:text-white transition-all shadow-sm">
                                ↗ Share Store
                            </button>
                        </div>
                    </div>
                </section>

                <!-- TABS: Products / About / Policies / Reviews -->
                <section class="mb-8">
                    <div
                        class="border-b border-brand-forest/10 flex text-[11px] font-bold uppercase tracking-widest overflow-x-auto">
                        <button
                            class="store-tab-btn px-6 py-4 border-b-2 border-brand-orange text-brand-forest transition-colors"
                            data-tab="products">
                            Products
                        </button>
                        <button
                            class="store-tab-btn px-6 py-4 border-b-2 border-transparent text-brand-ink/40 hover:text-brand-forest transition-colors"
                            data-tab="about">
                            About
                        </button>
                        <button
                            class="store-tab-btn px-6 py-4 border-b-2 border-transparent text-brand-ink/40 hover:text-brand-forest transition-colors"
                            data-tab="policies">
                            Policies
                        </button>
                        <button
                            class="store-tab-btn px-6 py-4 border-b-2 border-transparent text-brand-ink/40 hover:text-brand-forest transition-colors"
                            data-tab="reviews">
                            Reviews
                        </button>
                    </div>

                    <!-- TAB CONTENT: PRODUCTS -->
                    <div id="store-tab-products" class="store-tab-content pt-8">
                        <!-- Filters row -->
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-6 mb-8">
                            <div class="flex items-center gap-4 text-[11px]">
                                <label class="font-bold text-brand-ink/40 uppercase tracking-widest">Category</label>
                                <select id="categoryFilter"
                                    class="bg-white border border-brand-forest/10 rounded-full px-4 py-2 text-xs font-bold text-brand-forest focus:outline-none focus:border-brand-orange transition-all appearance-none cursor-pointer pr-10 relative">
                                    <?php foreach ($categories as $cat): ?>
                                        <option value="<?php echo htmlspecialchars($cat); ?>">
                                            <?php echo htmlspecialchars($cat); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="flex items-center gap-4 text-[11px]">
                                <label class="font-bold text-brand-ink/40 uppercase tracking-widest">Sort by</label>
                                <select id="sortSelect"
                                    class="bg-white border border-brand-forest/10 rounded-full px-4 py-2 text-xs font-bold text-brand-forest focus:outline-none focus:border-brand-orange transition-all appearance-none cursor-pointer pr-10">
                                    <option value="featured">Featured</option>
                                    <option value="price-asc">Price: Low to High</option>
                                    <option value="price-desc">Price: High to Low</option>
                                </select>
                            </div>
                        </div>

                        <!-- Products grid -->
                        <div id="productsGrid" class="grid grid-cols-2 lg:grid-cols-4 gap-6 text-xs">
                            <?php foreach ($products as $index => $p): ?>
                                <a href="product?id=<?php echo $p['id']; ?>"
                                    class="product-card bg-green-50 border border-brand-forest/5 hover:border-brand-orange/30 rounded-2xl p-4 flex flex-col gap-3 transition-all shadow-sm hover:shadow-xl group"
                                    data-category="<?php echo htmlspecialchars($p['category']); ?>"
                                    data-price="<?php echo (int) $p['price']; ?>">
                                    <div
                                        class="aspect-[4/3] rounded-xl bg-brand-parchment flex items-center justify-center border border-brand-forest/5 overflow-hidden">
                                        <?php if (!empty($p['main_image'])): ?>
                                            <img src="<?php echo htmlspecialchars($p['main_image']); ?>"
                                                class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" />
                                        <?php else: ?>
                                            <span class="text-brand-forest/20 font-bold uppercase tracking-widest text-[10px]">
                                                <?php echo htmlspecialchars($store['name']); ?>
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                    <p class="text-sm font-bold text-brand-forest line-clamp-2">
                                        <?php echo htmlspecialchars($p['name']); ?>
                                    </p>
                                    <p class="text-[11px] text-brand-ink/50">
                                        <?php echo htmlspecialchars($p['category']); ?>
                                    </p>
                                    <p class="text-sm font-bold text-brand-forest pb-1 border-b border-brand-forest/5">
                                        ₦<?php echo number_format($p['price']); ?>
                                    </p>
                                    <button
                                        class="mt-auto text-[11px] px-3 py-1.5 rounded-full bg-brand-orange text-white font-bold transition-all shadow-sm shadow-brand-orange/10 group-hover:scale-105">
                                        View Product
                                    </button>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- TAB CONTENT: ABOUT -->
                    <div id="store-tab-about"
                        class="store-tab-content hidden pt-8 text-sm text-brand-ink/80 leading-relaxed">
                        <div class="bg-green-50 border border-brand-forest/5 rounded-3xl p-8 shadow-sm">
                            <h3 class="text-lg font-bold text-brand-forest mb-4">Our Story</h3>
                            <p class="mb-6 whitespace-pre-line">
                                <?php echo htmlspecialchars($store['description']); ?>
                            </p>
                            <div
                                class="p-4 bg-brand-parchment rounded-2xl inline-flex flex-col gap-1 border border-brand-forest/5">
                                <span class="text-[10px] uppercase font-bold text-brand-ink/40">Registered
                                    Location</span>
                                <span
                                    class="text-sm font-bold text-brand-forest"><?php echo htmlspecialchars($store['location']); ?></span>
                                <span class="text-xs text-brand-ink/40">Selling on LocalTrade since
                                    <?php echo htmlspecialchars($store['since']); ?></span>
                            </div>
                        </div>
                    </div>

                    <!-- TAB CONTENT: POLICIES -->
                    <div id="store-tab-policies" class="store-tab-content hidden pt-8">
                        <div class="grid md:grid-cols-3 gap-6">
                            <?php foreach ($store['policies'] as $label => $policy): ?>
                                <div class="bg-green-50 border border-brand-forest/5 rounded-3xl p-6 shadow-sm">
                                    <div
                                        class="w-10 h-10 bg-brand-forest/5 rounded-full flex items-center justify-center mb-4">
                                        <span class="text-brand-forest">📜</span>
                                    </div>
                                    <dt class="font-bold text-brand-forest mb-2 uppercase tracking-wide text-xs">
                                        <?php echo htmlspecialchars($label); ?>
                                    </dt>
                                    <dd class="text-brand-ink/60 text-sm leading-relaxed">
                                        <?php echo htmlspecialchars($policy); ?>
                                    </dd>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- TAB CONTENT: REVIEWS -->
                    <div id="store-tab-reviews" class="store-tab-content hidden pt-8 text-center py-12">
                        <div class="inline-flex flex-col items-center">
                            <div class="text-5xl mb-6">⭐</div>
                            <h3 class="text-xl font-bold text-brand-forest mb-4">Trusted Presence</h3>
                            <p class="text-brand-ink/50 max-w-sm mb-6">
                                <?php echo htmlspecialchars($store['name']); ?> has maintained an average rating of
                                <span
                                    class="font-bold text-brand-forest"><?php echo number_format($store['rating'], 1); ?></span>
                                across <span
                                    class="font-bold text-brand-forest"><?php echo (int) $store['reviews']; ?></span>
                                verified purchases.
                            </p>
                            <span
                                class="px-6 py-2 bg-brand-parchment border border-brand-forest/5 rounded-full text-[11px] font-bold text-brand-forest italic">
                                Detailed buyer reviews are currently being verified for authenticity.
                            </span>
                        </div>
                    </div>
                </section>
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
        // Footer year
        document.getElementById('year').textContent = new Date().getFullYear();

        // Tabs logic
        const storeTabBtns = document.querySelectorAll('.store-tab-btn');
        const storeTabContents = document.querySelectorAll('.store-tab-content');

        storeTabBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                const target = btn.dataset.tab;

                storeTabBtns.forEach(b => {
                    b.classList.remove('border-brand-orange', 'text-brand-forest');
                    b.classList.add('border-transparent', 'text-brand-ink/40');
                });

                btn.classList.remove('border-transparent', 'text-brand-ink/40');
                btn.classList.add('border-brand-orange', 'text-brand-forest');

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
                        followBtn.className = 'w-full px-6 py-3 rounded-full font-bold text-sm transition-all bg-brand-parchment text-brand-forest border border-brand-forest/10';
                        showToast('You are now following this store');
                    } else {
                        followBtn.textContent = 'Follow Store';
                        followBtn.className = 'w-full px-6 py-3 rounded-full font-bold text-sm bg-brand-orange text-white shadow-lg shadow-brand-orange/20 hover:scale-[1.02] transition-all';
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
            toast.className = 'fixed bottom-5 right-5 bg-white border border-brand-forest/10 text-brand-forest px-6 py-4 rounded-2xl shadow-2xl transform transition-all duration-300 translate-y-20 opacity-0 z-50 font-bold text-sm backdrop-blur-md';
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