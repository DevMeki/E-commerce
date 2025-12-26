<?php
// Include DB config
if (file_exists('config.php')) {
    require_once 'config.php';
}

$categoryImages = [
    'Fashion' => 'https://i.pinimg.com/1200x/fc/91/8a/fc918ad6c979aa2cea78bf3cd39abe2f.jpg',
    'Beauty' => 'https://i.pinimg.com/1200x/24/13/1f/24131f500075c21beaf757eb2fa902ac.jpg',
    'Electronics' => 'https://i.pinimg.com/1200x/f0/1a/cb/f01acbc6826b186f73282450d3e6c680.jpg',
    'Home' => 'https://i.pinimg.com/1200x/d0/c7/21/d0c721c65bb005a19ea4ff2e2cb10d32.jpg',
    'Food' => 'https://i.pinimg.com/1200x/a9/4f/a1/a94fa1f9d38884b09034e396e3a274e0.jpg',
    'Art' => 'https://images.unsplash.com/photo-1578301978018-3005759f48f7?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80',
    'All' => 'https://i.pinimg.com/1200x/0b/34/36/0b3436e69a25f4970d1606591fc972b6.jpg',
    'Craft' => 'https://images.unsplash.com/photo-1513475382585-d06e58bcb0e0?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80',
    'Drinks' => 'https://images.unsplash.com/photo-1513558161293-cdaf765ed2fd?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80',
    'Personal Care' => 'https://images.pexels.com/photos/4050344/pexels-photo-4050344.jpeg?auto=compress&cs=tinysrgb&w=800', // Dark-skinned woman with skincare products
    'Gadgets' => 'https://images.unsplash.com/photo-1546868871-7041f2a55e12?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80',
    'Wearables' => 'https://images.unsplash.com/photo-1556909114-f6e7ad7d3136?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80',
    'Living' => 'https://images.unsplash.com/photo-1554995207-c18c203602cb?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80',
    'Streetwear' => 'https://images.unsplash.com/photo-1515372039744-b8f02a3ae446?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80',
    'Skincare' => 'https://images.pexels.com/photos/3985322/pexels-photo-3985322.jpeg?auto=compress&cs=tinysrgb&w=800', // Dark-skinned woman applying moisturizer
    'Tech' => 'https://images.unsplash.com/photo-1518709268805-4e9042af2176?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80',
    'Textiles' => 'https://images.unsplash.com/photo-1542332211-ff9dbb3c7c15?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80',
    'Accessories' => 'https://images.unsplash.com/photo-1594576722512-582d5577dc56?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80',
    'Decor' => 'https://images.unsplash.com/photo-1616486338812-3dadae4b4ace?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80',
    'Furniture' => 'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80',
    'Kitchen' => 'https://i.pinimg.com/1200x/36/ec/60/36ec609df4d5a1c4a74683467b54e98c.jpg',
    'Spices' => 'https://images.unsplash.com/photo-1586201375761-83865001e31c?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80',
    'Herbs' => 'https://images.unsplash.com/photo-1597362925123-77861d3fbac7?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80',
    'Snacks' => 'https://images.unsplash.com/photo-1565958011703-44f9829ba187?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80',
    'Paintings' => 'https://images.unsplash.com/photo-1579783902614-a3fb3927b6a5?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80',
    'Prints' => 'https://images.unsplash.com/photo-1544787219-7f47ccb76574?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80',
    'Sculptures' => 'https://i.pinimg.com/736x/c3/36/da/c336daf378d2a660eade1beb6da28149.jpg',
];

// Helper function to get image for category
function getImageForCategory($categoryName)
{
    global $categoryImages;
    $categoryName = trim($categoryName);

    // First check for exact match
    if (isset($categoryImages[$categoryName])) {
        return $categoryImages[$categoryName];
    }

    // Check for partial matches
    foreach ($categoryImages as $key => $image) {
        if (stripos($categoryName, $key) !== false) {
            return $image;
        }
    }

    // Default image
    return 'https://images.unsplash.com/photo-1556742049-0cfed4f6a45d?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80';
}

function getShortDescription($categoryName)
{
    $descriptions = [
        'Fashion' => 'Streetwear, Ankara, accessories',
        'Beauty' => 'Skincare, haircare, self-care',
        'Electronics' => 'Headphones, accessories, smart tech',
        'Home' => 'Decor, kitchen, furniture',
        'Food' => 'Snacks, pantry, local specials',
        'Art' => 'Handmade pieces from Nigerian artists',
        'Craft' => 'Handmade pieces from Nigerian artists',
        'Drinks' => 'Snacks, pantry, local specials',
        'Personal Care' => 'Skincare, haircare, self-care',
        'Gadgets' => 'Headphones, accessories, smart tech',
        'Wearables' => 'Streetwear, Ankara, accessories',
        'Living' => 'Decor, kitchen, furniture',
        'Streetwear' => 'Urban fashion and accessories',
        'Skincare' => 'Natural skincare products',
        'Tech' => 'Electronics and smart devices',
        'Textiles' => 'Fabrics and clothing materials',
        'Accessories' => 'Jewelry, bags, and fashion accessories',
        'Decor' => 'Home decoration items',
        'Furniture' => 'Home and office furniture',
        'Kitchen' => 'Kitchen utensils and appliances',
        'Spices' => 'Local spices and seasonings',
        'Herbs' => 'Fresh and dried herbs',
        'Snacks' => 'Local snacks and treats',
        'Paintings' => 'Artworks and paintings',
        'Prints' => 'Art prints and posters',
        'Sculptures' => 'Handmade sculptures',
    ];

    foreach ($descriptions as $key => $desc) {
        if (stripos($categoryName, $key) !== false) {
            return $desc;
        }
    }

    return 'Local Nigerian products';
}

function getTagsForCategory($categoryName)
{
    $categoryName = strtolower(trim($categoryName));
    $tagMap = [
        'fashion' => ['Hoodies', 'Tees', 'Bags'],
        'beauty' => ['Shea butter', 'Oils', 'Soaps'],
        'electronics' => ['Earbuds', 'Power banks', 'Smart home'],
        'home' => ['Throws', 'Baskets', 'Ceramics'],
        'food' => ['Spices', 'Snacks', 'Drinks'],
        'art' => ['Paintings', 'Prints', 'Crafts'],
        'craft' => ['Paintings', 'Prints', 'Crafts'],
        'drinks' => ['Juices', 'Teas', 'Beverages'],
        'personal' => ['Skincare', 'Haircare', 'Fragrances'],
        'care' => ['Skincare', 'Haircare', 'Fragrances'],
        'gadgets' => ['Accessories', 'Chargers', 'Cases'],
        'wearables' => ['Clothing', 'Jewelry', 'Watches'],
        'living' => ['Home decor', 'Furniture', 'Lighting'],
        'streetwear' => ['Hoodies', 'Jackets', 'Sneakers'],
        'skincare' => ['Face care', 'Body care', 'Natural'],
        'tech' => ['Gadgets', 'Accessories', 'Smart'],
        'textiles' => ['Fabrics', 'Cloth', 'Materials'],
        'accessories' => ['Jewelry', 'Bags', 'Belts'],
        'decor' => ['Wall art', 'Vases', 'Candles'],
        'furniture' => ['Chairs', 'Tables', 'Shelves'],
        'kitchen' => ['Utensils', 'Cookware', 'Storage'],
        'spices' => ['Seasonings', 'Herbs', 'Powders'],
        'herbs' => ['Medicinal', 'Culinary', 'Fresh'],
        'snacks' => ['Chips', 'Cookies', 'Nuts'],
        'paintings' => ['Canvas', 'Oil', 'Acrylic'],
        'prints' => ['Digital', 'Posters', 'Artwork'],
        'sculptures' => ['Wood', 'Clay', 'Metal'],
    ];

    foreach ($tagMap as $key => $tags) {
        if (strpos($categoryName, $key) !== false) {
            return $tags;
        }
    }

    return ['Local', 'Nigerian', 'Quality'];
}

// Fetch product categories with product counts
$productCategories = [];
if (isset($conn) && $conn) {
    $stmt = $conn->prepare('SELECT category, COUNT(*) as product_count FROM Product WHERE status = "active" GROUP BY category ORDER BY category');
    if ($stmt) {
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $categoryName = $row['category'];
            $productCategories[] = [
                'name' => $categoryName,
                'product_count' => $row['product_count'],
                'short' => getShortDescription($categoryName),
                'tags' => getTagsForCategory($categoryName),
                'image' => getImageForCategory($categoryName),
            ];
        }
        $stmt->close();
    }
}

// Add "All" category at the beginning if there are categories
if (!empty($productCategories)) {
    $totalProducts = array_sum(array_column($productCategories, 'product_count'));
    array_unshift($productCategories, [
        'name' => 'All',
        'product_count' => $totalProducts,
        'short' => 'All products across all categories',
        'tags' => ['Featured', 'Popular', 'New'],
        'image' => getImageForCategory('All'),
    ]);
}

// Fetch brand categories with brand counts
$brandCategories = [];
if (isset($conn) && $conn) {
    // Get distinct categories from active brands
    $stmt = $conn->prepare('SELECT DISTINCT category FROM Brand WHERE status = "active" ORDER BY category');
    if ($stmt) {
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $categoryName = $row['category'];

            // Count active brands in this category
            $brandCountStmt = $conn->prepare('SELECT COUNT(*) as brand_count FROM Brand WHERE category = ? AND status = "active"');
            $brandCountStmt->bind_param('s', $categoryName);
            $brandCountStmt->execute();
            $brandCountResult = $brandCountStmt->get_result();
            $brandCountRow = $brandCountResult->fetch_assoc();
            $brandCountStmt->close();

            if ($brandCountRow['brand_count'] > 0) {
                $brandCategories[] = [
                    'name' => $categoryName,
                    'brand_count' => $brandCountRow['brand_count'],
                    'short' => getShortDescription($categoryName),
                    'tags' => getTagsForCategory($categoryName),
                    'image' => getImageForCategory($categoryName),
                ];
            }
        }
        $stmt->close();
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Categories | LocalTrade</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
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

        .category-image {
            background-size: cover;
            background-position: center;
            position: relative;
        }

        .category-image::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(to bottom, rgba(30, 57, 50, 0.1), rgba(30, 57, 50, 0.6));
            border-radius: 1rem;
        }

        .category-image-content {
            position: relative;
            z-index: 1;
            color: white;
        }
    </style>
</head>

<body class="bg-brand-parchment text-brand-ink font-sans">
    <div class="min-h-screen flex flex-col">

        <!-- HEADER -->
        <?php $currentPage = 'categories';
        include 'header.php'; ?>

        <!-- MAIN -->
        <main class="flex-1 py-6 sm:py-10">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

                <!-- Title + intro -->
                <section class="mb-8">
                    <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold mb-2 text-brand-forest">
                        Shop by category
                    </h1>
                    <p class="text-xs sm:text-sm text-brand-ink/60 max-w-2xl leading-relaxed">
                        Explore curated collections from verified Nigerian artisans. Select a category below to discover
                        unique products in our marketplace.
                    </p>
                </section>

                <!-- Search/filter row -->
                <section class="mb-8">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                        <div class="w-full md:w-96">
                            <div
                                class="bg-white border border-brand-forest/10 rounded-full px-4 py-2 flex items-center gap-2 shadow-sm focus-within:border-brand-orange/30 transition-all">
                                <span class="text-brand-ink/40 text-sm">🔍</span>
                                <input id="categorySearch" type="text"
                                    placeholder="Search categories (e.g. Fashion, Art)..."
                                    class="flex-1 bg-transparent border-0 text-xs sm:text-sm text-brand-ink placeholder-brand-ink/30 focus:outline-none" />
                            </div>
                        </div>
                        <div id="categoryCount"
                            class="text-xs font-medium text-brand-forest/60 bg-brand-forest/5 px-4 py-2 rounded-full">
                            <span id="productCategoryCount"><?php echo count($productCategories); ?></span> Collections
                            •
                            <span id="brandCategoryCount"><?php echo count($brandCategories); ?></span> Industries
                        </div>
                    </div>
                </section>

                <?php if (!empty($productCategories)): ?>
                    <section id="productCategoriesSection" class="mb-12">
                        <h2 class="text-lg sm:text-xl font-bold mb-6 text-brand-forest flex items-center gap-2">
                            Product Categories
                            <span class="h-px flex-1 bg-brand-forest/10 ml-2"></span>
                        </h2>
                        <div id="productCategoriesGrid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                            <?php foreach ($productCategories as $cat): ?>
                                <a href="marketplace?category=<?php echo urlencode($cat['name'] == 'All' ? '' : $cat['name']); ?>"
                                    class="product-category-card bg-green-50 border border-brand-forest/5 hover:border-brand-orange/30 rounded-3xl p-5 flex flex-col gap-4 transition-all shadow-sm hover:shadow-xl group"
                                    data-name="<?php echo htmlspecialchars($cat['name']); ?>"
                                    data-tags="<?php echo htmlspecialchars(implode(' ', $cat['tags'])); ?>">
                                    <!-- Top: label + product count -->
                                    <div class="flex items-start justify-between gap-3">
                                        <div class="flex-1">
                                            <h3
                                                class="text-base sm:text-lg font-bold text-brand-forest group-hover:text-brand-orange transition-colors">
                                                <?php echo htmlspecialchars($cat['name']); ?>
                                            </h3>
                                            <p class="text-[11px] sm:text-xs text-brand-ink/50 mt-0.5">
                                                <?php echo htmlspecialchars($cat['short']); ?>
                                            </p>
                                        </div>
                                        <span
                                            class="text-[10px] font-bold px-2 py-1 rounded-full bg-brand-forest/5 text-brand-forest">
                                            <?php echo (int) $cat['product_count']; ?>
                                        </span>
                                    </div>

                                    <!-- Image thumbnail -->
                                    <div class="aspect-[5/2.5] rounded-2xl overflow-hidden category-image border border-brand-forest/5"
                                        style="background-image: url('<?php echo htmlspecialchars($cat['image']); ?>')">
                                        <div
                                            class="h-full flex items-center justify-center category-image-content p-4 text-center">
                                            <p
                                                class="text-[11px] font-bold px-3 py-1.5 bg-brand-forest/40 backdrop-blur-sm rounded-full border border-white/20">
                                                Explore <?php echo htmlspecialchars($cat['name']); ?>
                                            </p>
                                        </div>
                                    </div>

                                    <!-- Tags -->
                                    <?php if (!empty($cat['tags'])): ?>
                                        <div class="flex flex-wrap gap-2">
                                            <?php foreach ($cat['tags'] as $tag): ?>
                                                <span
                                                    class="text-[10px] font-medium px-2.5 py-1 rounded-lg bg-brand-parchment text-brand-forest border border-brand-forest/5">
                                                    #<?php echo htmlspecialchars($tag); ?>
                                                </span>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>

                                    <!-- CTA -->
                                    <div
                                        class="mt-auto pt-4 border-t border-brand-forest/5 flex items-center justify-between text-[11px] font-bold text-brand-orange">
                                        <span>Browse Collection</span>
                                        <span
                                            class="w-6 h-6 rounded-full bg-brand-orange/10 flex items-center justify-center group-hover:bg-brand-orange group-hover:text-white transition-all">→</span>
                                    </div>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </section>
                <?php endif; ?>

                <?php if (!empty($brandCategories)): ?>
                    <section id="brandCategoriesSection" class="mt-12 pt-10 border-t border-brand-forest/10">
                        <div class="max-w-6xl mx-auto">
                            <div class="flex items-center justify-between mb-8">
                                <div>
                                    <h2 class="text-lg sm:text-xl font-bold text-brand-forest">Browse Brands by Category
                                    </h2>
                                    <p class="text-[11px] sm:text-xs text-brand-ink/50 mt-1">
                                        Discover curated Nigerian businesses organized by their craft
                                    </p>
                                </div>
                                <a href="brands_page"
                                    class="text-xs font-bold text-brand-orange hover:underline uppercase tracking-wider">
                                    View all brands
                                </a>
                            </div>

                            <!-- Brand Categories Grid -->
                            <div id="brandCategoriesGrid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                                <?php foreach ($brandCategories as $cat): ?>
                                    <a href="brands_page?category=<?php echo urlencode($cat['name'] == 'All' ? '' : $cat['name']); ?>"
                                        class="brand-category-card bg-green-50 border border-brand-forest/5 hover:border-brand-orange/30 rounded-3xl p-5 flex flex-col gap-4 transition-all shadow-sm hover:shadow-xl group"
                                        data-name="<?php echo htmlspecialchars($cat['name']); ?>"
                                        data-tags="<?php echo htmlspecialchars(implode(' ', $cat['tags'])); ?>">
                                        <!-- Top: label + brand count -->
                                        <div class="flex items-start justify-between gap-3">
                                            <div class="flex-1">
                                                <h3
                                                    class="text-base sm:text-lg font-bold text-brand-forest group-hover:text-brand-orange transition-colors">
                                                    <?php echo htmlspecialchars($cat['name']); ?>
                                                </h3>
                                                <p class="text-[11px] sm:text-xs text-brand-ink/50 mt-0.5">
                                                    <?php echo htmlspecialchars($cat['short']); ?>
                                                </p>
                                            </div>
                                            <span
                                                class="text-[10px] font-bold px-2 py-1 rounded-full bg-brand-forest/5 text-brand-forest">
                                                <?php echo (int) $cat['brand_count']; ?>
                                            </span>
                                        </div>

                                        <!-- Image thumbnail -->
                                        <div class="aspect-[5/2.5] rounded-2xl overflow-hidden category-image border border-brand-forest/5"
                                            style="background-image: url('<?php echo htmlspecialchars($cat['image']); ?>')">
                                            <div
                                                class="h-full flex items-center justify-center category-image-content p-4 text-center">
                                                <p
                                                    class="text-[11px] font-bold px-3 py-1.5 bg-brand-forest/40 backdrop-blur-sm rounded-full border border-white/20">
                                                    Local <?php echo htmlspecialchars($cat['name']); ?> Brands
                                                </p>
                                            </div>
                                        </div>

                                        <!-- CTA -->
                                        <div
                                            class="mt-auto pt-4 border-t border-brand-forest/5 flex items-center justify-between text-[11px] font-bold text-brand-orange">
                                            <span>Explore Brands</span>
                                            <span
                                                class="w-6 h-6 rounded-full bg-brand-orange/10 flex items-center justify-center group-hover:bg-brand-orange group-hover:text-white transition-all">→</span>
                                        </div>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </section>
                <?php endif; ?>

                <?php if (empty($productCategories) && empty($brandCategories)): ?>
                    <section class="text-center py-20">
                        <div class="bg-white border border-brand-forest/5 rounded-3xl p-8 sm:p-12 shadow-sm">
                            <div class="text-5xl mb-6">📦</div>
                            <h3 class="text-lg sm:text-xl font-bold mb-3 text-brand-forest">No collections found</h3>
                            <p class="text-sm text-brand-ink/50 mb-8 max-w-sm mx-auto">We couldn't find any active
                                categories at the moment. Please check back later.</p>
                            <a href="marketplace"
                                class="inline-block bg-brand-orange text-white px-8 py-3 rounded-full text-sm font-bold shadow-lg shadow-brand-orange/20 transition-all hover:scale-[1.02]">
                                Browse Marketplace
                            </a>
                        </div>
                    </section>
                <?php endif; ?>
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

        const searchInput = document.getElementById('categorySearch');

        function applyCategoryFilter() {
            const term = (searchInput.value || '').toLowerCase().trim();
            let visibleProductCategories = 0;
            let visibleBrandCategories = 0;

            // Filter product categories
            const productCategoryCards = document.querySelectorAll('.product-category-card');
            if (productCategoryCards.length > 0) {
                productCategoryCards.forEach(card => {
                    const name = card.dataset.name.toLowerCase();
                    const tags = card.dataset.tags.toLowerCase();
                    const show = !term || name.includes(term) || tags.includes(term);

                    card.classList.toggle('hidden', !show);
                    if (show) visibleProductCategories++;
                });
            }

            // Filter brand categories
            const brandCategoryCards = document.querySelectorAll('.brand-category-card');
            if (brandCategoryCards.length > 0) {
                brandCategoryCards.forEach(card => {
                    const name = card.dataset.name.toLowerCase();
                    const tags = card.dataset.tags.toLowerCase();
                    const show = !term || name.includes(term) || tags.includes(term);

                    card.classList.toggle('hidden', !show);
                    if (show) visibleBrandCategories++;
                });
            }

            // Update counts
            document.getElementById('productCategoryCount').textContent = visibleProductCategories;
            document.getElementById('brandCategoryCount').textContent = visibleBrandCategories;

            // Show/hide section headers based on visibility
            const productSection = document.getElementById('productCategoriesSection');
            const brandSection = document.getElementById('brandCategoriesSection');

            if (productSection) {
                if (visibleProductCategories === 0) {
                    productSection.classList.add('hidden');
                } else {
                    productSection.classList.remove('hidden');
                }
            }

            if (brandSection) {
                if (visibleBrandCategories === 0) {
                    brandSection.classList.add('hidden');
                } else {
                    brandSection.classList.remove('hidden');
                }
            }

            // Update the main count display
            document.getElementById('categoryCount').innerHTML = `
                <span id="productCategoryCount">${visibleProductCategories}</span> product categories • 
                <span id="brandCategoryCount">${visibleBrandCategories}</span> brand categories
            `;
        }

        if (searchInput) {
            searchInput.addEventListener('input', applyCategoryFilter);

            // Initialize the filter on page load
            applyCategoryFilter();
        }
    </script>
</body>

</html>