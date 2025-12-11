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
    <style>
        :root {
            --lt-orange: #F36A1D;
            --lt-black: #0D0D0D;
        }
    </style>
</head>

<body class="bg-[#0D0D0D] text-white min-h-screen flex flex-col">

    <?php $currentPage = 'help'; include 'header.php'; ?>
    
    <main class="flex-1 py-6 sm:py-10">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6 sm:space-y-8">

            <!-- HERO / INTRO -->
            <section class="grid lg:grid-cols-[minmax(0,2.2fr)_minmax(0,1.5fr)] gap-6 lg:gap-8 items-start">
                <div class="space-y-3">
                    <p class="text-[11px] uppercase tracking-[0.15em] text-orange-400">
                        Help & Support
                    </p>
                    <h1 class="text-2xl sm:text-3xl font-semibold tracking-tight">
                        How can we help you on LocalTrade?
                    </h1>
                    <p class="text-xs sm:text-sm text-gray-400 max-w-xl">
                        Get answers about orders, payments, returns and selling on LocalTrade.
                        If you can’t find what you’re looking for, our support team is a message away.
                    </p>
                </div>

                <!-- Quick support card -->
                <div class="bg-[#111111] border border-white/10 rounded-2xl p-4 sm:p-5 text-xs sm:text-sm">
                    <h2 class="text-sm font-semibold mb-2">Need help right now?</h2>
                    <p class="text-[11px] text-gray-400 mb-3">
                        Reach our support team for urgent issues like failed payments or delivery problems.
                    </p>
                    <div class="space-y-2">
                        <a href="mailto:support@localtrade.ng"
                            class="flex items-center justify-between px-3 py-2 rounded-xl bg-[#0B0B0B] border border-white/15 hover:border-orange-400 text-[11px] sm:text-xs">
                            <span>Email support</span>
                            <span class="text-gray-400 text-[11px]">support@localtrade.ng</span>
                        </a>
                        <button type="button"
                            class="w-full flex items-center justify-between px-3 py-2 rounded-xl bg-orange-500 text-black text-[11px] sm:text-xs font-semibold hover:bg-orange-400">
                            <span>Open chat (coming soon)</span>
                            <span>💬</span>
                        </button>
                        <a href="#"
                            class="flex items-center justify-between px-3 py-2 rounded-xl bg-[#0B0B0B] border border-green-500/40 text-[11px] sm:text-xs hover:border-green-400">
                            <span>WhatsApp support (coming soon)</span>
                            <span>📱</span>
                        </a>
                    </div>
                    <p class="mt-3 text-[10px] text-gray-500">
                        Response times may vary, but we try to reply within a few hours.
                    </p>
                </div>
            </section>

            <!-- QUICK HELP CATEGORIES -->
            <section class="space-y-3">
                <h2 class="text-sm sm:text-base font-semibold">Browse help topics</h2>
                <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 text-xs sm:text-sm">

                    <!-- Orders & Delivery -->
                    <a href="#orders-help"
                        class="bg-[#111111] border border-white/10 rounded-2xl p-3 sm:p-4 hover:border-orange-400 transition-colors flex flex-col gap-2">
                        <div class="flex items-center justify-between">
                            <span class="text-gray-100 font-semibold">Orders & delivery</span>
                            <span>📦</span>
                        </div>
                        <p class="text-[11px] text-gray-400">
                            Track your order, delivery timelines and what to do if it’s late.
                        </p>
                        <span class="mt-auto text-[11px] text-orange-400">View answers →</span>
                    </a>

                    <!-- Payments -->
                    <a href="#payments-help"
                        class="bg-[#111111] border border-white/10 rounded-2xl p-3 sm:p-4 hover:border-orange-400 transition-colors flex flex-col gap-2">
                        <div class="flex items-center justify-between">
                            <span class="text-gray-100 font-semibold">Payments</span>
                            <span>💳</span>
                        </div>
                        <p class="text-[11px] text-gray-400">
                            Learn about payment methods, charges and failed transactions.
                        </p>
                        <span class="mt-auto text-[11px] text-orange-400">View answers →</span>
                    </a>

                    <!-- Returns & refunds -->
                    <a href="#returns-help"
                        class="bg-[#111111] border border-white/10 rounded-2xl p-3 sm:p-4 hover:border-orange-400 transition-colors flex flex-col gap-2">
                        <div class="flex items-center justify-between">
                            <span class="text-gray-100 font-semibold">Returns & refunds</span>
                            <span>🔁</span>
                        </div>
                        <p class="text-[11px] text-gray-400">
                            How to report an issue, request a return or get a refund.
                        </p>
                        <span class="mt-auto text-[11px] text-orange-400">View answers →</span>
                    </a>

                    <!-- Account / Selling -->
                    <a href="#account-help"
                        class="bg-[#111111] border border-white/10 rounded-2xl p-3 sm:p-4 hover:border-orange-400 transition-colors flex flex-col gap-2">
                        <div class="flex items-center justify-between">
                            <span class="text-gray-100 font-semibold">Account & selling</span>
                            <span>👤</span>
                        </div>
                        <p class="text-[11px] text-gray-400">
                            Manage your profile, saved addresses or becoming a LocalTrade seller.
                        </p>
                        <span class="mt-auto text-[11px] text-orange-400">View answers →</span>
                    </a>
                </div>
            </section>

            <!-- FAQ SECTIONS -->
            <section class="grid lg:grid-cols-2 gap-5 lg:gap-6 text-xs sm:text-sm">

                <!-- Orders & delivery FAQ -->
                <div id="orders-help" class="bg-[#111111] border border-white/10 rounded-2xl p-4 sm:p-5">
                    <h2 class="text-sm font-semibold mb-3 flex items-center justify-between">
                        Orders & delivery
                        <span class="text-[11px] text-gray-500">Buyer guide</span>
                    </h2>

                    <div class="divide-y divide-white/10">
                        <!-- Q1 -->
                        <button type="button"
                            class="faq-toggle w-full text-left py-3 flex items-center justify-between gap-3">
                            <span class="text-gray-100">How do I track my order?</span>
                            <span class="text-gray-500 text-xs">+</span>
                        </button>
                        <div class="faq-content hidden pb-3 text-[11px] text-gray-400">
                            After your order is confirmed, you’ll see a tracking status in <strong>My orders</strong>.
                            Some sellers also share a courier tracking link. If tracking is not updating after 48 hours,
                            please contact support with your order ID.
                        </div>

                        <!-- Q2 -->
                        <button type="button"
                            class="faq-toggle w-full text-left py-3 flex items-center justify-between gap-3">
                            <span class="text-gray-100">How long does delivery take?</span>
                            <span class="text-gray-500 text-xs">+</span>
                        </button>
                        <div class="faq-content hidden pb-3 text-[11px] text-gray-400">
                            Delivery times depend on the seller and your location.
                            Most orders within major cities in Nigeria arrive within <strong>2–5 business days</strong>.
                            The estimated delivery window is shown on the product page and at checkout.
                        </div>

                        <!-- Q3 -->
                        <button type="button"
                            class="faq-toggle w-full text-left py-3 flex items-center justify-between gap-3">
                            <span class="text-gray-100">My order is late. What should I do?</span>
                            <span class="text-gray-500 text-xs">+</span>
                        </button>
                        <div class="faq-content hidden pb-3 text-[11px] text-gray-400">
                            First, check the latest status in your <strong>orders</strong> page.
                            If your delivery window has passed and there’s no update, reach out to support with your
                            order ID so we can contact the seller or courier on your behalf.
                        </div>
                    </div>
                </div>

                <!-- Payments FAQ -->
                <div id="payments-help" class="bg-[#111111] border border-white/10 rounded-2xl p-4 sm:p-5">
                    <h2 class="text-sm font-semibold mb-3 flex items-center justify-between">
                        Payments
                        <span class="text-[11px] text-gray-500">Cards & transfers</span>
                    </h2>

                    <div class="divide-y divide-white/10">
                        <!-- Q1 -->
                        <button type="button"
                            class="faq-toggle w-full text-left py-3 flex items-center justify-between gap-3">
                            <span class="text-gray-100">What payment methods can I use?</span>
                            <span class="text-gray-500 text-xs">+</span>
                        </button>
                        <div class="faq-content hidden pb-3 text-[11px] text-gray-400">
                            LocalTrade supports <strong>debit/credit cards</strong> and other local payment methods
                            through secure payment providers. Available options are shown on the checkout page.
                        </div>

                        <!-- Q2 -->
                        <button type="button"
                            class="faq-toggle w-full text-left py-3 flex items-center justify-between gap-3">
                            <span class="text-gray-100">My card was charged but I see no order.</span>
                            <span class="text-gray-500 text-xs">+</span>
                        </button>
                        <div class="faq-content hidden pb-3 text-[11px] text-gray-400">
                            Sometimes payments get delayed in verification.
                            Please wait a few minutes and refresh your orders page.
                            If there is still no order after <strong>30 minutes</strong>, contact support with:
                            the last 4 digits of your card, amount, and time of payment.
                        </div>

                        <!-- Q3 -->
                        <button type="button"
                            class="faq-toggle w-full text-left py-3 flex items-center justify-between gap-3">
                            <span class="text-gray-100">Are there extra fees when I pay?</span>
                            <span class="text-gray-500 text-xs">+</span>
                        </button>
                        <div class="faq-content hidden pb-3 text-[11px] text-gray-400">
                            The amount you see at checkout already includes any platform or payment charges from
                            LocalTrade.
                            Your bank may still apply currency or card-related charges depending on your account.
                        </div>
                    </div>
                </div>

                <!-- Returns & refunds FAQ -->
                <div id="returns-help" class="bg-[#111111] border border-white/10 rounded-2xl p-4 sm:p-5">
                    <h2 class="text-sm font-semibold mb-3 flex items-center justify-between">
                        Returns & refunds
                        <span class="text-[11px] text-gray-500">Issues with an order</span>
                    </h2>

                    <div class="divide-y divide-white/10">
                        <!-- Q1 -->
                        <button type="button"
                            class="faq-toggle w-full text-left py-3 flex items-center justify-between gap-3">
                            <span class="text-gray-100">What if my item arrives damaged or wrong?</span>
                            <span class="text-gray-500 text-xs">+</span>
                        </button>
                        <div class="faq-content hidden pb-3 text-[11px] text-gray-400">
                            Please take clear photos or a short video of the issue and contact support within
                            <strong>48 hours</strong> of delivery.
                            We’ll review it with the seller and guide you through a replacement or refund.
                        </div>

                        <!-- Q2 -->
                        <button type="button"
                            class="faq-toggle w-full text-left py-3 flex items-center justify-between gap-3">
                            <span class="text-gray-100">How do refunds work on LocalTrade?</span>
                            <span class="text-gray-500 text-xs">+</span>
                        </button>
                        <div class="faq-content hidden pb-3 text-[11px] text-gray-400">
                            After a return is approved, refunds are sent back through your original payment method.
                            Depending on your bank or provider, it may take <strong>3–7 business days</strong> to
                            appear.
                        </div>

                        <!-- Q3 -->
                        <button type="button"
                            class="faq-toggle w-full text-left py-3 flex items-center justify-between gap-3">
                            <span class="text-gray-100">Can I cancel an order?</span>
                            <span class="text-gray-500 text-xs">+</span>
                        </button>
                        <div class="faq-content hidden pb-3 text-[11px] text-gray-400">
                            You can request a cancellation if the order has not yet been shipped.
                            Go to <strong>My orders</strong>, select the order and use the cancel option (coming soon),
                            or contact support and we’ll check with the seller.
                        </div>
                    </div>
                </div>

                <!-- Account & selling FAQ -->
                <div id="account-help" class="bg-[#111111] border border-white/10 rounded-2xl p-4 sm:p-5">
                    <h2 class="text-sm font-semibold mb-3 flex items-center justify-between">
                        Account & selling
                        <span class="text-[11px] text-gray-500">Profiles & brands</span>
                    </h2>

                    <div class="divide-y divide-white/10">
                        <!-- Q1 -->
                        <button type="button"
                            class="faq-toggle w-full text-left py-3 flex items-center justify-between gap-3">
                            <span class="text-gray-100">How do I update my details?</span>
                            <span class="text-gray-500 text-xs">+</span>
                        </button>
                        <div class="faq-content hidden pb-3 text-[11px] text-gray-400">
                            You can edit your name, phone and default addresses from your <strong>Account</strong> page
                            once you’re logged in. Changes apply to future orders.
                        </div>

                        <!-- Q2 -->
                        <button type="button"
                            class="faq-toggle w-full text-left py-3 flex items-center justify-between gap-3">
                            <span class="text-gray-100">I want to sell my products on LocalTrade.</span>
                            <span class="text-gray-500 text-xs">+</span>
                        </button>
                        <div class="faq-content hidden pb-3 text-[11px] text-gray-400">
                            Amazing! You can create a seller profile through the <strong>Brand signup</strong> flow
                            (on the seller side of LocalTrade). You’ll set up your store details, upload a logo, add
                            policies and start listing products.
                        </div>

                        <!-- Q3 -->
                        <button type="button"
                            class="faq-toggle w-full text-left py-3 flex items-center justify-between gap-3">
                            <span class="text-gray-100">How is my data secured?</span>
                            <span class="text-gray-500 text-xs">+</span>
                        </button>
                        <div class="faq-content hidden pb-3 text-[11px] text-gray-400">
                            LocalTrade uses industry-standard security practices and works with trusted payment
                            providers.
                            Never share your OTP, full card details or passwords with anyone claiming to be support.
                        </div>
                    </div>
                </div>
            </section>

            <!-- STILL NEED HELP -->
            <section class="bg-[#111111] border border-white/10 rounded-2xl p-4 sm:p-5 text-xs sm:text-sm">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div>
                        <h2 class="text-sm font-semibold">Still need help?</h2>
                        <p class="text-[11px] text-gray-400 mt-1 max-w-md">
                            If your issue is not covered in our FAQs, send us a message with your order ID and a short
                            description.
                        </p>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        <a href="mailto:support@localtrade.ng"
                            class="inline-flex items-center gap-2 px-3 py-2 rounded-full bg-[#0B0B0B] border border-white/20 hover:border-orange-400 text-[11px] sm:text-xs">
                            <span>Contact support</span>
                            <span>✉️</span>
                        </a>
                        <a href="help.php#"
                            class="inline-flex items-center gap-2 px-3 py-2 rounded-full text-[11px] sm:text-xs font-semibold text-black"
                            style="background-color:#F36A1D;">
                            <span>Report a problem</span>
                        </a>
                    </div>
                </div>
            </section>
        </div>
    </main>

    <footer class="border-t border-white/10">
        <div
            class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex flex-col sm:flex-row items-center justify-between gap-2 text-[11px] text-gray-500">
            <span>© <?= date('Y'); ?> LocalTrade. All rights reserved.</span>
            <div class="flex gap-3">
                <a href="#" class="hover:text-orange-400">Privacy</a>
                <a href="#" class="hover:text-orange-400">Terms</a>
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
                const parent = btn.closest('.bg-[#111111]');
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