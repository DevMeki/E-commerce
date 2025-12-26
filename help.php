<?php
// Help page for buyers
$currentPage = 'help';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Help & Support | LocalTrade</title>
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
    <style>
        :root {
            --lt-forest: #1E3932;
            --lt-orange: #F36A1D;
            --lt-parchment: #FCFBF7;
            --lt-ink: #1A1A1A;
            --lt-cream: #F3F0E6;
        }
    </style>
</head>

<body class="bg-brand-parchment text-brand-ink min-h-screen flex flex-col font-sans">

    <?php $currentPage = 'help';
    include 'header.php'; ?>

    <main class="flex-1 py-6 sm:py-10">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6 sm:space-y-8">

            <!-- HERO / INTRO -->
            <section class="grid lg:grid-cols-[minmax(0,2.2fr)_minmax(0,1.5fr)] gap-6 lg:gap-8 items-start">
                <div class="space-y-3">
                    <p class="text-[11px] uppercase tracking-[0.15em] text-brand-orange font-bold">
                        Help & Support
                    </p>
                    <h1 class="text-2xl sm:text-3xl font-semibold tracking-tight text-brand-forest">
                        How can we help you on LocalTrade?
                    </h1>
                    <p class="text-xs sm:text-sm text-brand-ink/50 max-w-xl">
                        Get answers about orders, payments, returns and selling on LocalTrade.
                        If you can’t find what you’re looking for, our support team is a message away.
                    </p>
                </div>

                <!-- Quick support card -->
                <div
                    class="bg-green-50 border border-brand-forest/5 rounded-2xl p-4 sm:p-5 text-xs sm:text-sm shadow-sm">
                    <h2 class="text-sm font-semibold mb-2 text-brand-forest">Need help right now?</h2>
                    <p class="text-[11px] text-brand-ink/50 mb-3">
                        Reach our support team for urgent issues like failed payments or delivery problems.
                    </p>
                    <div class="space-y-2">
                        <a href="mailto:support@localtrade.ng"
                            class="flex items-center justify-between px-3 py-2 rounded-xl bg-brand-parchment border border-brand-forest/5 hover:border-brand-orange text-[11px] sm:text-xs text-brand-ink">
                            <span>Email support</span>
                            <span class="text-brand-ink/50 text-[11px]">support@localtrade.ng</span>
                        </a>
                        <button type="button"
                            class="w-full flex items-center justify-between px-3 py-2 rounded-xl bg-brand-orange text-white text-[11px] sm:text-xs font-semibold hover:bg-orange-400">
                            <span>Open chat (coming soon)</span>
                            <span>💬</span>
                        </button>
                        <a href="#"
                            class="flex items-center justify-between px-3 py-2 rounded-xl bg-brand-parchment border border-brand-forest/5 text-[11px] sm:text-xs text-brand-ink hover:border-green-400">
                            <span>WhatsApp support (coming soon)</span>
                            <span>📱</span>
                        </a>
                    </div>
                    <p class="mt-3 text-[10px] text-brand-ink/40">
                        Response times may vary, but we try to reply within a few hours.
                    </p>
                </div>
            </section>

            <!-- QUICK HELP CATEGORIES -->
            <section class="space-y-6">
                <h2 class="text-[11px] font-bold text-brand-ink/30 uppercase tracking-[0.2em]">Browse help topics</h2>
                <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">

                    <!-- Orders & Delivery -->
                    <a href="#orders-help"
                        class="bg-green-50 border border-brand-forest/5 rounded-2xl p-4 sm:p-5 hover:border-brand-orange/30 shadow-sm hover:shadow-xl transition-all group flex flex-col gap-3">
                        <div
                            class="w-10 h-10 rounded-lg bg-brand-parchment flex items-center justify-center text-lg group-hover:scale-110 transition-transform">
                            📦
                        </div>
                        <div>
                            <span class="block text-sm font-semibold text-brand-forest mb-1">Orders & Delivery</span>
                            <p class="text-[11px] text-brand-ink/40 leading-relaxed">
                                Track your orders, understand delivery timelines, and manage shipments.
                            </p>
                        </div>
                        <span
                            class="mt-2 text-[10px] font-bold text-brand-orange uppercase tracking-widest group-hover:translate-x-1 transition-transform inline-block">View
                            guide →</span>
                    </a>

                    <!-- Payments -->
                    <a href="#payments-help"
                        class="bg-green-50 border border-brand-forest/5 rounded-2xl p-4 sm:p-5 hover:border-brand-orange/30 shadow-sm hover:shadow-xl transition-all group flex flex-col gap-3">
                        <div
                            class="w-10 h-10 rounded-lg bg-brand-forest/5 flex items-center justify-center text-lg group-hover:scale-110 transition-transform text-brand-forest">
                            🏦
                        </div>
                        <div>
                            <span class="block text-sm font-semibold text-brand-forest mb-1">Payments</span>
                            <p class="text-[11px] text-brand-ink/40 leading-relaxed">
                                Learn about payment methods, transaction security, and billing inquiries.
                            </p>
                        </div>
                        <span
                            class="mt-2 text-[10px] font-bold text-brand-orange uppercase tracking-widest group-hover:translate-x-1 transition-transform inline-block">View
                            guide →</span>
                    </a>

                    <!-- Returns & refunds -->
                    <a href="#returns-help"
                        class="bg-green-50 border border-brand-forest/5 rounded-2xl p-4 sm:p-5 hover:border-brand-orange/30 shadow-sm hover:shadow-xl transition-all group flex flex-col gap-3">
                        <div
                            class="w-10 h-10 rounded-lg bg-brand-orange/10 flex items-center justify-center text-lg group-hover:scale-110 transition-transform text-brand-orange">
                            🔄
                        </div>
                        <div>
                            <span class="block text-sm font-semibold text-brand-forest mb-1">Returns & Refunds</span>
                            <p class="text-[11px] text-brand-ink/40 leading-relaxed">
                                Understand our return policy, refund process, and dispute resolution.
                            </p>
                        </div>
                        <span
                            class="mt-2 text-[10px] font-bold text-brand-orange uppercase tracking-widest group-hover:translate-x-1 transition-transform inline-block">View
                            guide →</span>
                    </a>

                    <!-- Account / Selling -->
                    <a href="#account-help"
                        class="bg-green-50 border border-brand-forest/5 rounded-2xl p-4 sm:p-5 hover:border-brand-orange/30 shadow-sm hover:shadow-xl transition-all group flex flex-col gap-3">
                        <div
                            class="w-10 h-10 rounded-lg bg-brand-cream flex items-center justify-center text-lg group-hover:scale-110 transition-transform">
                            🏺
                        </div>
                        <div>
                            <span class="block text-sm font-semibold text-brand-forest mb-1">Account & Selling</span>
                            <p class="text-[11px] text-brand-ink/40 leading-relaxed">
                                Manage your profile, update details, or learn how to become a seller.
                            </p>
                        </div>
                        <span
                            class="mt-2 text-[10px] font-bold text-brand-orange uppercase tracking-widest group-hover:translate-x-1 transition-transform inline-block">View
                            guide →</span>
                    </a>
                </div>
            </section>

            <!-- FAQ SECTIONS -->
            <section class="grid lg:grid-cols-2 gap-5 lg:gap-6 text-xs sm:text-sm">

                <!-- Orders & delivery FAQ -->
                <div id="orders-help" class="bg-green-50 border border-brand-forest/5 rounded-2xl p-4 sm:p-5 shadow-sm">
                    <h2 class="text-sm font-semibold mb-3 flex items-center justify-between text-brand-forest">
                        Orders & Delivery
                        <span class="text-[11px] text-brand-ink/30">Tracking & timelines</span>
                    </h2>

                    <div class="divide-y divide-brand-forest/5">
                        <!-- Q1 -->
                        <button type="button"
                            class="faq-toggle w-full text-left py-3 flex items-center justify-between gap-3">
                            <span class="text-brand-forest font-medium">How do I track my order?</span>
                            <span class="text-brand-ink/30 text-xs">+</span>
                        </button>
                        <div class="faq-content hidden pb-3 text-[11px] text-brand-ink/50">
                            After your order is confirmed, you’ll see a tracking status in <strong>My orders</strong>.
                            Some sellers also share a courier tracking link. If tracking is not updating after 48 hours,
                            please contact support with your order ID.
                        </div>

                        <!-- Q2 -->
                        <button type="button"
                            class="faq-toggle w-full text-left py-3 flex items-center justify-between gap-3">
                            <span class="text-brand-forest font-medium">How long does delivery take?</span>
                            <span class="text-brand-ink/30 text-xs">+</span>
                        </button>
                        <div class="faq-content hidden pb-3 text-[11px] text-brand-ink/50">
                            Delivery times depend on the seller and your location.
                            Most orders within major cities in Nigeria arrive within <strong>2–5 business days</strong>.
                            The estimated delivery window is shown on the product page and at checkout.
                        </div>

                        <!-- Q3 -->
                        <button type="button"
                            class="faq-toggle w-full text-left py-3 flex items-center justify-between gap-3">
                            <span class="text-brand-forest font-medium">My order is late. What should I do?</span>
                            <span class="text-brand-ink/30 text-xs">+</span>
                        </button>
                        <div class="faq-content hidden pb-3 text-[11px] text-brand-ink/50">
                            First, check the latest status in your <strong>orders</strong> page.
                            If your delivery window has passed and there’s no update, reach out to support with your
                            order ID so we can contact the seller or courier on your behalf.
                        </div>
                    </div>
                </div>

                <!-- Payments FAQ -->
                <div id="payments-help"
                    class="bg-green-50 border border-brand-forest/5 rounded-2xl p-4 sm:p-5 shadow-sm">
                    <h2 class="text-sm font-semibold mb-3 flex items-center justify-between text-brand-forest">
                        Payments
                        <span class="text-[11px] text-brand-ink/30">Cards & transfers</span>
                    </h2>

                    <div class="divide-y divide-brand-forest/5">
                        <!-- Q1 -->
                        <button type="button"
                            class="faq-toggle w-full text-left py-3 flex items-center justify-between gap-3">
                            <span class="text-brand-forest font-medium">What payment methods can I use?</span>
                            <span class="text-brand-ink/30 text-xs">+</span>
                        </button>
                        <div class="faq-content hidden pb-3 text-[11px] text-brand-ink/50">
                            LocalTrade supports <strong>debit/credit cards</strong> and other local payment methods
                            through secure payment providers. Available options are shown on the checkout page.
                        </div>

                        <!-- Q2 -->
                        <button type="button"
                            class="faq-toggle w-full text-left py-3 flex items-center justify-between gap-3">
                            <span class="text-brand-forest font-medium">My card was charged but I see no order.</span>
                            <span class="text-brand-ink/30 text-xs">+</span>
                        </button>
                        <div class="faq-content hidden pb-3 text-[11px] text-brand-ink/50">
                            Sometimes payments get delayed in verification.
                            Please wait a few minutes and refresh your orders page.
                            If there is still no order after <strong>30 minutes</strong>, contact support with:
                            the last 4 digits of your card, amount, and time of payment.
                        </div>

                        <!-- Q3 -->
                        <button type="button"
                            class="faq-toggle w-full text-left py-3 flex items-center justify-between gap-3">
                            <span class="text-brand-forest font-medium">Are there extra fees when I pay?</span>
                            <span class="text-brand-ink/30 text-xs">+</span>
                        </button>
                        <div class="faq-content hidden pb-3 text-[11px] text-brand-ink/50">
                            The amount you see at checkout already includes any platform or payment charges from
                            LocalTrade.
                            Your bank may still apply currency or card-related charges depending on your account.
                        </div>
                    </div>
                </div>

                <!-- Returns & refunds FAQ -->
                <div id="returns-help"
                    class="bg-green-50 border border-brand-forest/5 rounded-2xl p-4 sm:p-5 shadow-sm">
                    <h2 class="text-sm font-semibold mb-3 flex items-center justify-between text-brand-forest">
                        Returns & Refunds
                        <span class="text-[11px] text-brand-ink/30">Exchanges & disputes</span>
                    </h2>

                    <div class="divide-y divide-brand-forest/5">
                        <!-- Q1 -->
                        <button type="button"
                            class="faq-toggle w-full text-left py-3 flex items-center justify-between gap-3">
                            <span class="text-brand-forest font-medium">What if my item arrives damaged or wrong?</span>
                            <span class="text-brand-ink/30 text-xs">+</span>
                        </button>
                        <div class="faq-content hidden pb-3 text-[11px] text-brand-ink/50">
                            Please take clear photos or a short video of the issue and contact support within
                            <strong>48 hours</strong> of delivery.
                            We’ll review it with the seller and guide you through a replacement or refund.
                        </div>

                        <!-- Q2 -->
                        <button type="button"
                            class="faq-toggle w-full text-left py-3 flex items-center justify-between gap-3">
                            <span class="text-brand-forest font-medium">How do refunds work on LocalTrade?</span>
                            <span class="text-brand-ink/30 text-xs">+</span>
                        </button>
                        <div class="faq-content hidden pb-3 text-[11px] text-brand-ink/50">
                            After a return is approved, refunds are sent back through your original payment method.
                            Depending on your bank or provider, it may take <strong>3–7 business days</strong> to
                            appear.
                        </div>

                        <!-- Q3 -->
                        <button type="button"
                            class="faq-toggle w-full text-left py-3 flex items-center justify-between gap-3">
                            <span class="text-brand-forest font-medium">Can I cancel an order?</span>
                            <span class="text-brand-ink/30 text-xs">+</span>
                        </button>
                        <div class="faq-content hidden pb-3 text-[11px] text-brand-ink/50">
                            You can request a cancellation if the order has not yet been shipped.
                            Go to <strong>My orders</strong>, select the order and use the cancel option (coming soon),
                            or contact support and we’ll check with the seller.
                        </div>
                    </div>
                </div>

                <!-- Account & selling FAQ -->
                <div id="account-help"
                    class="bg-green-50 border border-brand-forest/5 rounded-2xl p-4 sm:p-5 shadow-sm">
                    <h2 class="text-sm font-semibold mb-3 flex items-center justify-between text-brand-forest">
                        Account & Selling
                        <span class="text-[11px] text-brand-ink/30">Profile & stores</span>
                    </h2>

                    <div class="divide-y divide-brand-forest/5">
                        <!-- Q1 -->
                        <button type="button"
                            class="faq-toggle w-full text-left py-3 flex items-center justify-between gap-3">
                            <span class="text-brand-forest font-medium">How do I update my details?</span>
                            <span class="text-brand-ink/30 text-xs">+</span>
                        </button>
                        <div class="faq-content hidden pb-3 text-[11px] text-brand-ink/50">
                            You can update your personal information, delivery addresses, and profile picture from the
                            <strong>Account</strong> tab.
                        </div>

                        <!-- Q2 -->
                        <button type="button"
                            class="faq-toggle w-full text-left py-3 flex items-center justify-between gap-3">
                            <span class="text-brand-forest font-medium">I want to sell my products on LocalTrade.</span>
                            <span class="text-brand-ink/30 text-xs">+</span>
                        </button>
                        <div class="faq-content hidden pb-3 text-[11px] text-brand-ink/50">
                            Great! You can create a seller profile through the <strong>Brand signup</strong> flow
                            (on the seller side of LocalTrade). You’ll set up your store details, upload a logo, add
                            policies and start listing products.
                        </div>

                        <!-- Q3 -->
                        <button type="button"
                            class="faq-toggle w-full text-left py-3 flex items-center justify-between gap-3">
                            <span class="text-brand-forest font-medium">How is my data secured?</span>
                            <span class="text-brand-ink/30 text-xs">+</span>
                        </button>
                        <div class="faq-content hidden pb-3 text-[11px] text-brand-ink/50">
                            LocalTrade uses industry-standard security practices and works with trusted payment
                            providers.
                            Never share your OTP, full card details or passwords with anyone claiming to be support.
                        </div>
                    </div>
                </div>
            </section>

            <!-- STILL NEED HELP -->
            <section
                class="bg-green-50 border border-brand-forest/5 rounded-2xl p-4 sm:p-5 text-xs sm:text-sm shadow-sm">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div>
                        <h2 class="text-sm font-semibold text-brand-forest">Still need help?</h2>
                        <p class="text-[11px] text-brand-ink/50 mt-1 max-w-md">
                            If your issue is not covered in our FAQs, send us a message with your order ID and a short
                            description.
                        </p>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        <a href="mailto:support@localtrade.ng"
                            class="inline-flex items-center gap-2 px-3 py-2 rounded-full bg-brand-parchment border border-brand-forest/5 hover:border-brand-orange text-[11px] sm:text-xs text-brand-ink">
                            <span>Contact support</span>
                            <span>✉️</span>
                        </a>
                        <a href="help.php#"
                            class="inline-flex items-center gap-2 px-3 py-2 rounded-full text-[11px] sm:text-xs font-semibold text-white"
                            style="background-color:#F36A1D;">
                            <span>Report a problem</span>
                        </a>
                    </div>
                </div>
            </section>
        </div>
    </main>

    <footer class="border-t border-brand-forest/5 bg-white">
        <div
            class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex flex-col sm:flex-row items-center justify-between gap-2 text-[11px] text-brand-ink/40">
            <span>© <?= date('Y'); ?> LocalTrade. All rights reserved.</span>
            <div class="flex gap-3">
                <a href="#" class="hover:text-brand-orange transition-colors">Privacy</a>
                <a href="#" class="hover:text-brand-orange transition-colors">Terms</a>
            </div>
        </div>
    </footer>

    <!-- Mobile menu toggle (from header) -->
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

        // Simple FAQ accordion
        const faqToggles = document.querySelectorAll('.faq-toggle');
        faqToggles.forEach(btn => {
            btn.addEventListener('click', () => {
                const content = btn.nextElementSibling;
                if (!content) return;

                const isOpen = !content.classList.contains('hidden');

                // Close all in this section
                const parent = btn.closest('div[id$="-help"]');
                if (parent) {
                    parent.querySelectorAll('.faq-content').forEach(c => c.classList.add('hidden'));
                    parent.querySelectorAll('.faq-toggle span:last-child').forEach(icon => {
                        icon.textContent = '+';
                    });
                }

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