<?php
require_once 'process/check_brand_login.php';
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
</head>

<body class="bg-brand-parchment text-brand-ink min-h-screen flex flex-col">

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
                    <p class="text-[11px] uppercase tracking-[0.15em] text-brand-orange font-bold">
                        Seller help
                    </p>
                    <h1 class="text-2xl sm:text-3xl font-semibold tracking-tight text-brand-forest">
                        Get help running your LocalTrade store
                    </h1>
                    <p class="text-xs sm:text-sm text-brand-ink/50 mt-1 max-w-xl">
                        From payouts and product approval to shipping and store policies, this space is for brands
                        selling on LocalTrade.
                    </p>
                </div>
                <div
                    class="bg-green-50 border border-brand-forest/5 rounded-2xl px-4 py-3 text-[11px] sm:text-xs text-brand-ink/70 shadow-sm">
                    <p class="font-semibold text-brand-forest mb-1">Need urgent help?</p>
                    <p class="mb-2 text-brand-ink/60">
                        Email: <span class="text-brand-forest font-semibold">seller-support@localtrade.ng</span>
                    </p>
                    <p class="text-brand-ink/40 italic">
                        Response time: typically within <span class="text-brand-forest font-bold">1–4 business
                            hours</span>.
                    </p>
                </div>
            </section>

            <!-- TOPIC CARDS -->
            <section class="space-y-3">
                <h2 class="text-sm sm:text-base font-semibold text-brand-forest">Seller help topics</h2>
                <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 text-xs sm:text-sm">
                    <!-- Getting started -->
                    <a href="#setup-help"
                        class="bg-green-50 border border-brand-forest/5 rounded-2xl p-3 sm:p-4 hover:border-brand-orange/50 transition-all flex flex-col gap-2 shadow-sm hover:shadow-md group">
                        <div class="flex items-center justify-between">
                            <span class="text-brand-forest font-semibold">Get started</span>
                            <span>🚀</span>
                        </div>
                        <p class="text-[11px] text-brand-ink/50">
                            Set up your brand profile, logo, store details and policies.
                        </p>
                        <span
                            class="mt-auto text-[11px] text-brand-orange font-bold group-hover:translate-x-1 transition-transform">View
                            answers →</span>
                    </a>

                    <!-- Product approval -->
                    <a href="#products-help"
                        class="bg-green-50 border border-brand-forest/5 rounded-2xl p-3 sm:p-4 hover:border-brand-orange/50 transition-all flex flex-col gap-2 shadow-sm hover:shadow-md group">
                        <div class="flex items-center justify-between">
                            <span class="text-brand-forest font-semibold">Product approval</span>
                            <span>✅</span>
                        </div>
                        <p class="text-[11px] text-brand-ink/50">
                            Learn how product review works and why listings get rejected.
                        </p>
                        <span
                            class="mt-auto text-[11px] text-brand-orange font-bold group-hover:translate-x-1 transition-transform">View
                            answers →</span>
                    </a>

                    <!-- Payouts -->
                    <a href="#payouts-help"
                        class="bg-green-50 border border-brand-forest/5 rounded-2xl p-3 sm:p-4 hover:border-brand-orange/50 transition-all flex flex-col gap-2 shadow-sm hover:shadow-md group">
                        <div class="flex items-center justify-between">
                            <span class="text-brand-forest font-semibold">Payouts & earnings</span>
                            <span>💸</span>
                        </div>
                        <p class="text-[11px] text-brand-ink/50">
                            Settlement timelines, payment cycles and how much you actually receive.
                        </p>
                        <span
                            class="mt-auto text-[11px] text-brand-orange font-bold group-hover:translate-x-1 transition-transform">View
                            answers →</span>
                    </a>

                    <!-- Shipping & returns -->
                    <a href="#shipping-help"
                        class="bg-green-50 border border-brand-forest/5 rounded-2xl p-3 sm:p-4 hover:border-brand-orange/50 transition-all flex flex-col gap-2 shadow-sm hover:shadow-md group">
                        <div class="flex items-center justify-between">
                            <span class="text-brand-forest font-semibold">Shipping & returns</span>
                            <span>📦</span>
                        </div>
                        <p class="text-[11px] text-brand-ink/50">
                            Handling delivery, returns, and keeping your ratings healthy.
                        </p>
                        <span
                            class="mt-auto text-[11px] text-brand-orange font-bold group-hover:translate-x-1 transition-transform">View
                            answers →</span>
                    </a>
                </div>
            </section>

            <!-- FAQ GRID -->
            <section class="grid lg:grid-cols-2 gap-5 lg:gap-6 text-xs sm:text-sm">

                <!-- Getting started / Store setup -->
                <div id="setup-help" class="bg-white border border-brand-forest/10 rounded-2xl p-4 sm:p-5 shadow-sm">
                    <h2 class="text-sm font-semibold mb-3 flex items-center justify-between text-brand-forest">
                        Getting started & store setup
                        <span class="text-[11px] text-brand-ink/40">Brand basics</span>
                    </h2>

                    <div class="divide-y divide-brand-forest/5">
                        <!-- Q1 -->
                        <button type="button"
                            class="seller-faq-toggle w-full text-left py-3 flex items-center justify-between gap-3 group">
                            <span class="text-brand-ink group-hover:text-brand-orange transition-colors">What do I need
                                before opening a LocalTrade store?</span>
                            <span class="text-brand-ink/40 text-xs">+</span>
                        </button>
                        <div class="seller-faq-content hidden pb-3 text-[11px] text-brand-ink/60">
                            You’ll need your brand name, logo, a short description, contact details, and at least one
                            product you’re ready to sell. If you have a registered business, you can also add your
                            BN/RC number in your brand profile.
                        </div>

                        <!-- Q2 -->
                        <button type="button"
                            class="seller-faq-toggle w-full text-left py-3 flex items-center justify-between gap-3 group">
                            <span class="text-brand-ink group-hover:text-brand-orange transition-colors">How do I set my
                                store policies?</span>
                            <span class="text-brand-ink/40 text-xs">+</span>
                        </button>
                        <div class="seller-faq-content hidden pb-3 text-[11px] text-brand-ink/60">
                            During seller onboarding, you can define your handling time, return window, and basic rules
                            for refunds or exchanges. Keep it clear and realistic—customers see these policies on your
                            brand
                            page and product listings.
                        </div>

                        <!-- Q3 -->
                        <button type="button"
                            class="seller-faq-toggle w-full text-left py-3 flex items-center justify-between gap-3 group">
                            <span class="text-brand-ink group-hover:text-brand-orange transition-colors">Can I update my
                                logo and brand info later?</span>
                            <span class="text-brand-ink/40 text-xs">+</span>
                        </button>
                        <div class="seller-faq-content hidden pb-3 text-[11px] text-brand-ink/60">
                            Yes. You can edit your logo, description, social links and other details from your
                            <strong>Brand settings</strong> page at any time. Major changes may be reviewed by
                            LocalTrade.
                        </div>
                    </div>
                </div>

                <!-- Product approval -->
                <div id="products-help" class="bg-white border border-brand-forest/10 rounded-2xl p-4 sm:p-5 shadow-sm">
                    <h2 class="text-sm font-semibold mb-3 flex items-center justify-between text-brand-forest">
                        Product approval & listing quality
                        <span class="text-[11px] text-brand-ink/40">Listings</span>
                    </h2>

                    <div class="divide-y divide-brand-forest/5">
                        <!-- Q1 -->
                        <button type="button"
                            class="seller-faq-toggle w-full text-left py-3 flex items-center justify-between gap-3 group">
                            <span class="text-brand-ink group-hover:text-brand-orange transition-colors">Why do my
                                products need approval?</span>
                            <span class="text-brand-ink/40 text-xs">+</span>
                        </button>
                        <div class="seller-faq-content hidden pb-3 text-[11px] text-brand-ink/60">
                            LocalTrade reviews new listings to protect buyers and keep the marketplace clean.
                            We check for prohibited items, misleading content, poor-quality images, and missing
                            information
                            like pricing or sizing.
                        </div>

                        <!-- Q2 -->
                        <button type="button"
                            class="seller-faq-toggle w-full text-left py-3 flex items-center justify-between gap-3 group">
                            <span class="text-brand-ink group-hover:text-brand-orange transition-colors">How long does
                                product review take?</span>
                            <span class="text-brand-ink/40 text-xs">+</span>
                        </button>
                        <div class="seller-faq-content hidden pb-3 text-[11px] text-brand-ink/60">
                            Most products are reviewed within <strong>24 hours</strong> on business days.
                            During very busy periods, it might take slightly longer, but you can still edit drafts while
                            you wait for approval.
                        </div>

                        <!-- Q3 -->
                        <button type="button"
                            class="seller-faq-toggle w-full text-left py-3 flex items-center justify-between gap-3 group">
                            <span class="text-brand-ink group-hover:text-brand-orange transition-colors">My product was
                                rejected. What should I do?</span>
                            <span class="text-brand-ink/40 text-xs">+</span>
                        </button>
                        <div class="seller-faq-content hidden pb-3 text-[11px] text-brand-ink/60">
                            Check the rejection reason in your product details. It might be due to unclear images,
                            missing details, or a policy violation.
                            Fix the highlighted issues, save changes and resubmit. If you’re unsure, contact seller
                            support
                            with the product ID.
                        </div>
                    </div>
                </div>

                <!-- Payouts & earnings -->
                <div id="payouts-help" class="bg-white border border-brand-forest/10 rounded-2xl p-4 sm:p-5 shadow-sm">
                    <h2 class="text-sm font-semibold mb-3 flex items-center justify-between text-brand-forest">
                        Payouts & earnings
                        <span class="text-[11px] text-brand-ink/40">Money</span>
                    </h2>

                    <div class="divide-y divide-brand-forest/5">
                        <!-- Q1 -->
                        <button type="button"
                            class="seller-faq-toggle w-full text-left py-3 flex items-center justify-between gap-3 group">
                            <span class="text-brand-ink group-hover:text-brand-orange transition-colors">When do I get
                                paid after an order?</span>
                            <span class="text-brand-ink/40 text-xs">+</span>
                        </button>
                        <div class="seller-faq-content hidden pb-3 text-[11px] text-brand-ink/60">
                            Payouts are usually released after the order is marked as <strong>delivered</strong> and the
                            buyer’s return window has passed.
                            Depending on your payout schedule, funds might land weekly or on a custom cycle defined in
                            your
                            seller settings.
                        </div>

                        <!-- Q2 -->
                        <button type="button"
                            class="seller-faq-toggle w-full text-left py-3 flex items-center justify-between gap-3 group">
                            <span class="text-brand-ink group-hover:text-brand-orange transition-colors">How are
                                LocalTrade fees calculated?</span>
                            <span class="text-brand-ink/40 text-xs">+</span>
                        </button>
                        <div class="seller-faq-content hidden pb-3 text-[11px] text-brand-ink/60">
                            For each order, we deduct the platform fee and payment processing charge before sending your
                            payout.
                            You’ll see a breakdown per order in your <strong>Earnings</strong> or
                            <strong>Payout</strong> view.
                        </div>

                        <!-- Q3 -->
                        <button type="button"
                            class="seller-faq-toggle w-full text-left py-3 flex items-center justify-between gap-3 group">
                            <span class="text-brand-ink group-hover:text-brand-orange transition-colors">My payout is
                                delayed or missing.</span>
                            <span class="text-brand-ink/40 text-xs">+</span>
                        </button>
                        <div class="seller-faq-content hidden pb-3 text-[11px] text-brand-ink/60">
                            First, confirm that your bank details are correct and verified in your payout settings.
                            Then check if the orders are already in “delivered” status and past the return window.
                            If everything looks fine, contact seller support with your brand name and the payout period.
                        </div>
                    </div>
                </div>

                <!-- Shipping, logistics & returns -->
                <div id="shipping-help" class="bg-white border border-brand-forest/10 rounded-2xl p-4 sm:p-5 shadow-sm">
                    <h2 class="text-sm font-semibold mb-3 flex items-center justify-between text-brand-forest">
                        Shipping, logistics & returns
                        <span class="text-[11px] text-brand-ink/40">Operations</span>
                    </h2>

                    <div class="divide-y divide-brand-forest/5">
                        <!-- Q1 -->
                        <button type="button"
                            class="seller-faq-toggle w-full text-left py-3 flex items-center justify-between gap-3 group">
                            <span class="text-brand-ink group-hover:text-brand-orange transition-colors">Who handles
                                delivery—the brand or LocalTrade?</span>
                            <span class="text-brand-ink/40 text-xs">+</span>
                        </button>
                        <div class="seller-faq-content hidden pb-3 text-[11px] text-brand-ink/60">
                            This depends on your configuration.
                            Some brands handle their own couriers, while others plug into LocalTrade’s logistics
                            partners.
                            Your setup is defined during onboarding and can be adjusted in your shipping settings.
                        </div>

                        <!-- Q2 -->
                        <button type="button"
                            class="seller-faq-toggle w-full text-left py-3 flex items-center justify-between gap-3 group">
                            <span class="text-brand-ink group-hover:text-brand-orange transition-colors">What is
                                expected response time for orders?</span>
                            <span class="text-brand-ink/40 text-xs">+</span>
                        </button>
                        <div class="seller-faq-content hidden pb-3 text-[11px] text-brand-ink/60">
                            We encourage sellers to confirm and ship orders within their stated processing time,
                            usually <strong>1–3 business days</strong>.
                            Slow responses can impact your seller rating and visibility in the marketplace.
                        </div>

                        <!-- Q3 -->
                        <button type="button"
                            class="seller-faq-toggle w-full text-left py-3 flex items-center justify-between gap-3 group">
                            <span class="text-brand-ink group-hover:text-brand-orange transition-colors">How do returns
                                work for sellers?</span>
                            <span class="text-brand-ink/40 text-xs">+</span>
                        </button>
                        <div class="seller-faq-content hidden pb-3 text-[11px] text-brand-ink/60">
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
            <section
                class="bg-green-50 border border-brand-forest/5 rounded-2xl p-4 sm:p-5 text-xs sm:text-sm shadow-sm">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div>
                        <h2 class="text-sm font-semibold text-brand-forest">Still stuck with something?</h2>
                        <p class="text-[11px] text-brand-ink/50 mt-1 max-w-md">
                            Share your brand name, affected order or product ID, and a short description of the issue.
                            Our seller support team will get back to you as quickly as possible.
                        </p>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        <a href="mailto:seller-support@localtrade.ng"
                            class="inline-flex items-center gap-2 px-3 py-2 rounded-full bg-white border border-brand-forest/10 hover:border-brand-orange/50 text-[11px] sm:text-xs text-brand-forest shadow-sm">
                            <span>Email seller support</span>
                            <span>✉️</span>
                        </a>
                        <a href="#"
                            class="inline-flex items-center gap-2 px-3 py-2 rounded-full text-[11px] sm:text-xs font-bold text-white shadow-sm shadow-brand-orange/20"
                            style="background-color: var(--lt-orange);">
                            <span>Open support ticket (coming soon)</span>
                        </a>
                    </div>
                </div>
            </section>
        </div>
    </main>

    <footer class="border-t border-brand-forest/5 bg-white/50">
        <div
            class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-6 flex flex-col sm:flex-row items-center justify-between gap-2 text-[11px] text-brand-ink/30">
            <span>© <?= date('Y'); ?> LocalTrade · Seller dashboard</span>
            <div class="flex gap-4">
                <a href="#" class="hover:text-brand-orange">Seller terms</a>
                <a href="#" class="hover:text-brand-orange">Policy & compliance</a>
            </div>
        </div>
    </footer>

    </script>

    // Seller FAQ accordion (per card)
    const sellerFaqToggles = document.querySelectorAll('.seller-faq-toggle');
    sellerFaqToggles.forEach(btn => {
    btn.addEventListener('click', () => {
    const content = btn.nextElementSibling;
    if (!content) return;

    const parentCard = btn.closest('.bg-white');
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