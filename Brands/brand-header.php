<?php
// Used across all brand (seller) pages inside /Brands
// Set $currentBrandPage in each file before including this:
// $currentBrandPage = 'dashboard' | 'products' | 'orders' | 'help';

$currentBrandPage = $currentBrandPage ?? '';
// Load root config if present
$brandConfigPath = __DIR__ . '/../config.php';
$config = file_exists($brandConfigPath) ? include $brandConfigPath : [];
?>

<header class="border-b border-white/10 bg-black/60 backdrop-blur sticky top-0 z-40">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex h-14 items-center justify-between gap-3">
            <!-- LEFT: Logo -->
            <a href="brand-dashboard" class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-full flex items-center justify-center" style="background-color:#F36A1D;">
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
                <a href="brand-dashboard" class="<?= $currentBrandPage === 'dashboard'
                    ? 'text-orange-400 font-semibold'
                    : 'text-gray-300 hover:text-orange-400'; ?>">
                    Dashboard
                </a>

                <a href="products" class="<?= $currentBrandPage === 'products'
                    ? 'text-orange-400 font-semibold'
                    : 'text-gray-300 hover:text-orange-400'; ?>">
                    Products
                </a>

                <a href="orders" class="<?= $currentBrandPage === 'orders'
                    ? 'text-orange-400 font-semibold'
                    : 'text-gray-300 hover:text-orange-400'; ?>">
                    Orders
                </a>

                <a href="brand-help" class="<?= $currentBrandPage === 'help'
                    ? 'text-orange-400 font-semibold'
                    : 'text-gray-300 hover:text-orange-400'; ?>">
                    Help
                </a>
            </nav>

            <!-- RIGHT: Brand chip + mobile button -->
            <div class="flex items-center gap-2 sm:gap-3">
                <!-- Brand avatar / name (placeholder) -->
                <a href="brand-dashboard"
                    class="hidden sm:inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-[#0B0B0B] border border-white/15 hover:border-orange-400 text-[11px] sm:text-xs">
                    <div
                        class="w-6 h-6 rounded-full bg-gradient-to-br from-orange-500 to-yellow-400 flex items-center justify-center text-[11px] font-semibold">
                        B
                    </div>
                    <span>My brand</span>
                </a>

                <form action="../process/logout" method="post" class="hidden sm:inline-flex" aria-label="Logout form">
                    <button type="submit"
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-full text-sm font-semibold bg-red-600 text-white hover:bg-red-500 focus:outline-none focus:ring-2 focus:ring-red-400"
                        aria-describedby="logoutDesc">
                        <!-- logout icon -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a2 2 0 01-2 2H6a2 2 0 01-2-2V7a2 2 0 012-2h5a2 2 0 012 2v1" />
                        </svg>
                        <span>Logout</span>
                    </button>
                    <p id="logoutDesc" class="sr-only">This will sign you out of your account and return you to the
                        homepage.</p>
                </form>

                <!-- Mobile menu button -->
                <button type="button" id="brandMobileMenuButton"
                    class="flex md:hidden items-center justify-center w-9 h-9 rounded-full bg-[#0B0B0B] border border-white/15"
                    aria-label="Toggle brand menu">
                    <span id="brandMobileMenuIconOpen" class="block text-sm">☰</span>
                    <span id="brandMobileMenuIconClose" class="hidden text-sm">✕</span>
                </button>
            </div>
        </div>

        <!-- MOBILE NAV -->
        <div id="brandMobileMenu" class="md:hidden hidden border-t border-white/10 mt-2 pt-2 pb-3 space-y-2">
            <nav class="flex flex-col gap-1 text-xs">
                <a href="brand-dashboard" class="py-1.5 <?= $currentBrandPage === 'dashboard'
                    ? 'text-orange-400 font-semibold'
                    : 'text-gray-300'; ?>">
                    Dashboard
                </a>

                <a href="products" class="py-1.5 <?= $currentBrandPage === 'products'
                    ? 'text-orange-400 font-semibold'
                    : 'text-gray-300'; ?>">
                    Products
                </a>

                <a href="orders" class="py-1.5 <?= $currentBrandPage === 'orders'
                    ? 'text-orange-400 font-semibold'
                    : 'text-gray-300'; ?>">
                    Orders
                </a>

                <a href="brand-help" class="py-1.5 <?= $currentBrandPage === 'help'
                    ? 'text-orange-400 font-semibold'
                    : 'text-gray-300'; ?>">
                    Help & Support
                </a>

                <form action="../process/logout" method="post" class="inline-block" aria-label="Logout form">
                    <button type="submit"
                        class="inline-flex items-center gap-2 py-2 text-sm font-semibold text-red-600 hover:text-white"
                        aria-describedby="logoutDesc">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a2 2 0 01-2 2H6a2 2 0 01-2-2V7a2 2 0 012-2h5a2 2 0 012 2v1" />
                        </svg>
                        <span>Logout</span>
                    </button>
                    <p id="logoutDesc" class="sr-only">This will sign you out of your account and return you to the
                        homepage.</p>
                </form>
            </nav>
        </div>
    </div>
</header>

<!-- Brand mobile menu JS (can live here once) -->
<script defer>
    const brandMobileMenuButton = document.getElementById('brandMobileMenuButton');
    const brandMobileMenu = document.getElementById('brandMobileMenu');
    const brandIconOpen = document.getElementById('brandMobileMenuIconOpen');
    const brandIconClose = document.getElementById('brandMobileMenuIconClose');

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