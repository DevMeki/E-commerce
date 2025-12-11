<?php
// Used across all brand (seller) pages inside /Brands
// Set $currentBrandPage in each file before including this:
// $currentBrandPage = 'dashboard' | 'products' | 'orders' | 'help';

$currentBrandPage = $currentBrandPage ?? '';
?>

<header class="border-b border-white/10 bg-black/60 backdrop-blur sticky top-0 z-40">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex h-14 items-center justify-between gap-3">
            <!-- LEFT: Logo -->
            <a href="brand-dashboard.php" class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-full flex items-center justify-center"
                     style="background-color:#F36A1D;">
                    <div class="w-4 h-3 border-2 border-white border-b-0 rounded-sm relative">
                        <span class="w-1 h-1 bg-white rounded-full absolute -bottom-1 left-0.5"></span>
                        <span class="w-1 h-1 bg-white rounded-full absolute -bottom-1 right-0.5"></span>
                    </div>
                </div>
                <span class="font-semibold tracking-tight text-lg">LocalTrade</span>
                <span class="ml-1 text-[11px] text-gray-500 hidden sm:inline">
                    Brand
                </span>
            </a>

            <!-- DESKTOP NAV -->
            <nav class="hidden md:flex items-center gap-5 text-xs sm:text-sm">
                <a href="brand-dashboard.php"
                   class="<?= $currentBrandPage === 'dashboard'
                       ? 'text-orange-400 font-semibold'
                       : 'text-gray-300 hover:text-orange-400'; ?>">
                    Dashboard
                </a>

                <a href="products.php"
                   class="<?= $currentBrandPage === 'products'
                       ? 'text-orange-400 font-semibold'
                       : 'text-gray-300 hover:text-orange-400'; ?>">
                    Products
                </a>

                <a href="orders.php"
                   class="<?= $currentBrandPage === 'orders'
                       ? 'text-orange-400 font-semibold'
                       : 'text-gray-300 hover:text-orange-400'; ?>">
                    Orders
                </a>

                <a href="brand-help.php"
                   class="<?= $currentBrandPage === 'help'
                       ? 'text-orange-400 font-semibold'
                       : 'text-gray-300 hover:text-orange-400'; ?>">
                    Help
                </a>
            </nav>

            <!-- RIGHT: Brand chip + mobile button -->
            <div class="flex items-center gap-2 sm:gap-3">
                <!-- Brand avatar / name (placeholder) -->
                <a href="brand-dashboard.php"
                   class="hidden sm:inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-[#0B0B0B] border border-white/15 hover:border-orange-400 text-[11px] sm:text-xs">
                    <div class="w-6 h-6 rounded-full bg-gradient-to-br from-orange-500 to-yellow-400 flex items-center justify-center text-[11px] font-semibold">
                        B
                    </div>
                    <span>My brand</span>
                </a>

                <!-- Mobile menu button -->
                <button
                    type="button"
                    id="brandMobileMenuButton"
                    class="flex md:hidden items-center justify-center w-9 h-9 rounded-full bg-[#0B0B0B] border border-white/15"
                    aria-label="Toggle brand menu"
                >
                    <span id="brandMobileMenuIconOpen" class="block text-sm">☰</span>
                    <span id="brandMobileMenuIconClose" class="hidden text-sm">✕</span>
                </button>
            </div>
        </div>

        <!-- MOBILE NAV -->
        <div id="brandMobileMenu" class="md:hidden hidden border-t border-white/10 mt-2 pt-2 pb-3 space-y-2">
            <nav class="flex flex-col gap-1 text-xs">
                <a href="brand-dashboard.php"
                   class="py-1.5 <?= $currentBrandPage === 'dashboard'
                       ? 'text-orange-400 font-semibold'
                       : 'text-gray-300'; ?>">
                    Dashboard
                </a>

                <a href="products.php"
                   class="py-1.5 <?= $currentBrandPage === 'products'
                       ? 'text-orange-400 font-semibold'
                       : 'text-gray-300'; ?>">
                    Products
                </a>

                <a href="orders.php"
                   class="py-1.5 <?= $currentBrandPage === 'orders'
                       ? 'text-orange-400 font-semibold'
                       : 'text-gray-300'; ?>">
                    Orders
                </a>

                <a href="brand-help.php"
                   class="py-1.5 <?= $currentBrandPage === 'help'
                       ? 'text-orange-400 font-semibold'
                       : 'text-gray-300'; ?>">
                    Help & Support
                </a>

                <a href="../logout.php"
                   class="pt-1 py-1.5 text-red-300 hover:text-red-400">
                    Logout
                </a>
            </nav>
        </div>
    </div>
</header>

<!-- Brand mobile menu JS (can live here once) -->
<script defer>
const brandMobileMenuButton = document.getElementById('brandMobileMenuButton');
const brandMobileMenu       = document.getElementById('brandMobileMenu');
const brandIconOpen         = document.getElementById('brandMobileMenuIconOpen');
const brandIconClose        = document.getElementById('brandMobileMenuIconClose');

if (brandMobileMenuButton && brandMobileMenu && brandIconOpen && brandIconClose) {
    brandMobileMenuButton.addEventListener('click', () => {
        const isOpen = !brandMobileMenu.classList.contains('hidden');
        if (isOpen) {
            brandMobileMenu.classList.add('hidden');
            brandIconOpen.classList.remove('hidden');
            brandIconClose.classList.add('hidden');
        } else {
            brandMobileMenu.classList.remove('hidden');
            brandIconOpen.classList.add('hidden');
            brandIconClose.classList.remove('hidden');
        }
    });
}
</script>
