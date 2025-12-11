<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>LocalTrade – Buy Local. Sell Global.</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <!-- Tailwind CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Brand helpers (optional) */
        :root {
            --lt-orange: #F36A1D;
            --lt-black: #0D0D0D;
        }
    </style>
</head>

<body class="bg-[#0D0D0D] text-white">
    <!-- Page wrapper -->
    <div class="min-h-screen flex flex-col">

        <!-- HEADER / NAVBAR -->
        <?php $currentPage = 'home';
        include 'header.php'; ?>

        <!-- MAIN CONTENT -->
        <main class="flex-1">
            <!-- HERO SECTION -->
            <section class="py-10 sm:py-14">
                <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 grid lg:grid-cols-2 gap-10 items-center">
                    <!-- Hero copy -->
                    <div>
                        <p class="text-xs font-semibold tracking-[0.25em] uppercase text-orange-400 mb-3">
                            Nigeria · Marketplace
                        </p>
                        <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold leading-tight mb-4">
                            Buy from real Nigerian brands.<br class="hidden sm:block" />
                            <span class="text-orange-400">Support local. Shop global.</span>
                        </h1>
                        <p class="text-sm sm:text-base text-gray-300 mb-6">
                            LocalTrade connects you with authentic Nigerian sellers—from fashion and beauty
                            to tech and home essentials. Discover trusted brands, secure payments, and fast delivery.
                        </p>

                        <!-- Search bar -->
                        <div class="bg-white/5 border border-white/10 rounded-full p-1.5 flex items-center mb-4">
                            <input type="text" placeholder="Search for products, brands, or categories..."
                                class="flex-1 bg-transparent border-0 text-sm text-white placeholder-gray-400 px-3 py-2 focus:outline-none" />
                            <button class="px-4 py-2 rounded-full text-sm font-semibold"
                                style="background-color: var(--lt-orange);">
                                Search
                            </button>
                        </div>

                        <!-- Stats / badges -->
                        <div class="flex flex-wrap gap-4 text-xs text-gray-300">
                            <div class="flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-green-400"></span>
                                <span>Verified Nigerian brands</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-orange-400"></span>
                                <span>Secure escrow payments</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-blue-400"></span>
                                <span>Nationwide delivery</span>
                            </div>
                        </div>
                    </div>

                    <!-- Hero card / mockup -->
                    <div class="lg:justify-self-end">
                        <div
                            class="bg-gradient-to-b from-[#1A1A1A] to-black border border-white/5 rounded-3xl p-5 sm:p-6 shadow-xl shadow-black/40">
                            <p class="text-xs text-gray-400 mb-3">Trending this week</p>
                            <div class="grid grid-cols-2 gap-3 text-xs">
                                <!-- product card -->
                                <div class="bg-[#111111] rounded-2xl p-3 flex flex-col gap-2">
                                    <div
                                        class="aspect-[4/3] rounded-xl bg-gradient-to-br from-orange-500 to-yellow-400 flex items-center justify-center text-[10px] font-semibold">
                                        Lagos Streetwear
                                    </div>
                                    <div class="flex-1">
                                        <p class="font-semibold text-sm">Ankara Hoodie</p>
                                        <p class="text-[11px] text-gray-400">Urban Naija fit</p>
                                    </div>
                                    <div class="flex items-center justify-between mt-1">
                                        <p class="font-semibold text-sm text-orange-400">₦18,500</p>
                                        <button class="text-[11px] px-2 py-1 rounded-full bg-white/5">
                                            Add
                                        </button>
                                    </div>
                                </div>

                                <!-- product card -->
                                <div class="bg-[#111111] rounded-2xl p-3 flex flex-col gap-2">
                                    <div
                                        class="aspect-[4/3] rounded-xl bg-gradient-to-br from-emerald-500 to-teal-400 flex items-center justify-center text-[10px] font-semibold">
                                        Abuja Beauty Co
                                    </div>
                                    <div class="flex-1">
                                        <p class="font-semibold text-sm">Shea Butter Glow Kit</p>
                                        <p class="text-[11px] text-gray-400">Natural skincare</p>
                                    </div>
                                    <div class="flex items-center justify-between mt-1">
                                        <p class="font-semibold text-sm text-orange-400">₦9,900</p>
                                        <button class="text-[11px] px-2 py-1 rounded-full bg-white/5">
                                            Add
                                        </button>
                                    </div>
                                </div>

                                <!-- product card -->
                                <div class="bg-[#111111] rounded-2xl p-3 flex flex-col gap-2">
                                    <div
                                        class="aspect-[4/3] rounded-xl bg-gradient-to-br from-blue-500 to-indigo-500 flex items-center justify-center text-[10px] font-semibold">
                                        Naija Tech Hub
                                    </div>
                                    <div class="flex-1">
                                        <p class="font-semibold text-sm">Wireless Earbuds</p>
                                        <p class="text-[11px] text-gray-400">Noise cancelling</p>
                                    </div>
                                    <div class="flex items-center justify-between mt-1">
                                        <p class="font-semibold text-sm text-orange-400">₦14,200</p>
                                        <button class="text-[11px] px-2 py-1 rounded-full bg-white/5">
                                            Add
                                        </button>
                                    </div>
                                </div>

                                <!-- product card -->
                                <div class="bg-[#111111] rounded-2xl p-3 flex flex-col gap-2">
                                    <div
                                        class="aspect-[4/3] rounded-xl bg-gradient-to-br from-fuchsia-500 to-pink-500 flex items-center justify-center text-[10px] font-semibold">
                                        Home & Living NG
                                    </div>
                                    <div class="flex-1">
                                        <p class="font-semibold text-sm">Handwoven Throw</p>
                                        <p class="text-[11px] text-gray-400">Made in Abeokuta</p>
                                    </div>
                                    <div class="flex items-center justify-between mt-1">
                                        <p class="font-semibold text-sm text-orange-400">₦11,000</p>
                                        <button class="text-[11px] px-2 py-1 rounded-full bg-white/5">
                                            Add
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <p class="mt-4 text-[11px] text-gray-400 text-center">
                                Over <span class="text-orange-400 font-semibold">2,000+</span> products from verified
                                Nigerian brands.
                            </p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- CATEGORIES SECTION -->
            <section class="py-6 sm:py-8 border-t border-white/5 bg-[#050505]">
                <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-lg sm:text-xl font-semibold">Shop by category</h2>
                        <a href="#" class="text-xs text-orange-400 hover:underline">View all</a>
                    </div>

                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3 sm:gap-4 text-xs">
                        <button
                            class="bg-[#111111] hover:bg-[#181818] border border-white/5 rounded-2xl p-3 flex flex-col items-start gap-1">
                            <span class="text-sm font-semibold">Fashion & Wearables</span>
                            <span class="text-[11px] text-gray-400">Streetwear, Ankara, bags</span>
                        </button>
                        <button
                            class="bg-[#111111] hover:bg-[#181818] border border-white/5 rounded-2xl p-3 flex flex-col items-start gap-1">
                            <span class="text-sm font-semibold">Beauty & Care</span>
                            <span class="text-[11px] text-gray-400">Skincare, haircare</span>
                        </button>
                        <button
                            class="bg-[#111111] hover:bg-[#181818] border border-white/5 rounded-2xl p-3 flex flex-col items-start gap-1">
                            <span class="text-sm font-semibold">Electronics</span>
                            <span class="text-[11px] text-gray-400">Gadgets, accessories</span>
                        </button>
                        <button
                            class="bg-[#111111] hover:bg-[#181818] border border-white/5 rounded-2xl p-3 flex flex-col items-start gap-1">
                            <span class="text-sm font-semibold">Home & Living</span>
                            <span class="text-[11px] text-gray-400">Decor, kitchen, more</span>
                        </button>
                    </div>
                </div>
            </section>

            <!-- FEATURED BRANDS -->
            <section class="py-8 sm:py-10">
                <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-lg sm:text-xl font-semibold">Featured Nigerian brands</h2>
                        <a href="#" class="text-xs text-orange-400 hover:underline">See all brands</a>
                    </div>

                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-xs">
                        <div class="bg-[#111111] rounded-2xl p-4 border border-white/5 flex flex-col gap-2">
                            <p class="text-sm font-semibold">Lagos Streetwear Co.</p>
                            <p class="text-[11px] text-gray-400">Urban fashion from Lagos.</p>
                            <span class="mt-auto inline-flex items-center gap-1 text-[11px] text-orange-400">
                                View store →
                            </span>
                        </div>
                        <div class="bg-[#111111] rounded-2xl p-4 border border-white/5 flex flex-col gap-2">
                            <p class="text-sm font-semibold">Abuja Beauty Lab</p>
                            <p class="text-[11px] text-gray-400">Clean skincare, made in Nigeria.</p>
                            <span class="mt-auto inline-flex items-center gap-1 text-[11px] text-orange-400">
                                View store →
                            </span>
                        </div>
                        <div class="bg-[#111111] rounded-2xl p-4 border border-white/5 flex flex-col gap-2">
                            <p class="text-sm font-semibold">Naija Tech Hub</p>
                            <p class="text-[11px] text-gray-400">Gadgets & accessories.</p>
                            <span class="mt-auto inline-flex items-center gap-1 text-[11px] text-orange-400">
                                View store →
                            </span>
                        </div>
                        <div class="bg-[#111111] rounded-2xl p-4 border border-white/5 flex flex-col gap-2">
                            <p class="text-sm font-semibold">Abeokuta Crafts</p>
                            <p class="text-[11px] text-gray-400">Handmade home goods.</p>
                            <span class="mt-auto inline-flex items-center gap-1 text-[11px] text-orange-400">
                                View store →
                            </span>
                        </div>
                    </div>
                </div>
            </section>

            <!-- AVAILABLE PRODUCTS SECTION -->
            <section class="py-8 sm:py-10 border-t border-white/10">
                <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-lg sm:text-xl font-semibold">Available Products</h2>
                        <a href="marketplace.php" class="text-xs text-orange-400 hover:underline">View all</a>
                    </div>

                    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4 text-xs">

                        <!-- Product card 1 -->
                        <a href="product.php?id=1"
                            class="bg-[#111111] border border-white/10 hover:border-orange-500/70 rounded-2xl p-3 sm:p-4 flex flex-col gap-2">
                            <div class="aspect-[4/3] rounded-xl bg-gradient-to-br from-orange-500/60 to-pink-500/60
                            flex items-center justify-center text-[11px] font-semibold">
                                Lagos Streetwear Co.
                            </div>
                            <p class="text-sm font-semibold line-clamp-2">Ankara Panel Hoodie</p>
                            <p class="text-[11px] text-gray-400">Fashion</p>
                            <p class="text-sm font-semibold text-orange-400">₦18,500</p>
                            <button class="mt-auto text-[11px] px-2 py-1 rounded-full bg-white/5">
                                View product
                            </button>
                        </a>

                        <!-- Product card 2 -->
                        <a href="product.php?id=2"
                            class="bg-[#111111] border border-white/10 hover:border-orange-500/70 rounded-2xl p-3 sm:p-4 flex flex-col gap-2">
                            <div class="aspect-[4/3] rounded-xl bg-gradient-to-br from-pink-500/60 to-orange-500/60
                            flex items-center justify-center text-[11px] font-semibold">
                                Abuja Beauty Lab
                            </div>
                            <p class="text-sm font-semibold line-clamp-2">Shea Butter Glow Kit</p>
                            <p class="text-[11px] text-gray-400">Beauty</p>
                            <p class="text-sm font-semibold text-orange-400">₦9,900</p>
                            <button class="mt-auto text-[11px] px-2 py-1 rounded-full bg-white/5">
                                View product
                            </button>
                        </a>

                        <!-- Product card 3 -->
                        <a href="product.php?id=3"
                            class="bg-[#111111] border border-white/10 hover:border-orange-500/70 rounded-2xl p-3 sm:p-4 flex flex-col gap-2">
                            <div class="aspect-[4/3] rounded-xl bg-gradient-to-br from-blue-500/60 to-indigo-500/60
                            flex items-center justify-center text-[11px] font-semibold">
                                Naija Tech Hub
                            </div>
                            <p class="text-sm font-semibold line-clamp-2">Wireless Earbuds Pro</p>
                            <p class="text-[11px] text-gray-400">Electronics</p>
                            <p class="text-sm font-semibold text-orange-400">₦14,200</p>
                            <button class="mt-auto text-[11px] px-2 py-1 rounded-full bg-white/5">
                                View product
                            </button>
                        </a>

                        <!-- Product card 4 -->
                        <a href="product.php?id=4"
                            class="bg-[#111111] border border-white/10 hover:border-orange-500/70 rounded-2xl p-3 sm:p-4 flex flex-col gap-2">
                            <div class="aspect-[4/3] rounded-xl bg-gradient-to-br from-fuchsia-500/60 to-pink-500/60
                            flex items-center justify-center text-[11px] font-semibold">
                                Abeokuta Crafts
                            </div>
                            <p class="text-sm font-semibold line-clamp-2">Handwoven Throw Blanket</p>
                            <p class="text-[11px] text-gray-400">Home & Living</p>
                            <p class="text-sm font-semibold text-orange-400">₦11,000</p>
                            <button class="mt-auto text-[11px] px-2 py-1 rounded-full bg-white/5">
                                View product
                            </button>
                        </a>

                    </div>

                    <p class="mt-4 text-[11px] text-gray-400 text-center">
                        Showing 4 of 50+ products · <a href="marketplace.php"
                            class="text-orange-400 hover:underline">Explore more</a>
                    </p>

                </div>
            </section>


            <!-- SELLER CTA SECTION -->
            <section class="py-10 sm:py-12 bg-gradient-to-r from-[#1A1A1A] to-black border-t border-white/10">
                <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                    <p class="text-xs uppercase tracking-[0.2em] text-orange-400 mb-2">
                        For Nigerian brands
                    </p>
                    <h2 class="text-2xl sm:text-3xl font-semibold mb-3">
                        Sell on LocalTrade and reach customers across Nigeria.
                    </h2>
                    <p class="text-sm sm:text-base text-gray-300 mb-6">
                        Whether you’re a solo creator or a growing brand, LocalTrade gives you
                        secure payments, logistics partners, and tools to grow your business.
                    </p>
                    <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
                        <button class="px-6 py-2.5 rounded-full text-sm font-semibold"
                            style="background-color: var(--lt-orange);">
                            Start selling
                        </button>
                        <button class="px-6 py-2.5 rounded-full text-sm border border-white/20">
                            Learn how it works
                        </button>
                    </div>
                </div>
            </section>
        </main>

        <!-- FOOTER -->
        <footer class="border-t border-white/10 bg-black">
            <div
                class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-6 text-xs text-gray-400 flex flex-col sm:flex-row gap-3 sm:items-center sm:justify-between">
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
        // Year in footer
        document.getElementById('year').textContent = new Date().getFullYear();
    </script>
</body>

</html>