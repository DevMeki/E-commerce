<?php
// product.php
session_start();

// Include database configuration
require_once 'config.php';

// Get product identifier from URL
$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$product_slug = isset($_GET['slug']) ? trim($_GET['slug']) : '';

// Fetch product from database
$product = null;
$seller = null;
$images = [];
$relatedProducts = [];

if (($product_id > 0 || !empty($product_slug)) && isset($conn) && $conn instanceof mysqli) {
    // Determine search column
    $whereClause = $product_id > 0 ? "p.id = ?" : "p.slug = ?";
    $paramType = $product_id > 0 ? "i" : "s";
    $paramVal = $product_id > 0 ? $product_id : $product_slug;

    // Fetch product details - Relaxed visibility check for direct links
    $stmt = $conn->prepare("
        SELECT 
            p.id, 
            p.name, 
            p.slug, 
            p.status,
            p.category, 
            p.price, 
            p.compare_at_price, 
            p.stock, 
            p.short_desc, 
            p.long_desc, 
            p.main_image, 
            p.shipping_fee, 
            p.ships_from, 
            p.processing_time, 
            p.variants_text, 
            p.rating, 
            p.total_reviews, 
            p.total_sales, 
            p.views,
            b.id as brand_id,
            b.brand_name,
            b.location,
            b.rating as brand_rating,
            b.total_sales as brand_total_sales,
            b.products_count,
            b.status as brand_status,
            b.slug as brand_slug
        FROM Product p
        JOIN Brand b ON p.brand_id = b.id
        WHERE $whereClause AND p.status = 'active' AND b.status = 'active'
    ");
    
    if ($stmt) {
        $stmt->bind_param($paramType, $paramVal);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $product = $result->fetch_assoc();
            
            // Re-assign product_id correctly if it was fetched by slug
            $product_id = $product['id'];
            
            // Increment view count
            $updateView = $conn->prepare("UPDATE Product SET views = views + 1 WHERE id = ?");
            $updateView->bind_param('i', $product_id);
            $updateView->execute();
            $updateView->close();
            
            // Get additional product images
            $imageStmt = $conn->prepare("
                SELECT image_url FROM productimage 
                WHERE product_id = ? 
                ORDER BY sort_order, id
            ");
            $imageStmt->bind_param('i', $product_id);
            $imageStmt->execute();
            $imageResult = $imageStmt->get_result();
            
            $images = [$product['main_image']]; // Main image first
            while ($row = $imageResult->fetch_assoc()) {
                $images[] = $row['image_url'];
            }
            $imageStmt->close();
            
            // Prepare seller info
            $seller = [
                'id' => $product['brand_id'],
                'name' => $product['brand_name'],
                'rating' => $product['brand_rating'],
                'total_products' => $product['products_count'],
                'location' => $product['location'],
                'total_sales' => $product['brand_total_sales']
            ];
            
            // Fetch related products (same category)
            $relatedStmt = $conn->prepare("
                SELECT p.id, p.name, p.price, p.main_image, p.slug
                FROM Product p
                WHERE p.category = ? 
                AND p.id != ? 
                AND p.status = 'active' 
                AND p.visibility = 'public'
                ORDER BY p.total_sales DESC, p.rating DESC
                LIMIT 4
            ");
            $relatedStmt->bind_param('si', $product['category'], $product_id);
            $relatedStmt->execute();
            $relatedResult = $relatedStmt->get_result();
            
            while ($row = $relatedResult->fetch_assoc()) {
                $relatedProducts[] = $row;
            }
            $relatedStmt->close();

            // Fetch more from this brand
            $moreBrandProducts = [];
            $brandStmt = $conn->prepare("
                SELECT p.id, p.name, p.price, p.main_image, p.slug
                FROM Product p
                WHERE p.brand_id = ? 
                AND p.id != ? 
                AND p.status = 'active' 
                AND p.visibility = 'public'
                ORDER BY p.created_at DESC
                LIMIT 4
            ");
            $brandId = $product['brand_id'];
            $brandStmt->bind_param('ii', $brandId, $product_id);
            $brandStmt->execute();
            $brandResult = $brandStmt->get_result();
            while ($row = $brandResult->fetch_assoc()) {
                $moreBrandProducts[] = $row;
            }
            $brandStmt->close();
           if ($product['brand_status'] !== 'active' || $product['status'] === 'archived') {
            $product = null;
        }
        }
        $stmt->close();
    }
}

// If product not found, redirect to marketplace
if (!$product) {
    header('Location: marketplace.php');
    exit;
}

// Get product variants if any
$variants = [];
if (!empty($product['variants_text'])) {
    $variants = json_decode($product['variants_text'], true) ?: [];
}

// Check if user is logged in
$is_logged_in = isset($_SESSION['user']);
$user_id = $is_logged_in ? $_SESSION['user']['id'] : 0;
$user_type = $is_logged_in ? $_SESSION['user']['type'] : '';

// Check if product is in user's cart (Removed to allow multiple variants)
$in_cart = false;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>LocalTrade – <?php echo htmlspecialchars($product['name']); ?></title>
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
        
        .spinner {
            width: 16px;
            height: 16px;
            border: 2px solid rgba(255,255,255,0.3);
            border-radius: 50%;
            border-top-color: white;
            animation: spin 0.8s linear infinite;
            display: inline-block;
        }
        
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
    </style>
</head>
<body class="bg-brand-parchment text-brand-ink font-sans">
    <div class="min-h-screen flex flex-col">

        <!-- Top bar -->
        <header class="border-b border-white/10 bg-brand-forest sticky top-0 z-40 shadow-lg">
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
                <div class="flex items-center gap-4">
                    <a href="marketplace.php" class="text-xs sm:text-sm text-white/70 hover:text-white transition-colors">
                        Back to marketplace
                    </a>
                    <?php if ($is_logged_in && $user_type === 'buyer'): ?>
                        <a href="cart"
                    class="relative flex items-center justify-center w-10 h-10 rounded-full border border-white/10 hover:border-brand-orange transition-all group">
                    <span class="text-sm group-hover:scale-110 text-white transition-transform">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                        </svg>

                    </span>
                </a>
                    <?php endif; ?>
                </div>
            </div>
        </header>

        <!-- Main -->
        <main class="flex-1 py-6 sm:py-10">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 grid lg:grid-cols-2 gap-10">

                <!-- Left: Image gallery -->
                <section>
                    <div class="bg-green-50 rounded-3xl p-4 sm:p-6 border border-brand-forest/5 shadow-sm">
                        <!-- Main image -->
                        <div
                            class="aspect-square bg-brand-parchment rounded-2xl overflow-hidden flex items-center justify-center mb-4 border border-brand-forest/5">
                            <img id="mainImage" src="<?php echo htmlspecialchars($images[0] ?? 'https://via.placeholder.com/600x600?text=Product+Image'); ?>"
                                alt="<?php echo htmlspecialchars($product['name']); ?>"
                                class="w-full h-full object-cover">
                        </div>

                        <!-- Thumbnails -->
                        <div class="grid grid-cols-4 gap-3">
                            <?php foreach ($images as $index => $img): ?>
                                <button
                                    class="thumb border rounded-xl overflow-hidden focus:outline-none focus:ring-2 focus:ring-brand-orange <?php echo $index === 0 ? 'border-brand-orange border-2' : 'border-brand-forest/10'; ?>"
                                    data-src="<?php echo htmlspecialchars($img); ?>">
                                    <img src="<?php echo htmlspecialchars($img); ?>"
                                        alt="Thumbnail <?php echo $index + 1; ?>" class="w-full h-full object-cover">
                                </button>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </section>

                <!-- Right: Product info -->
                <section class="flex flex-col gap-4 sm:gap-5">
                    <!-- Title & rating -->
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-bold mb-1 text-brand-forest">
                            <?php echo htmlspecialchars($product['name']); ?>
                        </h1>
                        <div class="flex flex-wrap items-center gap-3 text-xs text-brand-ink/60">
                            <div class="flex items-center gap-1">
                                <span class="text-brand-orange">★</span>
                                <span class="font-bold text-brand-ink"><?php echo number_format($product['rating'], 1); ?></span>
                                <span class="text-brand-ink/20">·</span>
                                <span><?php echo (int) $product['total_reviews']; ?> reviews</span>
                            </div>
                            <span class="text-brand-ink/20">·</span>
                            <span class="<?php echo $product['stock'] > 0 ? 'text-brand-forest font-bold' : 'text-red-500'; ?>">
                                <?php echo $product['stock'] > 0 ? 'In stock (' . $product['stock'] . ' available)' : 'Out of stock'; ?>
                            </span>
                            <?php if ($product['total_sales'] > 0): ?>
                                <span class="text-brand-ink/20">·</span>
                                <span><?php echo (int) $product['total_sales']; ?> sold</span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Price -->
                    <div class="flex items-baseline gap-3">
                        <p class="text-2xl sm:text-3xl font-bold text-brand-forest">
                            ₦<?php echo number_format($product['price']); ?>
                        </p>
                        <?php if ($product['compare_at_price'] && $product['compare_at_price'] > $product['price']): ?>
                            <p class="text-sm text-gray-400 line-through">
                                ₦<?php echo number_format($product['compare_at_price']); ?>
                            </p>
                            <?php 
                            $discount = round((($product['compare_at_price'] - $product['price']) / $product['compare_at_price']) * 100);
                            if ($discount > 0): ?>
                                <span class="text-xs bg-red-500/20 text-red-300 px-2 py-1 rounded-full">
                                    -<?php echo $discount; ?>%
                                </span>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>

                    <!-- Short description -->
                    <p class="text-sm sm:text-base text-brand-ink/80 leading-relaxed">
                        <?php echo htmlspecialchars($product['short_desc']); ?>
                    </p>

                    <!-- Variants (if any) -->
                    <?php if (!empty($variants)): ?>
                        <div class="bg-green-50 border border-brand-forest/5 rounded-2xl p-4 shadow-sm">
                            <h3 class="text-sm font-bold text-brand-forest mb-4 uppercase tracking-wider">Options</h3>
                            <?php foreach ($variants as $variantType => $options): ?>
                                <div class="mb-4 last:mb-0">
                                    <p class="text-[11px] font-bold text-brand-ink/40 uppercase tracking-widest mb-3">Select <?php echo htmlspecialchars($variantType); ?></p>
                                    <div class="flex flex-wrap gap-2">
                                        <?php foreach ($options as $option): ?>
                                            <button type="button" 
                                                class="variant-btn px-4 py-2 text-xs border border-brand-forest/10 rounded-xl hover:border-brand-orange text-brand-forest font-medium transition-all"
                                                data-type="<?php echo htmlspecialchars($variantType); ?>"
                                                data-value="<?php echo htmlspecialchars($option); ?>">
                                                <?php echo htmlspecialchars($option); ?>
                                            </button>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                            <input type="hidden" id="selectedVariants" value="">
                        </div>
                    <?php endif; ?>

                    <!-- Quantity + buttons -->
                    <div class="bg-green-50 border border-brand-forest/5 rounded-2xl p-4 sm:p-5 flex flex-col gap-4 shadow-sm">
                        <div class="flex items-center justify-between gap-3">
                            <div class="flex items-center gap-4">
                                <span class="text-[11px] font-bold text-brand-ink/40 uppercase tracking-widest">Quantity</span>
                                <div class="flex items-center border border-brand-forest/10 rounded-full overflow-hidden bg-brand-parchment">
                                    <button type="button" id="qtyMinus"
                                        class="w-10 h-10 flex items-center justify-center text-lg text-brand-forest hover:bg-brand-forest/5 transition-colors">
                                        -
                                    </button>
                                    <input id="qtyInput" type="number" value="1" min="1" max="<?php echo $product['stock']; ?>"
                                        class="w-12 text-center text-sm bg-transparent border-0 text-brand-forest font-bold focus:outline-none">
                                    <button type="button" id="qtyPlus"
                                        class="w-10 h-10 flex items-center justify-center text-lg text-brand-forest hover:bg-brand-forest/5 transition-colors">
                                        +
                                    </button>
                                </div>
                            </div>
                            <div class="text-right text-[11px] font-medium text-brand-ink/40">
                                <?php echo (int) $product['stock']; ?> available
                            </div>
                        </div>

                        <div class="flex flex-col sm:flex-row gap-4">
                            <button id="addToCartBtn"
                                class="flex-1 px-8 py-4 rounded-full text-sm font-bold flex items-center justify-center gap-2 text-white shadow-lg shadow-brand-orange/20 transition-all hover:scale-[1.02] active:scale-[0.98]"
                                style="background-color: var(--lt-orange);"
                                <?php echo $product['stock'] <= 0 ? 'disabled' : ''; ?>
                                data-logged-in="<?php echo $is_logged_in && $user_type === 'buyer' ? 'true' : 'false'; ?>">
                                <span id="cartButtonText">
                                    <?php if ($product['stock'] <= 0): ?>
                                        Out of Stock
                                    <?php else: ?>
                                        Add to Cart
                                    <?php endif; ?>
                                </span>
                                <div id="cartSpinner" class="spinner hidden"></div>
                            </button>
                            <button id="buyNowBtn"
                                class="flex-1 px-8 py-4 rounded-full text-sm font-bold border border-brand-forest/10 text-brand-forest hover:bg-brand-forest hover:text-white transition-all shadow-sm"
                                <?php echo $product['stock'] <= 0 ? 'disabled' : ''; ?>>
                                Buy now
                            </button>
                        </div>
                    </div>

                    <!-- Shipping info (Moved to Tabs) -->

                    <!-- Seller box -->
                    <div
                        class="bg-green-50 border border-brand-forest/5 rounded-2xl p-5 flex items-start justify-between gap-5 shadow-sm">
                        <div class="flex-1">
                            <p class="text-[10px] uppercase font-bold tracking-[0.2em] text-brand-ink/40 mb-2">Verified Brand</p>
                            <p class="text-base font-bold text-brand-forest"><?php echo htmlspecialchars($seller['name']); ?></p>
                            <p class="text-xs text-brand-ink/50 mt-1">
                                📍 <?php echo htmlspecialchars($seller['location']); ?>
                            </p>
                            <div class="mt-4 flex flex-wrap gap-4 text-xs font-medium text-brand-forest/70">
                                <div class="flex items-center gap-1.5">
                                    <span class="text-brand-orange">⭐</span>
                                    <span><?php echo number_format($seller['rating'], 1); ?> rating</span>
                                </div>
                                <div class="flex items-center gap-1.5">
                                    <span class="text-brand-forest/30">📦</span>
                                    <span><?php echo (int) $seller['total_products']; ?> products</span>
                                </div>
                                <?php if ($seller['total_sales'] > 0): ?>
                                    <div class="flex items-center gap-1.5">
                                        <span class="text-brand-forest/30">🤝</span>
                                        <span><?php echo (int) $seller['total_sales']; ?> sales</span>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <a href="store.php?slug=<?php echo urlencode($product['brand_slug']); ?>" 
                           class="px-4 py-2 rounded-full border border-brand-forest/10 text-[11px] font-bold text-brand-forest hover:bg-brand-forest hover:text-white transition-all shadow-sm">
                            Visit Store
                        </a>
                    </div>

                    <!-- Tabs: Description / Details / Shipping / Reviews -->
                    <div class="bg-green-50 border border-brand-forest/5 rounded-2xl shadow-sm overflow-hidden">
                        <div class="flex border-b border-brand-forest/5 text-[11px] font-bold uppercase tracking-wider overflow-x-auto bg-brand-parchment/50">
                            <button class="tab-btn flex-1 min-w-max py-4 px-6 text-center border-b-2 border-brand-orange text-brand-forest transition-colors"
                                data-tab="description">Description</button>
                            <button class="tab-btn flex-1 min-w-max py-4 px-6 text-center border-b-2 border-transparent text-brand-ink/40 hover:text-brand-forest transition-colors"
                                data-tab="details">Details</button>
                            <button class="tab-btn flex-1 min-w-max py-4 px-6 text-center border-b-2 border-transparent text-brand-ink/40 hover:text-brand-forest transition-colors"
                                data-tab="shipping">Shipping</button>
                            <button class="tab-btn flex-1 min-w-max py-4 px-6 text-center border-b-2 border-transparent text-brand-ink/40 hover:text-brand-forest transition-colors"
                                data-tab="reviews">Reviews (<?php echo (int) $product['total_reviews']; ?>)</button>
                        </div>
                        <div class="p-6 text-sm text-brand-ink/80 leading-relaxed">
                            <div class="tab-content" id="tab-description">
                                <p class="mb-2 whitespace-pre-line">
                                    <?php echo htmlspecialchars($product['long_desc'] ?: $product['short_desc']); ?>
                                </p>
                            </div>
                            <div class="tab-content hidden" id="tab-details">
                                <dl class="space-y-2">
                                    <div class="flex justify-between gap-4">
                                        <dt class="text-gray-400">Category</dt>
                                        <dd class="text-gray-200 text-right"><?php echo htmlspecialchars($product['category']); ?></dd>
                                    </div>
                                    <div class="flex justify-between gap-4">
                                        <dt class="text-gray-400">SKU</dt>
                                        <dd class="text-gray-200 text-right"><?php echo htmlspecialchars($product['id']); ?></dd>
                                    </div>
                                    <?php if ($product['stock']): ?>
                                        <div class="flex justify-between gap-4">
                                            <dt class="text-gray-400">Available Stock</dt>
                                            <dd class="text-gray-200 text-right"><?php echo (int) $product['stock']; ?> units</dd>
                                        </div>
                                    <?php endif; ?>
                                </dl>

                            </div>
                            <div class="tab-content hidden" id="tab-shipping">
                                <h3 class="text-sm font-bold text-brand-forest mb-4">Shipping & Delivery</h3>
                                <dl class="space-y-4 text-sm">
                                    <div class="flex justify-between gap-4 border-b border-brand-forest/5 pb-4">
                                        <dt class="text-brand-ink/40 font-medium">Ships from</dt>
                                        <dd class="text-brand-forest font-bold text-right"><?php echo htmlspecialchars($product['ships_from']); ?></dd>
                                    </div>
                                    <div class="flex justify-between gap-4 border-b border-brand-forest/5 pb-4">
                                        <dt class="text-brand-ink/40 font-medium">Processing time</dt>
                                        <dd class="text-brand-forest font-bold text-right"><?php echo htmlspecialchars($product['processing_time'] ?: '2-4 business days'); ?></dd>
                                    </div>
                                    <div class="flex justify-between gap-4">
                                        <dt class="text-brand-ink/40 font-medium">Shipping fee</dt>
                                        <dd class="text-brand-forest font-bold text-right">
                                            <?php echo $product['shipping_fee'] ? '₦' . number_format($product['shipping_fee']) : 'Free Delivery'; ?>
                                        </dd>
                                    </div>
                                </dl>
                            </div>
                            <div class="tab-content hidden" id="tab-reviews">
                                <?php if ($product['total_reviews'] > 0): ?>
                                    <div class="flex items-center gap-2 mb-4">
                                        <span class="text-2xl font-bold text-brand-forest"><?php echo number_format($product['rating'], 1); ?></span>
                                        <div class="flex text-brand-orange text-lg">★★★★★</div>
                                        <span class="text-brand-ink/40 text-xs">(<?php echo (int) $product['total_reviews']; ?> reviews)</span>
                                    </div>
                                    <p class="text-xs text-brand-ink/50 italic">
                                        Detailed reviews are being migrated. Buyers can still rate products after purchase.
                                    </p>
                                <?php else: ?>
                                    <p class="text-brand-ink/40 italic">No reviews yet. Be the first to share your experience!</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                </section>
            </div>

            <!-- Related products -->
            <?php if (!empty($relatedProducts)): ?>
            <section class="mt-16 sm:mt-20 border-t border-brand-forest/10 pt-10">
                <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex items-center justify-between mb-8">
                        <div>
                            <h2 class="text-lg sm:text-2xl font-bold text-brand-forest">You may also like</h2>
                            <span class="block h-1 w-12 bg-brand-orange mt-2 rounded-full"></span>
                        </div>
                        <a href="marketplace.php?category=<?php echo urlencode($product['category']); ?>" 
                           class="text-xs font-bold text-brand-orange hover:underline uppercase tracking-wider">
                           Explore Category
                        </a>
                    </div>

                    <div class="grid grid-cols-2 lg:grid-cols-4 gap-6 text-xs">
                        <?php foreach ($relatedProducts as $rp): ?>
                            <a href="product.php?id=<?php echo $rp['id']; ?>" 
                               class="bg-green-50 border border-brand-forest/5 rounded-2xl p-4 flex flex-col gap-3 transition-all shadow-sm hover:shadow-xl group">
                                <div class="aspect-[4/3] rounded-xl overflow-hidden bg-brand-parchment border border-brand-forest/5">
                                    <img src="<?php echo htmlspecialchars($rp['main_image'] ?: 'https://via.placeholder.com/400x300?text=Product'); ?>" 
                                         alt="<?php echo htmlspecialchars($rp['name']); ?>"
                                         class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                </div>
                                <p class="text-sm font-bold text-brand-forest line-clamp-1">
                                    <?php echo htmlspecialchars($rp['name']); ?>
                                </p>
                                <p class="text-sm text-brand-forest font-bold pb-1 border-b border-brand-forest/5">
                                    ₦<?php echo number_format($rp['price']); ?>
                                </p>
                                <button class="mt-auto text-[11px] px-3 py-1.5 rounded-full bg-brand-orange text-white font-bold transition-all shadow-sm shadow-brand-orange/10 group-hover:scale-105">
                                    View Product
                                </button>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            </section>
            <?php endif; ?>

            <!-- More from this brand -->
            <?php if (!empty($moreBrandProducts)): ?>
            <section class="mt-16 sm:mt-20 border-t border-brand-forest/10 pt-10">
                <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex items-center justify-between mb-8">
                        <div>
                            <h2 class="text-lg sm:text-2xl font-bold text-brand-forest">More from <?php echo htmlspecialchars($product['brand_name']); ?></h2>
                            <span class="block h-1 w-12 bg-brand-orange mt-2 rounded-full"></span>
                        </div>
                        <a href="store.php?slug=<?php echo urlencode($product['brand_slug']); ?>" 
                           class="text-xs font-bold text-brand-orange hover:underline uppercase tracking-wider">
                           Visit Store
                        </a>
                    </div>

                    <div class="grid grid-cols-2 lg:grid-cols-4 gap-6 text-xs">
                        <?php foreach ($moreBrandProducts as $mp): ?>
                            <a href="product.php?id=<?php echo $mp['id']; ?>" 
                               class="bg-green-50 border border-brand-forest/5 rounded-2xl p-4 flex flex-col gap-3 transition-all shadow-sm hover:shadow-xl group">
                                <div class="aspect-[4/3] rounded-xl overflow-hidden bg-brand-parchment border border-brand-forest/5">
                                    <img src="<?php echo htmlspecialchars($mp['main_image'] ?: 'https://via.placeholder.com/400x300?text=Product'); ?>" 
                                         alt="<?php echo htmlspecialchars($mp['name']); ?>"
                                         class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                </div>
                                <p class="text-sm font-bold text-brand-forest line-clamp-1">
                                    <?php echo htmlspecialchars($mp['name']); ?>
                                </p>
                                <p class="text-sm text-brand-forest font-bold pb-1 border-b border-brand-forest/5">
                                    ₦<?php echo number_format($mp['price']); ?>
                                </p>
                                <button class="mt-auto text-[11px] px-3 py-1.5 rounded-full bg-brand-orange text-white font-bold transition-all shadow-sm shadow-brand-orange/10 group-hover:scale-105">
                                    View Product
                                </button>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            </section>
            <?php endif; ?>
        </main>

        <!-- Footer -->
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

    <!-- Messages container -->
    <div id="messageContainer" class="fixed top-4 right-5 z-50 max-w-sm"></div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Footer year
            document.getElementById('year').textContent = new Date().getFullYear();

            
            // Image gallery interactions
            const mainImage = document.getElementById('mainImage');
            const thumbs = document.querySelectorAll('.thumb');
            thumbs.forEach(btn => {
                btn.addEventListener('click', () => {
                    const src = btn.getAttribute('data-src');
                    mainImage.src = src;
                    thumbs.forEach(b => {
                        b.classList.remove('border-brand-orange', 'border-2');
                        b.classList.add('border-brand-forest/10');
                    });
                    btn.classList.add('border-brand-orange', 'border-2');
                    btn.classList.remove('border-brand-forest/10');
                });
            });

            // Tab switching
            const tabButtons = document.querySelectorAll('.tab-btn');
            const tabContents = document.querySelectorAll('.tab-content');
            tabButtons.forEach(btn => {
                btn.addEventListener('click', () => {
                    const target = btn.getAttribute('data-tab');
                    
                    // Reset all tabs to inactive state
                    tabButtons.forEach(b => {
                        b.classList.remove('border-brand-orange', 'text-brand-forest');
                        b.classList.add('border-transparent', 'text-brand-ink/40');
                    });
                    
                    // Activate clicked tab
                    btn.classList.remove('border-transparent', 'text-brand-ink/40');
                    btn.classList.add('border-brand-orange', 'text-brand-forest');
                    
                    // Show content
                    tabContents.forEach(c => {
                        c.classList.toggle('hidden', c.id !== 'tab-' + target);
                    });
                });
            });

            // --- Product Logic --- //

            const qtyInput = document.getElementById('qtyInput');
            const qtyMinus = document.getElementById('qtyMinus');
            const qtyPlus = document.getElementById('qtyPlus');
            const maxStock = <?php echo (int) $product['stock']; ?>;
            const selectedVariantsInput = document.getElementById('selectedVariants');
            let selectedVariants = {};

            // Quantity Logic
            qtyMinus.addEventListener('click', () => {
                let current = parseInt(qtyInput.value) || 1;
                if (current > 1) {
                    qtyInput.value = current - 1;
                }
            });
            
            qtyPlus.addEventListener('click', () => {
                let current = parseInt(qtyInput.value) || 1;
                if (current < maxStock) {
                    qtyInput.value = current + 1;
                } else {
                    showMessage('Maximum quantity reached', 'warning');
                }
            });
            
            qtyInput.addEventListener('change', () => {
                let value = parseInt(qtyInput.value) || 1;
                if (value < 1) qtyInput.value = 1;
                if (value > maxStock) {
                    qtyInput.value = maxStock;
                    showMessage('Maximum quantity is ' + maxStock, 'warning');
                }
            });

            // Variant Logic
            const variantButtons = document.querySelectorAll('.variant-btn');
            variantButtons.forEach(btn => {
                btn.addEventListener('click', () => {
                    const type = btn.getAttribute('data-type');
                    const value = btn.getAttribute('data-value');
                    
                    // UI Update for Variants
                    document.querySelectorAll(`.variant-btn[data-type="${type}"]`).forEach(b => {
                        b.classList.remove('border-brand-orange', 'bg-brand-orange/5');
                        b.classList.add('border-brand-forest/10');
                    });
                    btn.classList.add('border-brand-orange', 'bg-brand-orange/5');
                    btn.classList.remove('border-brand-forest/10');
                    
                    // Logic Update
                    selectedVariants[type] = value;
                    selectedVariantsInput.value = JSON.stringify(selectedVariants);
                });
            });

            // --- AJAX Cart Logic --- //

            const addToCartBtn = document.getElementById('addToCartBtn');
            const buyNowBtn = document.getElementById('buyNowBtn');
            const cartButtonText = document.getElementById('cartButtonText');
            const cartSpinner = document.getElementById('cartSpinner');
            // Logged in check from PHP rendered attribute
            const isLoggedIn = addToCartBtn.getAttribute('data-logged-in') === 'true';

            // Generic Add to Cart Function
            async function handleAddToCart(isBuyNow = false) {
                if (!isLoggedIn) {
                    showMessage('Please login to add items to cart', 'error');
                    setTimeout(() => {
                        window.location.href = 'login.php?redirect=' + encodeURIComponent(window.location.href);
                    }, 1500);
                    return;
                }

                // Verify Variants (Optional: Check if all required variants are selected)
                const requiredVariantTypes = new Set([...document.querySelectorAll('.variant-btn')].map(b => b.dataset.type));
                const selectedVariantTypes = new Set(Object.keys(selectedVariants));

                const quantity = parseInt(qtyInput.value) || 1;
                
                // UI Loading State
                const btn = isBuyNow ? buyNowBtn : addToCartBtn;
                const originalText = isBuyNow ? 'Buy now' : cartButtonText.textContent;
                
                btn.disabled = true;
                if (!isBuyNow) {
                    cartButtonText.textContent = 'Adding...';
                    cartSpinner.classList.remove('hidden');
                } else {
                    btn.textContent = 'Processing...';
                }

                try {
                    const formData = new FormData();
                    formData.append('product_id', <?php echo $product_id; ?>);
                    formData.append('quantity', quantity);
                    formData.append('variants', JSON.stringify(selectedVariants));
                    if (isBuyNow) {
                        formData.append('redirect_to_checkout', 'true');
                    }

                    const response = await fetch('process/add-to-cart.php', {
                        method: 'POST',
                        body: formData
                    });

                    const result = await response.json();

                    if (result.success) {
                        if (isBuyNow && result.redirect) {
                            window.location.href = result.redirect;
                        } else {
                            if (!isBuyNow) {
                                cartButtonText.textContent = '✅ Added to Cart';
                                cartSpinner.classList.add('hidden');
                                // Reset button after 2 seconds
                                setTimeout(() => {
                                    cartButtonText.textContent = '🛒 Add to Cart';
                                    btn.disabled = false;
                                }, 3000);
                            }
                            showMessage(result.message || 'Added to cart!', 'success');
                        }
                    } else {
                        showMessage(result.message || 'Failed to add to cart', 'error');
                        // Reset UI
                        btn.disabled = false;
                        if (!isBuyNow) {
                            cartButtonText.textContent = originalText;
                            cartSpinner.classList.add('hidden');
                        } else {
                            btn.textContent = originalText;
                        }
                    }

                } catch (error) {
                    console.error('Cart Error:', error);
                    showMessage('Network error. Please try again.', 'error');
                    // Reset UI
                    btn.disabled = false;
                    if (!isBuyNow) {
                        cartButtonText.textContent = originalText;
                        cartSpinner.classList.add('hidden');
                    } else {
                        btn.textContent = originalText;
                    }
                }
            }

            // Event Listeners for Cart Actions
            addToCartBtn.addEventListener('click', () => handleAddToCart(false));
            buyNowBtn.addEventListener('click', () => handleAddToCart(true));

            // Notifcation System
            function showMessage(message, type = 'info') {
                const container = document.getElementById('messageContainer');
                const messageDiv = document.createElement('div');
                
                // Styling based on message type
                let colors = 'border-blue-500/40 bg-blue-500/10 text-blue-200'; // default info
                if (type === 'success') colors = 'border-green-500/40 bg-green-500/10 text-green-200';
                if (type === 'error') colors = 'border-red-500/40 bg-red-500/10 text-red-200';
                if (type === 'warning') colors = 'border-yellow-500/40 bg-yellow-500/10 text-yellow-200';

                messageDiv.className = `mb-2 p-3 rounded-xl border text-sm backdrop-blur-md shadow-lg transition-all duration-300 transform translate-y-2 opacity-0 ${colors}`;
                messageDiv.textContent = message;

                container.appendChild(messageDiv);

                // Animate in
                requestAnimationFrame(() => {
                    messageDiv.classList.remove('translate-y-2', 'opacity-0');
                });

                // Auto remove
                setTimeout(() => {
                    messageDiv.classList.add('opacity-0', 'translate-y-[-10px]');
                    setTimeout(() => messageDiv.remove(), 300);
                }, 4000);
            }
        });
    </script>
</body>
</html>