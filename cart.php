<?php
// -------- Mock cart data (replace with session/DB later) --------
$cartItems = [
    [
        'id' => 101,
        'name' => 'Handcrafted Ankara Tote Bag',
        'seller' => 'Lagos Streetwear Co.',
        'price' => 14500,
        'currency' => '₦',
        'qty' => 1,
        'variant' => 'Colour: Orange · Size: One Size',
    ],
    [
        'id' => 202,
        'name' => 'Naija Drip Tee',
        'seller' => 'Lagos Streetwear Co.',
        'price' => 7500,
        'currency' => '₦',
        'qty' => 2,
        'variant' => 'Size: L · Colour: Black',
    ],
];

$currency = '₦';

// Calculate subtotal in PHP
$subtotal = 0;
foreach ($cartItems as $item) {
    $subtotal += $item['price'] * $item['qty'];
}

// Simple flat delivery estimate (can be dynamic later)
$deliveryEstimate = 2500;
$total = $subtotal + $deliveryEstimate;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Your Cart | LocalTrade</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        :root {
            --lt-orange: #F36A1D;
            --lt-black: #0D0D0D;
        }
    </style>
</head>

<body class="bg-[#0D0D0D] text-white">
    <div class="min-h-screen flex flex-col">

        <!-- HEADER -->
        <?php 
        include 'header.php'; ?>

        <!-- MAIN -->
        <main class="flex-1 py-6 sm:py-10">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
                <h1 class="text-xl sm:text-2xl font-semibold mb-4 sm:mb-6">Your Cart</h1>

                <?php if (empty($cartItems)): ?>
                    <div class="bg-[#111111] border border-white/10 rounded-3xl p-6 text-center text-sm text-gray-300">
                        Your cart is empty. <a href="index.php" class="text-orange-400 hover:underline">Start shopping</a>.
                    </div>
                <?php else: ?>

                    <div class="grid lg:grid-cols-[minmax(0,2fr)_minmax(280px,1fr)] gap-6 lg:gap-10">

                        <!-- CART ITEMS -->
                        <section class="space-y-3 sm:space-y-4">
                            <?php foreach ($cartItems as $index => $item): ?>
                                <div class="cart-item bg-[#111111] border border-white/10 rounded-2xl p-3 sm:p-4 flex gap-3 sm:gap-4"
                                    data-index="<?php echo $index; ?>" data-price="<?php echo (int) $item['price']; ?>">
                                    <!-- Product image placeholder -->
                                    <div
                                        class="w-20 sm:w-24 h-20 sm:h-24 rounded-xl bg-gradient-to-br from-orange-500/60 to-pink-500/60 flex items-center justify-center text-[11px] text-center">
                                        Local brand
                                    </div>

                                    <!-- Info -->
                                    <div class="flex-1 flex flex-col gap-1">
                                        <div class="flex items-start justify-between gap-2">
                                            <div>
                                                <p class="text-sm sm:text-base font-semibold leading-snug">
                                                    <?php echo htmlspecialchars($item['name']); ?>
                                                </p>
                                                <p class="text-[11px] sm:text-xs text-gray-400">
                                                    by <?php echo htmlspecialchars($item['seller']); ?>
                                                </p>
                                                <?php if (!empty($item['variant'])): ?>
                                                    <p class="text-[11px] text-gray-400 mt-1">
                                                        <?php echo htmlspecialchars($item['variant']); ?>
                                                    </p>
                                                <?php endif; ?>
                                            </div>
                                            <button type="button"
                                                class="remove-item text-[11px] text-gray-400 hover:text-red-400">
                                                Remove
                                            </button>
                                        </div>

                                        <!-- Bottom row: quantity + price -->
                                        <div class="mt-3 flex flex-wrap items-center justify-between gap-3">
                                            <!-- Quantity -->
                                            <div class="flex items-center gap-2 text-xs">
                                                <span class="text-gray-300">Qty</span>
                                                <div
                                                    class="flex items-center border border-white/20 rounded-full overflow-hidden">
                                                    <button type="button"
                                                        class="qty-minus w-7 h-7 flex items-center justify-center text-lg text-gray-300 hover:bg-white/5">
                                                        -
                                                    </button>
                                                    <input type="number" min="1" value="<?php echo (int) $item['qty']; ?>"
                                                        class="qty-input w-10 text-center text-xs bg-transparent border-0 text-white focus:outline-none">
                                                    <button type="button"
                                                        class="qty-plus w-7 h-7 flex items-center justify-center text-lg text-gray-300 hover:bg-white/5">
                                                        +
                                                    </button>
                                                </div>
                                            </div>

                                            <!-- Line price -->
                                            <div class="text-right text-sm sm:text-base">
                                                <p class="font-semibold text-orange-400 line-total">
                                                    <?php echo $currency . number_format($item['price'] * $item['qty']); ?>
                                                </p>
                                                <p class="text-[11px] text-gray-500">
                                                    <?php echo $currency . number_format($item['price']); ?> each
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </section>

                        <!-- ORDER SUMMARY -->
                        <aside class="bg-[#111111] border border-white/10 rounded-2xl p-4 sm:p-5 h-max">
                            <h2 class="text-sm sm:text-base font-semibold mb-3">Order summary</h2>

                            <dl class="space-y-2 text-xs sm:text-sm text-gray-300">
                                <div class="flex justify-between">
                                    <dt>Subtotal</dt>
                                    <dd id="subtotalText">
                                        <?php echo $currency . number_format($subtotal); ?>
                                    </dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt>Estimated delivery</dt>
                                    <dd id="deliveryText">
                                        <?php echo $currency . number_format($deliveryEstimate); ?>
                                    </dd>
                                </div>
                                <div class="flex justify-between text-[11px] text-gray-400">
                                    <dt>Promo code</dt>
                                    <dd>Apply at checkout</dd>
                                </div>
                                <div class="border-t border-white/10 pt-2 mt-2 flex justify-between font-semibold text-sm">
                                    <dt>Total</dt>
                                    <dd id="totalText">
                                        <?php echo $currency . number_format($total); ?>
                                    </dd>
                                </div>
                            </dl>

                            <!-- Checkout button -->
                            <button id="checkoutBtn"
                                class="mt-4 w-full px-4 py-2.5 rounded-full text-sm font-semibold flex items-center justify-center gap-2"
                                style="background-color: var(--lt-orange);">
                                Proceed to checkout
                            </button>

                            <!-- Small note -->
                            <p class="mt-3 text-[11px] text-gray-400">
                                Payments are secured via LocalTrade escrow. You’ll review delivery options and address at
                                the next step.
                            </p>
                        </aside>
                    </div>
                <?php endif; ?>
            </div>
        </main>

        <!-- FOOTER -->
        <footer class="border-t border-white/10 bg-black mt-6">
            <div
                class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-5 text-xs text-gray-400 flex flex-col sm:flex-row gap-3 sm:items-center sm:justify-between">
                <p>© <span id="year"></span> LocalTrade. All rights reserved.</p>
                <div class="flex gap-4">
                    <a href="#" class="hover:text-orange-400">Privacy</a>
                    <a href="#" class="hover:text-orange-400">Terms</a>
                    <a href="#" class="hover:text-orange-400">Support</a>
                </div>
            </div>
        </footer>
    </div>

    <script>
        // Footer year
        document.getElementById('year').textContent = new Date().getFullYear();

        const currency = '<?php echo $currency; ?>';
        let delivery = <?php echo (int) $deliveryEstimate; ?>;

        // Helpers
        function formatMoney(n) {
            // basic thousands separator
            return n.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        }

        // Recalculate totals whenever cart changes (front-end only)
        function recalcTotals() {
            const items = document.querySelectorAll('.cart-item');
            let subtotal = 0;

            items.forEach(item => {
                const price = parseInt(item.dataset.price, 10);
                const qtyInput = item.querySelector('.qty-input');
                const lineTotalEl = item.querySelector('.line-total');
                const qty = Math.max(1, parseInt(qtyInput.value || '1', 10));

                const lineTotal = price * qty;
                subtotal += lineTotal;

                lineTotalEl.textContent = currency + formatMoney(lineTotal);
            });

            document.getElementById('subtotalText').textContent = currency + formatMoney(subtotal);

            const total = subtotal + delivery;
            document.getElementById('totalText').textContent = currency + formatMoney(total);

            // If no items left, show "empty" state (simple front-end version)
            if (items.length === 0) {
                alert('Your cart is now empty.');
                window.location.reload(); // in real app, you’d rerender via PHP
            }
        }

        // Quantity controls
        document.querySelectorAll('.cart-item').forEach(item => {
            const minusBtn = item.querySelector('.qty-minus');
            const plusBtn = item.querySelector('.qty-plus');
            const qtyInput = item.querySelector('.qty-input');

            minusBtn.addEventListener('click', () => {
                let v = parseInt(qtyInput.value || '1', 10);
                if (v > 1) v--;
                qtyInput.value = v;
                recalcTotals();
            });

            plusBtn.addEventListener('click', () => {
                let v = parseInt(qtyInput.value || '1', 10);
                v++;
                qtyInput.value = v;
                recalcTotals();
            });

            qtyInput.addEventListener('change', () => {
                let v = parseInt(qtyInput.value || '1', 10);
                if (isNaN(v) || v < 1) v = 1;
                qtyInput.value = v;
                recalcTotals();
            });
        });

        // Remove item
        document.querySelectorAll('.remove-item').forEach(btn => {
            btn.addEventListener('click', () => {
                const item = btn.closest('.cart-item');
                item.remove();
                recalcTotals();
            });
        });

        // Checkout button (demo)
        document.getElementById('checkoutBtn')?.addEventListener('click', () => {
            alert('Go to checkout page (implement redirect to checkout.php).');
        });

        // Initial calculation
        recalcTotals();
    </script>
</body>

</html>