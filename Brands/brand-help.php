<?php
// Seller Help page
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Seller Help & Support | LocalTrade</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        :root {
            --lt-orange: #F36A1D;
            --lt-black: #0D0D0D;
        }
    </style>
</head>

<body class="bg-[#0D0D0D] text-white min-h-screen flex flex-col">

    <!-- BRAND HEADER -->
    <?php
    $currentBrandPage = 'help';
    include 'brand-header.php';
    ?>

    <!-- MAIN -->
    <main class="flex-1 py-6 sm:py-8">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6 sm:space-y-8">

            <!-- HERO -->
            <section class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <p class="text-[11px] uppercase tracking-[0.15em] text-orange-400">
                        Seller help
                    </p>
                    <h1 class="text-2xl sm:text-3xl font-semibold tracking-tight">
                        Get help running your LocalTrade store
                    </h1>
                    <p class="text-xs sm:text-sm text-gray-400 mt-1 max-w-xl">
                        From payouts and product approval to shipping and store policies, this space is for brands
                        selling on LocalTrade.
                    </p>
                </div>
                <div
                    class="bg-[#111111] border border-white/10 rounded-2xl px-4 py-3 text-[11px] sm:text-xs text-gray-300">
                    <p class="font-semibold text-gray-100 mb-1">Need urgent help?</p>
                    <p class="mb-2 text-gray-400">
                        Email: <span class="text-gray-200">seller-support@localtrade.ng</span>
                    </p>
                    <p class="text-gray-500">
                        Response time: typically within <span class="text-gray-300">1–4 business hours</span>.
                    </p>
                </div>
            </section>

            <!-- TOPIC CARDS -->
            <section class="space-y-3">
                <h2 class="text-sm sm:text-base font-semibold">Seller help topics</h2>
                <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 text-xs sm:text-sm">
                    <!-- Getting started -->
                    <a href="#setup-help"
                        class="bg-[#111111] border border-white/10 rounded-2xl p-3 sm:p-4 hover:border-orange-400 transition-colors flex flex-col gap-2">
                        <div class="flex items-center justify-between">
                            <span class="text-gray-100 font-semibold">Get started</span>
                            <span>🚀</span>
                        </div>
                        <p class="text-[11px] text-gray-400">
                            Set up your brand profile, logo, store details and policies.
                        </p>
                        <span class="mt-auto text-[11px] text-orange-400">View answers →</span>
                    </a>

                    <!-- Product approval -->
                    <a href="#products-help"
                        class="bg-[#111111] border border-white/10 rounded-2xl p-3 sm:p-4 hover:border-orange-400 transition-colors flex flex-col gap-2">
                        <div class="flex items-center justify-between">
                            <span class="text-gray-100 font-semibold">Product approval</span>
                            <span>✅</span>
                        </div>
                        <p class="text-[11px] text-gray-400">
                            Learn how product review works and why listings get rejected.
                        </p>
                        <span class="mt-auto text-[11px] text-orange-400">View answers →</span>
                    </a>

                    <!-- Payouts -->
                    <a href="#payouts-help"
                        class="bg-[#111111] border border-white/10 rounded-2xl p-3 sm:p-4 hover:border-orange-400 transition-colors flex flex-col gap-2">
                        <div class="flex items-center justify-between">
                            <span class="text-gray-100 font-semibold">Payouts & earnings</span>
                            <span>💸</span>
                        </div>
                        <p class="text-[11px] text-gray-400">
                            Settlement timelines, payment cycles and how much you actually receive.
                        </p>
                        <span class="mt-auto text-[11px] text-orange-400">View answers →</span>
                    </a>

                    <!-- Shipping & returns -->
                    <a href="#shipping-help"
                        class="bg-[#111111] border border-white/10 rounded-2xl p-3 sm:p-4 hover:border-orange-400 transition-colors flex flex-col gap-2">
                        <div class="flex items-center justify-between">
                            <span class="text-gray-100 font-semibold">Shipping & returns</span>
                            <span>📦</span>
                        </div>
                        <p class="text-[11px] text-gray-400">
                            Handling delivery, returns, and keeping your ratings healthy.
                        </p>
                        <span class="mt-auto text-[11px] text-orange-400">View answers →</span>
                    </a>
                </div>
            </section>

            <!-- FAQ GRID -->
            <section class="grid lg:grid-cols-2 gap-5 lg:gap-6 text-xs sm:text-sm">

                <!-- Getting started / Store setup -->
                <div id="setup-help" class="bg-[#111111] border border-white/10 rounded-2xl p-4 sm:p-5">
                    <h2 class="text-sm font-semibold mb-3 flex items-center justify-between">
                        Getting started & store setup
                        <span class="text-[11px] text-gray-500">Brand basics</span>
                    </h2>

                    <div class="divide-y divide-white/10">
                        <!-- Q1 -->
                        <button type="button"
                            class="seller-faq-toggle w-full text-left py-3 flex items-center justify-between gap-3">
                            <span class="text-gray-100">What do I need before opening a LocalTrade store?</span>
                            <span class="text-gray-500 text-xs">+</span>
                        </button>
                        <div class="seller-faq-content hidden pb-3 text-[11px] text-gray-400">
                            You’ll need your brand name, logo, a short description, contact details, and at least one
                            product you’re ready to sell. If you have a registered business, you can also add your
                            BN/RC number in your brand profile.
                        </div>

                        <!-- Q2 -->
                        <button type="button"
                            class="seller-faq-toggle w-full text-left py-3 flex items-center justify-between gap-3">
                            <span class="text-gray-100">How do I set my store policies?</span>
                            <span class="text-gray-500 text-xs">+</span>
                        </button>
                        <div class="seller-faq-content hidden pb-3 text-[11px] text-gray-400">
                            During seller onboarding, you can define your handling time, return window, and basic rules
                            for refunds or exchanges. Keep it clear and realistic—customers see these policies on your
                            brand
                            page and product listings.
                        </div>

                        <!-- Q3 -->
                        <button type="button"
                            class="seller-faq-toggle w-full text-left py-3 flex items-center justify-between gap-3">
                            <span class="text-gray-100">Can I update my logo and brand info later?</span>
                            <span class="text-gray-500 text-xs">+</span>
                        </button>
                        <div class="seller-faq-content hidden pb-3 text-[11px] text-gray-400">
                            Yes. You can edit your logo, description, social links and other details from your
                            <strong>Brand settings</strong> page at any time. Major changes may be reviewed by
                            LocalTrade.
                        </div>
                    </div>
                </div>

                <!-- Product approval -->
                <div id="products-help" class="bg-[#111111] border border-white/10 rounded-2xl p-4 sm:p-5">
                    <h2 class="text-sm font-semibold mb-3 flex items-center justify-between">
                        Product approval & listing quality
                        <span class="text-[11px] text-gray-500">Listings</span>
                    </h2>

                    <div class="divide-y divide-white/10">
                        <!-- Q1 -->
                        <button type="button"
                            class="seller-faq-toggle w-full text-left py-3 flex items-center justify-between gap-3">
                            <span class="text-gray-100">Why do my products need approval?</span>
                            <span class="text-gray-500 text-xs">+</span>
                        </button>
                        <div class="seller-faq-content hidden pb-3 text-[11px] text-gray-400">
                            LocalTrade reviews new listings to protect buyers and keep the marketplace clean.
                            We check for prohibited items, misleading content, poor-quality images, and missing
                            information
                            like pricing or sizing.
                        </div>

                        <!-- Q2 -->
                        <button type="button"
                            class="seller-faq-toggle w-full text-left py-3 flex items-center justify-between gap-3">
                            <span class="text-gray-100">How long does product review take?</span>
                            <span class="text-gray-500 text-xs">+</span>
                        </button>
                        <div class="seller-faq-content hidden pb-3 text-[11px] text-gray-400">
                            Most products are reviewed within <strong>24 hours</strong> on business days.
                            During very busy periods, it might take slightly longer, but you can still edit drafts while
                            you wait for approval.
                        </div>

                        <!-- Q3 -->
                        <button type="button"
                            class="seller-faq-toggle w-full text-left py-3 flex items-center justify-between gap-3">
                            <span class="text-gray-100">My product was rejected. What should I do?</span>
                            <span class="text-gray-500 text-xs">+</span>
                        </button>
                        <div class="seller-faq-content hidden pb-3 text-[11px] text-gray-400">
                            Check the rejection reason in your product details. It might be due to unclear images,
                            missing details, or a policy violation.
                            Fix the highlighted issues, save changes and resubmit. If you’re unsure, contact seller
                            support
                            with the product ID.
                        </div>
                    </div>
                </div>

                <!-- Payouts & earnings -->
                <div id="payouts-help" class="bg-[#111111] border border-white/10 rounded-2xl p-4 sm:p-5">
                    <h2 class="text-sm font-semibold mb-3 flex items-center justify-between">
                        Payouts & earnings
                        <span class="text-[11px] text-gray-500">Money</span>
                    </h2>

                    <div class="divide-y divide-white/10">
                        <!-- Q1 -->
                        <button type="button"
                            class="seller-faq-toggle w-full text-left py-3 flex items-center justify-between gap-3">
                            <span class="text-gray-100">When do I get paid after an order?</span>
                            <span class="text-gray-500 text-xs">+</span>
                        </button>
                        <div class="seller-faq-content hidden pb-3 text-[11px] text-gray-400">
                            Payouts are usually released after the order is marked as <strong>delivered</strong> and the
                            buyer’s return window has passed.
                            Depending on your payout schedule, funds might land weekly or on a custom cycle defined in
                            your
                            seller settings.
                        </div>

                        <!-- Q2 -->
                        <button type="button"
                            class="seller-faq-toggle w-full text-left py-3 flex items-center justify-between gap-3">
                            <span class="text-gray-100">How are LocalTrade fees calculated?</span>
                            <span class="text-gray-500 text-xs">+</span>
                        </button>
                        <div class="seller-faq-content hidden pb-3 text-[11px] text-gray-400">
                            For each order, we deduct the platform fee and payment processing charge before sending your
                            payout.
                            You’ll see a breakdown per order in your <strong>Earnings</strong> or
                            <strong>Payout</strong> view.
                        </div>

                        <!-- Q3 -->
                        <button type="button"
                            class="seller-faq-toggle w-full text-left py-3 flex items-center justify-between gap-3">
                            <span class="text-gray-100">My payout is delayed or missing.</span>
                            <span class="text-gray-500 text-xs">+</span>
                        </button>
                        <div class="seller-faq-content hidden pb-3 text-[11px] text-gray-400">
                            First, confirm that your bank details are correct and verified in your payout settings.
                            Then check if the orders are already in “delivered” status and past the return window.
                            If everything looks fine, contact seller support with your brand name and the payout period.
                        </div>
                    </div>
                </div>

                <!-- Shipping, logistics & returns -->
                <div id="shipping-help" class="bg-[#111111] border border-white/10 rounded-2xl p-4 sm:p-5">
                    <h2 class="text-sm font-semibold mb-3 flex items-center justify-between">
                        Shipping, logistics & returns
                        <span class="text-[11px] text-gray-500">Operations</span>
                    </h2>

                    <div class="divide-y divide-white/10">
                        <!-- Q1 -->
                        <button type="button"
                            class="seller-faq-toggle w-full text-left py-3 flex items-center justify-between gap-3">
                            <span class="text-gray-100">Who handles delivery—the brand or LocalTrade?</span>
                            <span class="text-gray-500 text-xs">+</span>
                        </button>
                        <div class="seller-faq-content hidden pb-3 text-[11px] text-gray-400">
                            This depends on your configuration.
                            Some brands handle their own couriers, while others plug into LocalTrade’s logistics
                            partners.
                            Your setup is defined during onboarding and can be adjusted in your shipping settings.
                        </div>

                        <!-- Q2 -->
                        <button type="button"
                            class="seller-faq-toggle w-full text-left py-3 flex items-center justify-between gap-3">
                            <span class="text-gray-100">What is expected response time for orders?</span>
                            <span class="text-gray-500 text-xs">+</span>
                        </button>
                        <div class="seller-faq-content hidden pb-3 text-[11px] text-gray-400">
                            We encourage sellers to confirm and ship orders within their stated processing time,
                            usually <strong>1–3 business days</strong>.
                            Slow responses can impact your seller rating and visibility in the marketplace.
                        </div>

                        <!-- Q3 -->
                        <button type="button"
                            class="seller-faq-toggle w-full text-left py-3 flex items-center justify-between gap-3">
                            <span class="text-gray-100">How do returns work for sellers?</span>
                            <span class="text-gray-500 text-xs">+</span>
                        </button>
                        <div class="seller-faq-content hidden pb-3 text-[11px] text-gray-400">
                            When a buyer requests a return, you’ll receive a notification with details and photos (if
                            provided).
                            You can approve or dispute the request in line with your store policy and LocalTrade
                            guidelines.
                            Once a return is approved and processed, the payout for that order may be adjusted
                            accordingly.
                        </div>
                    </div>
                </div>
            </section>

            <!-- STILL STUCK -->
            <section class="bg-[#111111] border border-white/10 rounded-2xl p-4 sm:p-5 text-xs sm:text-sm">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div>
                        <h2 class="text-sm font-semibold">Still stuck with something?</h2>
                        <p class="text-[11px] text-gray-400 mt-1 max-w-md">
                            Share your brand name, affected order or product ID, and a short description of the issue.
                            Our seller support team will get back to you as quickly as possible.
                        </p>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        <a href="mailto:seller-support@localtrade.ng"
                            class="inline-flex items-center gap-2 px-3 py-2 rounded-full bg-[#0B0B0B] border border-white/20 hover:border-orange-400 text-[11px] sm:text-xs">
                            <span>Email seller support</span>
                            <span>✉️</span>
                        </a>
                        <a href="#"
                            class="inline-flex items-center gap-2 px-3 py-2 rounded-full text-[11px] sm:text-xs font-semibold text-black"
                            style="background-color: var(--lt-orange);">
                            <span>Open support ticket (coming soon)</span>
                        </a>
                    </div>
                </div>
            </section>
        </div>
    </main>

    <footer class="border-t border-white/10">
        <div
            class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex flex-col sm:flex-row items-center justify-between gap-2 text-[11px] text-gray-500">
            <span>© <?= date('Y'); ?> LocalTrade · Seller dashboard</span>
            <div class="flex gap-3">
                <a href="#" class="hover:text-orange-400">Seller terms</a>
                <a href="#" class="hover:text-orange-400">Policy & compliance</a>
            </div>
        </div>
    </footer>

    <!-- Mobile menu toggle -->
    <script>
        const mobileMenuButton = document.getElementById('mobileMenuButton');
        const mobileMenu = document.getElementById('mobileMenu');
        const iconOpen = document.getElementById('mobileMenuIconOpen');
        const iconClose = document.getElementById('mobileMenuIconClose');

        if (mobileMenuButton && mobileMenu && iconOpen && iconClose) {
            mobileMenuButton.addEventListener('click', () => {
                const isOpen = !mobileMenu.classList.contains('hidden');
                if (isOpen) {
                    mobileMenu.classList.add('hidden');
                    iconOpen.classList.remove('hidden');
                    iconClose.classList.add('hidden');
                } else {
                    mobileMenu.classList.remove('hidden');
                    iconOpen.classList.add('hidden');
                    iconClose.classList.remove('hidden');
                }
            });
        }

        // Seller FAQ accordion (per card)
        const sellerFaqToggles = document.querySelectorAll('.seller-faq-toggle');
        sellerFaqToggles.forEach(btn => {
            btn.addEventListener('click', () => {
                const content = btn.nextElementSibling;
                if (!content) return;

                const parentCard = btn.closest('.bg-[#111111]');
                if (!parentCard) return;

                // Close all in this card
                parentCard.querySelectorAll('.seller-faq-content').forEach(c => c.classList.add('hidden'));
                parentCard.querySelectorAll('.seller-faq-toggle span:last-child').forEach(icon => {
                    icon.textContent = '+';
                });

                const isOpen = !content.classList.contains('hidden');
                if (!isOpen) {
                    content.classList.remove('hidden');
                    const icon = btn.querySelector('span:last-child');
                    if (icon) icon.textContent = '−';
                }
            });
        });
    </script>

</body>

</html>