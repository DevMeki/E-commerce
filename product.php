<?php
// ----- Mock product data (replace with DB later) -----
$product = [
    'id' => 101,
    'name' => 'Handcrafted Ankara Tote Bag',
    'price' => 14500,
    'currency' => '₦',
    'rating' => 4.7,
    'reviews' => 38,
    'in_stock' => true,
    'images' => [
        'https://via.placeholder.com/600x600.png?text=Main+Image',
        'https://via.placeholder.com/600x600.png?text=Side+View',
        'https://via.placeholder.com/600x600.png?text=Interior',
        'https://via.placeholder.com/600x600.png?text=Detail',
    ],
    'short_desc' => 'Handmade Ankara tote bag crafted in Lagos with premium fabric and reinforced stitching.',
    'description' => 'This handcrafted Ankara tote bag is made by local artisans in Lagos, Nigeria. '
        . 'Perfect for everyday use, it features inner pockets, durable handles, and a vibrant African print.',
    'details' => [
        'Material' => '100% Cotton Ankara',
        'Origin' => 'Lagos, Nigeria',
        'Dimensions' => '40cm x 35cm x 12cm',
        'Weight' => '0.6kg',
        'Care' => 'Cold hand wash, air dry',
    ],
    'shipping' => 'Ships within 2–4 business days across Nigeria. Standard delivery fee calculated at checkout.',
];

$seller = [
    'name' => 'Lagos Streetwear Co.',
    'rating' => 4.8,
    'total_products' => 52,
    'location' => 'Lagos, Nigeria',
];

$relatedProducts = [
    [
        'name' => 'Ankara Crossbody Bag',
        'price' => 9800,
    ],
    [
        'name' => 'Adire Tote Limited Edition',
        'price' => 16500,
    ],
    [
        'name' => 'Leather & Ankara Backpack',
        'price' => 21500,
    ],
];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>LocalTrade – <?php echo htmlspecialchars($product['name']); ?></title>
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

        <!-- Top bar (simple) -->
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
                <a href="#" class="text-xs sm:text-sm text-gray-300 hover:text-orange-400">
                    Back to marketplace
                </a>
            </div>
        </header>

        <!-- Main -->
        <main class="flex-1 py-6 sm:py-10">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 grid lg:grid-cols-2 gap-10">

                <!-- Left: Image gallery -->
                <section>
                    <div class="bg-[#111111] rounded-3xl p-4 sm:p-6 border border-white/10">
                        <!-- Main image -->
                        <div
                            class="aspect-square bg-black rounded-2xl overflow-hidden flex items-center justify-center mb-4">
                            <img id="mainImage" src="<?php echo htmlspecialchars($product['images'][0]); ?>"
                                alt="<?php echo htmlspecialchars($product['name']); ?>"
                                class="w-full h-full object-cover">
                        </div>

                        <!-- Thumbnails -->
                        <div class="grid grid-cols-4 gap-3">
                            <?php foreach ($product['images'] as $index => $img): ?>
                                <button
                                    class="thumb border rounded-xl overflow-hidden focus:outline-none focus:ring-2 focus:ring-orange-400 <?php echo $index === 0 ? 'border-orange-500' : 'border-white/10'; ?>"
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
                        <h1 class="text-2xl sm:text-3xl font-semibold mb-1">
                            <?php echo htmlspecialchars($product['name']); ?>
                        </h1>
                        <div class="flex flex-wrap items-center gap-3 text-xs text-gray-300">
                            <div class="flex items-center gap-1">
                                <span class="text-yellow-400">★</span>
                                <span><?php echo number_format($product['rating'], 1); ?></span>
                                <span class="text-gray-500">·</span>
                                <span><?php echo (int) $product['reviews']; ?> reviews</span>
                            </div>
                            <span class="text-gray-500">·</span>
                            <span class="<?php echo $product['in_stock'] ? 'text-green-400' : 'text-red-400'; ?>">
                                <?php echo $product['in_stock'] ? 'In stock' : 'Out of stock'; ?>
                            </span>
                        </div>
                    </div>

                    <!-- Price -->
                    <div class="flex items-baseline gap-3">
                        <p class="text-2xl sm:text-3xl font-semibold text-orange-400">
                            <?php echo $product['currency'] . number_format($product['price']); ?>
                        </p>
                        <p class="text-xs sm:text-sm text-gray-400">
                            incl. VAT · secure escrow payment
                        </p>
                    </div>

                    <!-- Short description -->
                    <p class="text-sm sm:text-base text-gray-200">
                        <?php echo htmlspecialchars($product['short_desc']); ?>
                    </p>

                    <!-- Quantity + buttons -->
                    <div class="bg-[#111111] border border-white/10 rounded-2xl p-4 sm:p-5 flex flex-col gap-4">
                        <div class="flex items-center justify-between gap-3">
                            <div class="flex items-center gap-3">
                                <span class="text-sm text-gray-300">Quantity</span>
                                <div class="flex items-center border border-white/20 rounded-full overflow-hidden">
                                    <button type="button" id="qtyMinus"
                                        class="w-8 h-8 flex items-center justify-center text-lg text-gray-300 hover:bg-white/5">
                                        -
                                    </button>
                                    <input id="qtyInput" type="number" value="1" min="1"
                                        class="w-12 text-center text-sm bg-transparent border-0 text-white focus:outline-none">
                                    <button type="button" id="qtyPlus"
                                        class="w-8 h-8 flex items-center justify-center text-lg text-gray-300 hover:bg-white/5">
                                        +
                                    </button>
                                </div>
                            </div>
                            <div class="text-right text-xs text-gray-400">
                                Delivery across Nigeria
                            </div>
                        </div>

                        <div class="flex flex-col sm:flex-row gap-3">
                            <button id="addToCartBtn"
                                class="flex-1 px-4 py-2.5 rounded-full text-sm font-semibold flex items-center justify-center gap-2"
                                style="background-color: var(--lt-orange);">
                                <span>🛒 Add to cart</span>
                            </button>
                            <button id="buyNowBtn"
                                class="flex-1 px-4 py-2.5 rounded-full text-sm font-semibold border border-white/20">
                                Buy now
                            </button>
                        </div>
                    </div>

                    <!-- Seller box -->
                    <div
                        class="bg-[#111111] border border-white/10 rounded-2xl p-4 flex items-start justify-between gap-4">
                        <div>
                            <p class="text-xs uppercase tracking-[0.2em] text-gray-400 mb-1">Sold by</p>
                            <p class="text-sm font-semibold"><?php echo htmlspecialchars($seller['name']); ?></p>
                            <p class="text-xs text-gray-400">
                                <?php echo htmlspecialchars($seller['location']); ?>
                            </p>
                            <div class="mt-2 flex flex-wrap gap-3 text-xs text-gray-300">
                                <span>⭐ <?php echo number_format($seller['rating'], 1); ?> seller rating</span>
                                <span class="text-gray-500">·</span>
                                <span><?php echo (int) $seller['total_products']; ?> products</span>
                            </div>
                        </div>
                        <button class="text-xs text-orange-400 hover:underline">
                            Visit store →
                        </button>
                    </div>

                    <!-- Tabs: Description / Details / Shipping / Reviews -->
                    <div class="bg-[#050505] border border-white/10 rounded-2xl">
                        <div class="flex border-b border-white/5 text-xs">
                            <button class="tab-btn flex-1 py-3 text-center border-b-2 border-orange-400"
                                data-tab="description">Description</button>
                            <button class="tab-btn flex-1 py-3 text-center border-b-2 border-transparent"
                                data-tab="details">Details</button>
                            <button class="tab-btn flex-1 py-3 text-center border-b-2 border-transparent"
                                data-tab="shipping">Shipping</button>
                            <button class="tab-btn flex-1 py-3 text-center border-b-2 border-transparent"
                                data-tab="reviews">Reviews</button>
                        </div>
                        <div class="p-4 sm:p-5 text-sm text-gray-200">
                            <div class="tab-content" id="tab-description">
                                <p class="mb-2">
                                    <?php echo nl2br(htmlspecialchars($product['description'])); ?>
                                </p>
                            </div>
                            <div class="tab-content hidden" id="tab-details">
                                <dl class="space-y-2">
                                    <?php foreach ($product['details'] as $label => $value): ?>
                                        <div class="flex justify-between gap-4">
                                            <dt class="text-gray-400"><?php echo htmlspecialchars($label); ?></dt>
                                            <dd class="text-gray-200 text-right">
                                                <?php echo htmlspecialchars($value); ?>
                                            </dd>
                                        </div>
                                    <?php endforeach; ?>
                                </dl>
                            </div>
                            <div class="tab-content hidden" id="tab-shipping">
                                <p><?php echo htmlspecialchars($product['shipping']); ?></p>
                            </div>
                            <div class="tab-content hidden" id="tab-reviews">
                                <p class="mb-2 text-gray-300">
                                    Reviews feature coming soon. For now, buyers can rate this product after purchase.
                                </p>
                                <p class="text-xs text-gray-500">
                                    Average rating: <?php echo number_format($product['rating'], 1); ?> from
                                    <?php echo (int) $product['reviews']; ?> reviews.
                                </p>
                            </div>
                        </div>
                    </div>

                </section>
            </div>

            <!-- Related products -->
            <section class="mt-10 sm:mt-14 border-t border-white/10 pt-6 sm:pt-8">
                <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-lg sm:text-xl font-semibold">You may also like</h2>
                        <a href="#" class="text-xs text-orange-400 hover:underline">View more</a>
                    </div>

                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4 text-xs">
                        <?php foreach ($relatedProducts as $rp): ?>
                            <div class="bg-[#111111] border border-white/10 rounded-2xl p-3 sm:p-4 flex flex-col gap-2">
                                <div
                                    class="aspect-[4/3] rounded-xl bg-gradient-to-br from-orange-500/60 to-pink-500/60 flex items-center justify-center text-[11px] font-semibold">
                                    Local brand product
                                </div>
                                <p class="text-sm font-semibold">
                                    <?php echo htmlspecialchars($rp['name']); ?>
                                </p>
                                <p class="text-sm text-orange-400 font-semibold">
                                    <?php echo $product['currency'] . number_format($rp['price']); ?>
                                </p>
                                <button class="mt-auto text-[11px] px-2 py-1 rounded-full bg-white/5">
                                    View product
                                </button>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </section>
        </main>

        <!-- Footer -->
        <footer class="border-t border-white/10 bg-black mt-8">
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

        // Image gallery
        const mainImage = document.getElementById('mainImage');
        const thumbs = document.querySelectorAll('.thumb');
        thumbs.forEach(btn => {
            btn.addEventListener('click', () => {
                const src = btn.getAttribute('data-src');
                mainImage.src = src;
                thumbs.forEach(b => b.classList.remove('border-orange-500'));
                btn.classList.add('border-orange-500');
            });
        });

        // Quantity controls
        const qtyInput = document.getElementById('qtyInput');
        document.getElementById('qtyMinus').addEventListener('click', () => {
            const current = parseInt(qtyInput.value || '1', 10);
            if (current > 1) qtyInput.value = current - 1;
        });
        document.getElementById('qtyPlus').addEventListener('click', () => {
            const current = parseInt(qtyInput.value || '1', 10);
            qtyInput.value = current + 1;
        });

        // Tabs
        const tabButtons = document.querySelectorAll('.tab-btn');
        const tabContents = document.querySelectorAll('.tab-content');
        tabButtons.forEach(btn => {
            btn.addEventListener('click', () => {
                const target = btn.getAttribute('data-tab');
                tabButtons.forEach(b => b.classList.remove('border-orange-400'));
                btn.classList.add('border-orange-400');
                tabContents.forEach(c => {
                    c.classList.toggle('hidden', c.id !== 'tab-' + target);
                });
            });
        });

        // Demo handlers for buttons (replace with real AJAX/cart logic)
        document.getElementById('addToCartBtn').addEventListener('click', () => {
            const qty = parseInt(qtyInput.value || '1', 10);
            alert(`Added ${qty} item(s) to cart.`);
        });
        document.getElementById('buyNowBtn').addEventListener('click', () => {
            alert('Proceeding to checkout (implement redirect here).');
        });
    </script>
</body>

</html>