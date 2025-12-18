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
    'Kitchen' => 'https://images.unsplash.com/photo-1556909114-f6e7ad7d3136?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80',
    'Spices' => 'https://images.unsplash.com/photo-1586201375761-83865001e31c?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80',
    'Herbs' => 'https://images.unsplash.com/photo-1597362925123-77861d3fbac7?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80',
    'Snacks' => 'https://images.unsplash.com/photo-1565958011703-44f9829ba187?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80',
    'Paintings' => 'https://images.unsplash.com/photo-1579783902614-a3fb3927b6a5?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80',
    'Prints' => 'https://images.unsplash.com/photo-1544787219-7f47ccb76574?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80',
    'Sculptures' => 'https://images.unsplash.com/photo-1541961017774-22349e4a1262?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80',
];

// Helper function to get image for category
function getImageForCategory($categoryName) {
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

function getShortDescription($categoryName) {
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

function getTagsForCategory($categoryName) {
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
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        :root {
            --lt-orange: #F36A1D;
            --lt-black: #0D0D0D;
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
            background: linear-gradient(to bottom, rgba(0,0,0,0.1), rgba(0,0,0,0.7));
            border-radius: 0.75rem;
        }
        
        .category-image-content {
            position: relative;
            z-index: 1;
            color: white;
            text-shadow: 0 1px 3px rgba(0,0,0,0.5);
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
                                <input id="categorySearch" type="text" placeholder="Search categories or brands..."
                                    class="flex-1 bg-transparent border-0 text-xs sm:text-sm text-white placeholder-gray-500 focus:outline-none" />
                            </div>
                        </div>
                        <p id="categoryCount" class="text-xs text-gray-400">
                            <span id="productCategoryCount"><?php echo count($productCategories); ?></span> product categories • 
                            <span id="brandCategoryCount"><?php echo count($brandCategories); ?></span> brand categories
                        </p>
                    </div>
                </section>

                <!-- PRODUCT CATEGORIES GRID -->
                <?php if (!empty($productCategories)): ?>
                <section id="productCategoriesSection" class="mb-12">
                    <h2 class="text-lg sm:text-xl font-semibold mb-4">Product Categories</h2>
                    <div id="productCategoriesGrid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-5">
                        <?php foreach ($productCategories as $cat): ?>
                            <a href="marketplace?category=<?php echo urlencode($cat['name'] == 'All' ? '' : $cat['name']); ?>"
                                class="product-category-card bg-[#111111] border border-white/10 hover:border-orange-500/70 rounded-2xl p-4 sm:p-5 flex flex-col gap-3 transition group"
                                data-name="<?php echo htmlspecialchars($cat['name']); ?>"
                                data-tags="<?php echo htmlspecialchars(implode(' ', $cat['tags'])); ?>">
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
                                        <?php echo (int) $cat['product_count']; ?> items
                                    </span>
                                </div>

                                <!-- Image thumbnail -->
                                <div class="mt-2 aspect-[5/2] rounded-xl overflow-hidden category-image"
                                     style="background-image: url('<?php echo htmlspecialchars($cat['image']); ?>')">
                                    <div class="h-full flex items-center justify-center category-image-content p-4">
                                        <div class="text-center">
                                            <p class="text-[11px] font-medium">
                                                Discover products from local Nigerian brands in
                                                <span class="font-bold"><?php echo htmlspecialchars($cat['name']); ?></span>
                                            </p>
                                        </div>
                                    </div>
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
                                <div class="mt-3 flex items-center justify-between text-[11px]">
                                    <span class="text-orange-300 group-hover:text-orange-400 transition">Browse products</span>
                                    <span class="text-orange-300 group-hover:text-orange-400 transition">→</span>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </section>
                <?php endif; ?>

                <!-- BRAND CATEGORIES GRID -->
                <?php if (!empty($brandCategories)): ?>
                <section id="brandCategoriesSection" class="mt-8 sm:mt-10 border-t border-white/10 pt-6 sm:pt-8">
                    <div class="max-w-6xl mx-auto">
                        <div class="flex items-center justify-between mb-6">
                            <div>
                                <h2 class="text-lg sm:text-xl font-semibold">Browse Brands by Category</h2>
                                <p class="text-[11px] sm:text-xs text-gray-400 mt-1">
                                    Discover Nigerian brands organized by their specialties
                                </p>
                            </div>
                            <a href="brands_page" class="text-xs text-orange-400 hover:underline">
                                View all brands
                            </a>
                        </div>

                        <!-- Brand Categories Grid -->
                        <div id="brandCategoriesGrid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-5">
                            <?php foreach ($brandCategories as $cat): ?>
                                <a href="brands_page?category=<?php echo urlencode($cat['name']); ?>"
                                    class="brand-category-card bg-[#111111] border border-white/10 hover:border-orange-500/70 rounded-2xl p-4 sm:p-5 flex flex-col gap-3 transition group"
                                    data-name="<?php echo htmlspecialchars($cat['name']); ?>"
                                    data-tags="<?php echo htmlspecialchars(implode(' ', $cat['tags'])); ?>">
                                    <!-- Top: label + brand count -->
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
                                            <?php echo (int) $cat['brand_count']; ?> brands
                                        </span>
                                    </div>

                                    <!-- Image thumbnail -->
                                    <div class="mt-2 aspect-[5/2] rounded-xl overflow-hidden category-image"
                                         style="background-image: url('<?php echo htmlspecialchars($cat['image']); ?>')">
                                        <div class="h-full flex items-center justify-center category-image-content p-4">
                                            <div class="text-center">
                                                <p class="text-[11px] font-medium">
                                                    Discover local Nigerian brands in
                                                    <span class="font-bold"><?php echo htmlspecialchars($cat['name']); ?></span>
                                                </p>
                                            </div>
                                        </div>
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
                                    <div class="mt-3 flex items-center justify-between text-[11px]">
                                        <span class="text-orange-300 group-hover:text-orange-400 transition">Browse brands</span>
                                        <span class="text-orange-300 group-hover:text-orange-400 transition">→</span>
                                    </div>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </section>
                <?php endif; ?>

                <?php if (empty($productCategories) && empty($brandCategories)): ?>
                <section class="text-center py-12">
                    <div class="bg-[#111111] border border-white/10 rounded-2xl p-8 sm:p-12">
                        <div class="text-4xl mb-4">📦</div>
                        <h3 class="text-lg sm:text-xl font-semibold mb-2">No categories available</h3>
                        <p class="text-sm text-gray-400 mb-6">There are no active categories in the marketplace yet.</p>
                        <a href="marketplace" class="inline-block bg-orange-500 hover:bg-orange-600 text-white px-6 py-2 rounded-full text-sm font-medium transition">
                            Browse all products
                        </a>
                    </div>
                </section>
                <?php endif; ?>
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