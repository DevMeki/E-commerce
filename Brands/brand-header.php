<?php
// Used across all brand (seller) pages inside /Brands
// Set $currentBrandPage in each file before including this:
// $currentBrandPage = 'dashboard' | 'products' | 'orders' | 'help';

$currentBrandPage = $currentBrandPage ?? '';
// Load root config if present
$brandConfigPath = __DIR__ . '/../config.php';
$config = file_exists($brandConfigPath) ? include $brandConfigPath : [];
?>

<header class="border-b border-white/10 bg-brand-forest sticky top-0 z-40 shadow-lg">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 items-center justify-between gap-3">
            <!-- LEFT: Logo -->
            <div class="flex items-center gap-4">
                <a href="brand-dashboard" class="flex items-center gap-2.5">
                    <div class="w-9 h-9 rounded-lg flex items-center justify-center shadow-md bg-brand-parchment">
                        <div class="w-4 h-3 border-2 border-brand-forest border-b-0 rounded-sm relative">
                            <span class="w-1 h-1 bg-brand-forest rounded-full absolute -bottom-1 left-0.5"></span>
                            <span class="w-1 h-1 bg-brand-forest rounded-full absolute -bottom-1 right-0.5"></span>
                        </div>
                    </div>
                    <div class="flex flex-col leading-none">
                        <span class="font-bold tracking-tight text-xl text-white">
                            LocalTrade
                        </span>
                        <span class="text-[10px] text-white/50 uppercase tracking-widest font-medium">
                            Brand Portal
                        </span>
                    </div>
                </a>
            </div>

            <!-- DESKTOP NAV -->
            <nav class="hidden md:flex items-center gap-6 text-sm">
                <!-- Dashboard -->
                <a href="brand-dashboard" class="<?= $currentBrandPage === 'dashboard'
                    ? 'text-white font-bold border-b-2 border-brand-orange py-1'
                    : 'text-white/70 hover:text-white font-medium transition-colors'; ?>">
                    Dashboard
                </a>

                <!-- Products -->
                <a href="products" class="<?= $currentBrandPage === 'products'
                    ? 'text-white font-bold border-b-2 border-brand-orange py-1'
                    : 'text-white/70 hover:text-white font-medium transition-colors'; ?>">
                    Products
                </a>

                <!-- Orders -->
                <a href="orders" class="<?= $currentBrandPage === 'orders'
                    ? 'text-white font-bold border-b-2 border-brand-orange py-1'
                    : 'text-white/70 hover:text-white font-medium transition-colors'; ?>">
                    Orders
                </a>

                <!-- Help -->
                <a href="brand-help" class="<?= $currentBrandPage === 'help'
                    ? 'text-white font-bold border-b-2 border-brand-orange py-1'
                    : 'text-white/40 hover:text-white font-medium transition-colors'; ?>">
                    Help
                </a>
            </nav>

            <!-- RIGHT: Brand chip + mobile button -->
            <div class="flex items-center gap-3 sm:gap-4">
                <!-- Brand avatar / name -->
                <?php
                $brandName = $_SESSION['user']['brand_name'] ?? 'My brand';
                $brandInitials = strtoupper(substr($brandName, 0, 1));
                ?>
                <a href="brand-dashboard" title="Profile"
                    class="hidden sm:inline-flex items-center gap-2 px-1 py-1 pr-3 rounded-full border border-white/10 hover:border-brand-orange/50 bg-white/5 transition-colors">
                    <div
                        class="w-8 h-8 rounded-full bg-gradient-to-br from-brand-orange to-orange-400 flex items-center justify-center text-xs font-bold text-white shadow-sm">
                        <?= $brandInitials; ?>
                    </div>
                    <span
                        class="text-xs font-medium text-white/90 max-w-[100px] truncate"><?= htmlspecialchars($brandName); ?></span>
                </a>

                <!-- Logout Trigger -->
                <button type="button" onclick="openLogoutModal()"
                    class="hidden sm:inline-flex items-center justify-center w-9 h-9 rounded-full text-white/60 hover:text-white hover:bg-white/10 transition-colors"
                    title="Sign out">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a2 2 0 01-2 2H6a2 2 0 01-2-2V7a2 2 0 012-2h5a2 2 0 012 2v1" />
                    </svg>
                </button>

                <!-- Mobile menu button -->
                <button type="button" id="brandMobileMenuButton"
                    class="flex md:hidden items-center justify-center w-10 h-10 rounded-full border border-white/10 text-white hover:bg-white/5 transition-colors"
                    aria-label="Toggle brand menu">
                    <span id="brandMobileMenuIconOpen" class="block text-lg">☰</span>
                    <span id="brandMobileMenuIconClose" class="hidden text-lg">✕</span>
                </button>
            </div>
        </div>

        <!-- MOBILE NAV -->
        <div id="brandMobileMenu" class="md:hidden hidden border-t border-white/5 mt-2 py-4 space-y-4">
            <nav class="flex flex-col gap-3 px-2">
                <a href="brand-dashboard" class="text-sm font-medium <?= $currentBrandPage === 'dashboard'
                    ? 'text-white font-bold'
                    : 'text-white/70'; ?>">
                    Dashboard
                </a>

                <a href="products" class="text-sm font-medium <?= $currentBrandPage === 'products'
                    ? 'text-white font-bold'
                    : 'text-white/70'; ?>">
                    Products
                </a>

                <a href="orders" class="text-sm font-medium <?= $currentBrandPage === 'orders'
                    ? 'text-white font-bold'
                    : 'text-white/70'; ?>">
                    Orders
                </a>

                <a href="brand-help" class="text-sm font-medium <?= $currentBrandPage === 'help'
                    ? 'text-white font-bold'
                    : 'text-white/40'; ?>">
                    Help & Support
                </a>

                <hr class="border-white/10 my-1">

                <button onclick="openLogoutModal()"
                    class="flex items-center gap-2 text-sm font-medium text-red-300 hover:text-red-200 py-1">
                    Sign out
                </button>
            </nav>
        </div>
    </div>
</header>

<!-- LOGOUT MODAL -->
<div id="logoutModal" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <!-- Backdrop -->
    <div class="fixed inset-0 bg-brand-ink/60 backdrop-blur-sm transition-opacity" onclick="closeLogoutModal()"></div>

    <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">

            <div
                class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-sm border border-brand-forest/5">
                <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div
                            class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-50 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                            <h3 class="text-base font-semibold leading-6 text-brand-ink" id="modal-title">Sign out?</h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">Are you sure you want to sign out of your brand
                                    dashboard?</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6 gap-2">
                    <form action="../process/logout" method="post" class="w-full sm:w-auto">
                        <button type="submit"
                            class="inline-flex w-full justify-center rounded-xl bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 sm:w-auto transition-colors">
                            Sign out
                        </button>
                    </form>
                    <button type="button" onclick="closeLogoutModal()"
                        class="mt-3 inline-flex w-full justify-center rounded-xl bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto transition-colors">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Mobile Menu Logic
    const btn = document.getElementById('brandMobileMenuButton');
    const menu = document.getElementById('brandMobileMenu');
    const iconO = document.getElementById('brandMobileMenuIconOpen');
    const iconC = document.getElementById('brandMobileMenuIconClose');

    if (btn && menu) {
        btn.addEventListener('click', () => {
            const isHidden = menu.classList.contains('hidden');
            if (isHidden) {
                menu.classList.remove('hidden');
                iconO.classList.add('hidden');
                iconC.classList.remove('hidden');
            } else {
                menu.classList.add('hidden');
                iconO.classList.remove('hidden');
                iconC.classList.add('hidden');
            }
        });
    }

    // Modal Logic
    const modal = document.getElementById('logoutModal');

    function openLogoutModal() {
        if (modal) modal.classList.remove('hidden');
    }

    function closeLogoutModal() {
        if (modal) modal.classList.add('hidden');
    }

    // Close on Escape key
    document.addEventListener('keydown', function (event) {
        if (event.key === "Escape" && !modal.classList.contains('hidden')) {
            closeLogoutModal();
        }
    });
</script>