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

<header class="border-b border-white/10 bg-brand-forest sticky top-0 z-40 shadow-lg">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 items-center justify-between gap-3">

            <!-- LEFT: Logo + Desktop Nav -->
            <div class="flex items-center gap-8">

                <!-- Logo -->
                <a href="index" class="flex items-center gap-2.5">
                    <div class="w-9 h-9 rounded-lg flex items-center justify-center shadow-md bg-brand-parchment">
                        <div class="w-4 h-3 border-2 border-brand-forest border-b-0 rounded-sm relative">
                            <span class="w-1 h-1 bg-brand-forest rounded-full absolute -bottom-1 left-0.5"></span>
                            <span class="w-1 h-1 bg-brand-forest rounded-full absolute -bottom-1 right-0.5"></span>
                        </div>
                    </div>
                    <span class="font-bold tracking-tight text-xl text-white">
                        LocalTrade
                    </span>
                </a>
            </div>

            <!-- middle: Desktop Nav -->
            <div class="flex items-center gap-2 sm:gap-3">
                <!-- Desktop Navigation (Active Highlight Applied Here) -->
                <nav class="hidden md:flex items-center gap-6 text-sm">

                    <!-- Home -->
                    <a href="index" class="<?= $currentPage === 'home'
                        ? 'text-white font-bold border-b-2 border-brand-orange py-1'
                        : 'text-white/70 hover:text-white font-medium transition-colors'; ?>">
                        Home
                    </a>

                    <!-- Marketplace -->
                    <a href="marketplace" class="<?= $currentPage === 'marketplace'
                        ? 'text-white font-bold border-b-2 border-brand-orange py-1'
                        : 'text-white/70 hover:text-white font-medium transition-colors'; ?>">
                        Marketplace
                    </a>

                    <!-- Categories -->
                    <a href="categories" class="<?= $currentPage === 'categories'
                        ? 'text-white font-bold border-b-2 border-brand-orange py-1'
                        : 'text-white/70 hover:text-white font-medium transition-colors'; ?>">
                        Categories
                    </a>

                    <!-- Brands -->
                    <a href="brands_page" class="<?= $currentPage === 'brands_page'
                        ? 'text-white font-bold border-b-2 border-brand-orange py-1'
                        : 'text-white/70 hover:text-white font-medium transition-colors'; ?>">
                        Brands
                    </a>

                    <!-- Help -->
                    <a href="help" class="<?= $currentPage === 'help'
                        ? 'text-white font-bold border-b-2 border-brand-orange py-1'
                        : 'text-white/40 hover:text-white font-medium transition-colors'; ?>">
                        Help
                    </a>
                </nav>
            </div>

            <!-- RIGHT: Cart + Login/Signup + Mobile menu -->
            <div class="flex items-center gap-3 sm:gap-4">

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

                <?php if ($isLoggedIn): ?>
                    <a href="account" title="Account"
                        class="hidden sm:inline-flex items-center gap-2 px-1 py-1 rounded-full border border-white/10 hover:border-white transition-colors">
                        <div class="w-8 h-8 rounded-full bg-white/10 flex items-center justify-center overflow-hidden">
                            <!-- user avatar (fallback to simple person SVG) -->
                            <?php if (!empty($currentUser['avatar'])): ?>
                                <img src="<?= htmlspecialchars($currentUser['avatar']) ?>" alt="user"
                                    class="w-full h-full object-cover" />
                            <?php else: ?>
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-white" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5.121 17.804A13.937 13.937 0 0112 15c2.5 0 4.847.607 6.879 1.804M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            <?php endif; ?>
                        </div>
                    </a>
                <?php else: ?>
                    <a href="login"
                        class="hidden sm:inline-flex items-center px-4 py-2 rounded-full text-xs font-bold text-white/80 hover:text-white transition-colors">
                        Login
                    </a>
                    <a href="signup"
                        class="hidden sm:inline-flex items-center px-5 py-2 rounded-full text-xs font-bold text-white shadow-md shadow-brand-orange/20 transition-all hover:scale-[1.02]"
                        style="background-color: var(--lt-orange);">
                        Sign up
                    </a>
                <?php endif; ?>

                <!-- Mobile menu button -->
                <button type="button" id="mobileMenuButton"
                    class="flex md:hidden items-center justify-center w-10 h-10 rounded-full border border-white/10 text-white">
                    <span id="mobileMenuIconOpen" class="block text-lg">☰</span>
                    <span id="mobileMenuIconClose" class="hidden text-lg">✕</span>
                </button>
            </div>
        </div>

        <!-- Mobile nav (Active Highlight Applied) -->
        <div id="mobileMenu" class="md:hidden hidden border-t border-white/5 mt-2 py-4 space-y-4">
            <nav class="flex flex-col gap-3 px-2">
                <a href="index"
                    class="text-sm font-medium <?= $currentPage === 'home' ? 'text-white font-bold' : 'text-white/70'; ?>">
                    Home
                </a>
                <a href="marketplace"
                    class="text-sm font-medium <?= $currentPage === 'marketplace' ? 'text-white font-bold' : 'text-white/70'; ?>">
                    Marketplace
                </a>
                <a href="categories"
                    class="text-sm font-medium <?= $currentPage === 'categories' ? 'text-white font-bold' : 'text-white/70'; ?>">
                    Categories
                </a>
                <a href="brands_page"
                    class="text-sm font-medium <?= $currentPage === 'brands_page' ? 'text-white font-bold' : 'text-white/70'; ?>">
                    Brands
                </a>
                <a href="help"
                    class="text-sm font-medium <?= $currentPage === 'help' ? 'text-white font-bold' : 'text-white/40'; ?>">
                    Help / Support
                </a>
            </nav>
        </div>
    </div>
</header>
<?php include 'header-script.php'; ?>