<?php
// Page name passed from each file, e.g. $currentPage = 'home';
$currentPage = $currentPage ?? '';
// Load local config if present (returns array). Use `config.php` for DB credentials, env overrides.
$config = file_exists(__DIR__ . '/config.php') ? include __DIR__ . '/config.php' : [];
// Start session if not started and determine login state
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$isLoggedIn = !empty($_SESSION['user']);
// Optionally get user info
$currentUser = $_SESSION['user'] ?? null;
?>

<header class="border-b border-white/10 bg-black/60 backdrop-blur sticky top-0 z-40">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex h-14 items-center justify-between gap-3">

            <!-- LEFT: Logo + Desktop Nav -->
            <div class="flex items-center gap-6">

                <!-- Logo -->
                <a href="index" class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center"
                        style="background-color:#F36A1D;">
                        <div class="w-4 h-3 border-2 border-white border-b-0 rounded-sm relative">
                            <span class="w-1 h-1 bg-white rounded-full absolute -bottom-1 left-0.5"></span>
                            <span class="w-1 h-1 bg-white rounded-full absolute -bottom-1 right-0.5"></span>
                        </div>
                    </div>
                    <span class="font-semibold tracking-tight text-lg">
                        LocalTrade
                    </span>
                </a>
            </div>

            <!-- middle: Desktop Nav -->
            <div class="flex items-center gap-2 sm:gap-3">
                <!-- Desktop Navigation (Active Highlight Applied Here) -->
                <nav class="hidden md:flex items-center gap-5 text-xs sm:text-sm">

                    <!-- Home -->
                    <a href="index" class="<?= $currentPage === 'home'
                        ? 'text-orange-400 font-semibold'
                        : 'text-gray-300 hover:text-orange-400'; ?>">
                        Home
                    </a>

                    <!-- Marketplace -->
                    <a href="marketplace" class="<?= $currentPage === 'marketplace'
                        ? 'text-orange-400 font-semibold'
                        : 'text-gray-300 hover:text-orange-400'; ?>">
                        Marketplace
                    </a>

                    <!-- Categories -->
                    <a href="categories" class="<?= $currentPage === 'categories'
                        ? 'text-orange-400 font-semibold'
                        : 'text-gray-300 hover:text-orange-400'; ?>">
                        Categories
                    </a>

                    <!-- Brands -->
                    <a href="brands_page" class="<?= $currentPage === 'brands_page'
                        ? 'text-orange-400 font-semibold'
                        : 'text-gray-300 hover:text-orange-400'; ?>">
                        Brands
                    </a>

                    <!-- Help -->
                    <a href="help" class="<?= $currentPage === 'help'
                        ? 'text-orange-400 font-semibold'
                        : 'text-gray-400 hover:text-orange-400'; ?>">
                        Help
                    </a>
                </nav>
            </div>

            <!-- RIGHT: Cart + Login/Signup + Mobile menu -->
            <div class="flex items-center gap-2 sm:gap-3">

                <a href="cart"
                    class="relative flex items-center justify-center w-8 h-8 rounded-full bg-[#0B0B0B] border border-white/10 hover:border-orange-400">
                    <span class="text-xs">🛒</span>
                </a>

                <?php if ($isLoggedIn): ?>
                    <a href="account" title="Account"
                        class="hidden sm:inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-[#0B0B0B] border border-white/15 hover:border-orange-400 text-[11px] sm:text-xs">
                        <div class="w-8 h-8 rounded-full bg-[#0B0B0B] border border-white/10 flex items-center justify-center overflow-hidden">
                            <!-- user avatar (fallback to simple person SVG) -->
                            <?php if (!empty($currentUser['avatar'])): ?>
                                <img src="<?= htmlspecialchars($currentUser['avatar']) ?>" alt="user" class="w-full h-full object-cover" />
                            <?php else: ?>
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5.121 17.804A13.937 13.937 0 0112 15c2.5 0 4.847.607 6.879 1.804M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            <?php endif; ?>
                        </div>
                    </a>
                <?php else: ?>
                    <a href="login"
                        class="hidden sm:inline-flex items-center px-3 py-1.5 rounded-full text-[11px] sm:text-xs bg-[#0B0B0B] border border-white/20 hover:border-orange-400">
                        Login
                    </a>
                    <a href="signup"
                        class="hidden sm:inline-flex items-center px-3 py-1.5 rounded-full text-[11px] sm:text-xs font-semibold text-black"
                        style="background-color:#F36A1D;">
                        Sign up
                    </a>
                <?php endif; ?>

                <!-- Mobile menu button -->
                <button type="button" id="mobileMenuButton"
                    class="flex md:hidden items-center justify-center w-9 h-9 rounded-full bg-[#0B0B0B] border border-white/15">
                    <span id="mobileMenuIconOpen" class="block text-sm">☰</span>
                    <span id="mobileMenuIconClose" class="hidden text-sm">✕</span>
                </button>
            </div>
        </div>

        <!-- Mobile nav (Active Highlight Applied) -->
        <div id="mobileMenu" class="md:hidden hidden border-t border-white/10 mt-2 pt-3 pb-3 space-y-3">
            <nav class="flex flex-col gap-1 text-xs">
                <a href="index"
                    class="py-1.5 <?= $currentPage === 'home' ? 'text-orange-400 font-semibold' : 'text-gray-200'; ?>">
                    Home
                </a>
                <a href="marketplace"
                    class="py-1.5 <?= $currentPage === 'marketplace' ? 'text-orange-400 font-semibold' : 'text-gray-300'; ?>">
                    Marketplace
                </a>
                <a href="categories"
                    class="py-1.5 <?= $currentPage === 'categories' ? 'text-orange-400 font-semibold' : 'text-gray-300'; ?>">
                    Categories
                </a>
                <a href="brands_page"
                    class="py-1.5 <?= $currentPage === 'brands_page' ? 'text-orange-400 font-semibold' : 'text-gray-300'; ?>">
                    Brands
                </a>
                <a href="help"
                    class="py-1.5 <?= $currentPage === 'help' ? 'text-orange-400 font-semibold' : 'text-gray-400'; ?>">
                    Help / Support
                </a>
            </nav>
        </div>
    </div>
</header>
<?php include 'header-script.php'; ?>