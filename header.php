<?php
// Page name passed from each file, e.g. $currentPage = 'home';
$currentPage = $currentPage ?? '';
?>

<header class="border-b border-white/10 bg-black/60 backdrop-blur sticky top-0 z-40">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex h-14 items-center justify-between gap-3">

            <!-- LEFT: Logo + Desktop Nav -->
            <div class="flex items-center gap-6">

                <!-- Logo -->
                <a href="index.php" class="flex items-center gap-2">
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
                    <a href="index.php" class="<?= $currentPage === 'home'
                        ? 'text-orange-400 font-semibold'
                        : 'text-gray-300 hover:text-orange-400'; ?>">
                        Home
                    </a>

                    <!-- Marketplace -->
                    <a href="marketplace.php" class="<?= $currentPage === 'marketplace'
                        ? 'text-orange-400 font-semibold'
                        : 'text-gray-300 hover:text-orange-400'; ?>">
                        Marketplace
                    </a>

                    <!-- Categories -->
                    <a href="categories.php" class="<?= $currentPage === 'categories'
                        ? 'text-orange-400 font-semibold'
                        : 'text-gray-300 hover:text-orange-400'; ?>">
                        Categories
                    </a>

                    <!-- Brands -->
                    <a href="brands.php" class="<?= $currentPage === 'brands'
                        ? 'text-orange-400 font-semibold'
                        : 'text-gray-300 hover:text-orange-400'; ?>">
                        Brands
                    </a>

                    <!-- Help -->
                    <a href="help.php" class="<?= $currentPage === 'help'
                        ? 'text-orange-400 font-semibold'
                        : 'text-gray-400 hover:text-orange-400'; ?>">
                        Help
                    </a>
                </nav>
            </div>

            <!-- RIGHT: Cart + Login/Signup + Mobile menu -->
            <div class="flex items-center gap-2 sm:gap-3">

                <a href="cart.php"
                    class="relative flex items-center justify-center w-8 h-8 rounded-full bg-[#0B0B0B] border border-white/10 hover:border-orange-400">
                    <span class="text-xs">🛒</span>
                </a>

                <?php $isLoggedIn = false; ?>

                <?php if ($isLoggedIn): ?>
                    <a href="account.php"
                        class="hidden xs:flex items-center gap-2 px-3 py-1.5 rounded-full bg-[#0B0B0B] border border-white/15 hover:border-orange-400 text-[11px] sm:text-xs">
                        <div
                            class="w-6 h-6 rounded-full bg-gradient-to-br from-orange-500 to-yellow-400 flex items-center justify-center text-[11px] font-semibold">
                            U
                        </div>
                        <span>Account</span>
                    </a>
                <?php else: ?>
                    <a href="login.php"
                        class="hidden sm:inline-flex items-center px-3 py-1.5 rounded-full text-[11px] sm:text-xs bg-[#0B0B0B] border border-white/20 hover:border-orange-400">
                        Login
                    </a>
                    <a href="signup.php"
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
                <a href="index.php"
                    class="py-1.5 <?= $currentPage === 'home' ? 'text-orange-400 font-semibold' : 'text-gray-200'; ?>">
                    Home
                </a>
                <a href="marketplace.php"
                    class="py-1.5 <?= $currentPage === 'marketplace' ? 'text-orange-400 font-semibold' : 'text-gray-300'; ?>">
                    Marketplace
                </a>
                <a href="categories.php"
                    class="py-1.5 <?= $currentPage === 'categories' ? 'text-orange-400 font-semibold' : 'text-gray-300'; ?>">
                    Categories
                </a>
                <a href="brands.php"
                    class="py-1.5 <?= $currentPage === 'brands' ? 'text-orange-400 font-semibold' : 'text-gray-300'; ?>">
                    Brands
                </a>
                <a href="help.php"
                    class="py-1.5 <?= $currentPage === 'help' ? 'text-orange-400 font-semibold' : 'text-gray-400'; ?>">
                    Help / Support
                </a>
            </nav>
        </div>
    </div>
</header>
<?php include 'header-script.php'; ?>