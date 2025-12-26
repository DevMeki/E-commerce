<?php
session_start();

// Include config (using require_once to ensure it's loaded)
require_once 'config.php';

// Check login
if (!isset($_SESSION['user']) || $_SESSION['user']['type'] !== 'buyer') {
    // Redirect to login if accessed directly without session
    header('Location: login.php?redirect=' . urlencode('cart.php'));
    exit;
}

$user_id = $_SESSION['user']['id'];
$cartItems = [];
$subtotal = 0;
$currency = '₦';
$deliveryEstimate = 2500; // Static for now

// Fetch cart items from DB
if (isset($conn) && $conn instanceof mysqli) {
    // Join with Product and Brand tables to get details
    $stmt = $conn->prepare("
        SELECT 
            c.id, 
            c.quantity as qty, 
            c.variants, 
            p.id as product_id,
            p.name, 
            p.price, 
            p.main_image, 
            b.brand_name as seller 
        FROM cart c 
        JOIN Product p ON c.product_id = p.id 
        JOIN Brand b ON p.brand_id = b.id 
        WHERE c.buyer_id = ?
        ORDER BY c.added_at DESC
    ");

    if ($stmt) {
        $stmt->bind_param('i', $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            // Parse variants JSON to string for display
            $variantStr = '';
            if (!empty($row['variants'])) {
                $variantsArr = json_decode($row['variants'], true);
                if (is_array($variantsArr)) {
                    $parts = [];
                    foreach ($variantsArr as $k => $v) {
                        $parts[] = ucfirst($k) . ': ' . $v;
                    }
                    $variantStr = implode(' · ', $parts);
                }
            }

            $row['variant'] = $variantStr;
            $row['currency'] = $currency;

            $cartItems[] = $row;
            $subtotal += $row['price'] * $row['qty'];
        }
        $stmt->close();
    }
}

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

<body class="bg-brand-parchment text-brand-ink font-sans">
    <div class="min-h-screen flex flex-col">

        <!-- HEADER -->
        <?php
        include 'header.php'; ?>

        <!-- MAIN -->
        <main class="flex-1 py-12">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="mb-8">
                    <h1 class="text-3xl font-bold text-brand-forest">Your Shopping Cart</h1>
                    <span class="block h-1 w-12 bg-brand-orange mt-2 rounded-full"></span>
                </div>

                <?php if (empty($cartItems)): ?>
                    <div class="bg-green-50 border border-brand-forest/5 rounded-3xl p-12 text-center shadow-sm">
                        <div class="w-20 h-20 bg-brand-forest/5 rounded-full flex items-center justify-center mx-auto mb-6">
                            <span class="text-4xl">🛒</span>
                        </div>
                        <h2 class="text-xl font-bold text-brand-forest mb-2">Your cart is feeling light</h2>
                        <p class="text-brand-ink/50 mb-8">It seems you haven't added anything to your cart yet.</p>
                        <a href="marketplace.php"
                            class="inline-flex px-8 py-3 bg-brand-orange text-white rounded-full font-bold shadow-lg shadow-brand-orange/20 hover:scale-[1.02] active:scale-[0.98] transition-all">
                            Start Exploring
                        </a>
                    </div>
                <?php else: ?>

                    <div class="grid lg:grid-cols-[minmax(0,2fr)_minmax(280px,1fr)] gap-6 lg:gap-10">

                        <!-- CART ITEMS -->
                        <section class="space-y-4">
                            <?php foreach ($cartItems as $index => $item): ?>
                                <div class="cart-item bg-green-50 border border-brand-forest/5 rounded-3xl p-4 sm:p-5 flex gap-4 sm:gap-6 shadow-sm hover:shadow-md transition-shadow"
                                    data-id="<?php echo $item['id']; ?>" data-price="<?php echo (int) $item['price']; ?>"
                                    id="cart-item-<?php echo $item['id']; ?>">
                                    <!-- Product image placeholder -->
                                    <div
                                        class="w-24 h-24 sm:w-28 sm:h-28 rounded-2xl bg-brand-parchment border border-brand-forest/5 flex items-center justify-center text-[10px] font-bold text-brand-forest/20 uppercase tracking-widest text-center overflow-hidden">
                                        <?php if (!empty($item['main_image'])): ?>
                                            <img src="<?php echo htmlspecialchars($item['main_image']); ?>"
                                                class="w-full h-full object-cover">
                                        <?php else: ?>
                                            <span>Local brand</span>
                                        <?php endif; ?>
                                    </div>

                                    <!-- Info -->
                                    <div class="flex-1 flex flex-col justify-between">
                                        <div class="flex items-start justify-between gap-4">
                                            <div>
                                                <p class="text-base sm:text-lg font-bold text-brand-forest leading-tight">
                                                    <?php echo htmlspecialchars($item['name']); ?>
                                                </p>
                                                <p class="text-xs font-medium text-brand-ink/40 mt-1 uppercase tracking-wider">
                                                    by <span
                                                        class="text-brand-forest"><?php echo htmlspecialchars($item['seller']); ?></span>
                                                </p>
                                                <?php if (!empty($item['variant'])): ?>
                                                    <p
                                                        class="text-[11px] font-medium text-brand-forest/60 bg-brand-forest/5 px-2 py-0.5 rounded mt-2 inline-block">
                                                        <?php echo htmlspecialchars($item['variant']); ?>
                                                    </p>
                                                <?php endif; ?>
                                            </div>
                                            <button type="button"
                                                class="remove-item text-[10px] font-bold text-red-400 hover:text-red-500 uppercase tracking-widest bg-red-50 px-3 py-1.5 rounded-full transition-colors">
                                                Remove
                                            </button>
                                        </div>

                                        <!-- Bottom row: quantity + price -->
                                        <div class="mt-3 flex flex-wrap items-center justify-between gap-3">
                                            <!-- Quantity -->
                                            <div class="flex items-center gap-2 text-xs">
                                                <span class="text-brand-ink/50">Qty</span>
                                                <div
                                                    class="flex items-center border border-brand-forest/10 rounded-full overflow-hidden">
                                                    <button type="button"
                                                        class="qty-minus w-7 h-7 flex items-center justify-center text-lg text-brand-ink/50 hover:bg-brand-forest/5">
                                                        -
                                                    </button>
                                                    <input type="number" min="1" value="<?php echo (int) $item['qty']; ?>"
                                                        class="qty-input w-10 text-center text-xs bg-transparent border-0 text-brand-ink focus:outline-none">
                                                    <button type="button"
                                                        class="qty-plus w-7 h-7 flex items-center justify-center text-lg text-brand-ink/50 hover:bg-brand-forest/5">
                                                        +
                                                    </button>
                                                </div>
                                            </div>

                                            <!-- Line price -->
                                            <div class="text-right text-sm sm:text-base">
                                                <p class="font-bold text-brand-forest line-total">
                                                    <?php echo $currency . number_format($item['price'] * $item['qty']); ?>
                                                </p>
                                                <p class="text-[11px] text-brand-ink/50">
                                                    <?php echo $currency . number_format($item['price']); ?> each
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </section>

                        <!-- ORDER SUMMARY -->
                        <aside class="bg-brand-forest rounded-3xl p-6 sm:p-8 text-white h-max shadow-xl sticky top-24">
                            <h2 class="text-lg font-bold mb-6 uppercase tracking-wider">Order summary</h2>

                            <dl class="space-y-4 text-sm mb-8">
                                <div class="flex justify-between items-baseline">
                                    <dt class="text-white/60 font-medium">Subtotal</dt>
                                    <dd id="subtotalText" class="font-bold">
                                        <?php echo $currency . number_format($subtotal); ?>
                                    </dd>
                                </div>
                                <div class="flex justify-between items-baseline">
                                    <dt class="text-white/60 font-medium">Estimated delivery</dt>
                                    <dd id="deliveryText" class="font-bold">
                                        <?php echo $currency . number_format($deliveryEstimate); ?>
                                    </dd>
                                </div>
                                <div class="flex justify-between items-baseline pb-4 border-b border-white/10">
                                    <dt class="text-white/60 font-medium">Promo code</dt>
                                    <dd class="text-[10px] font-bold uppercase tracking-widest text-brand-orange">Review at
                                        checkout</dd>
                                </div>
                                <div class="pt-4 flex justify-between items-baseline text-xl font-bold">
                                    <dt>Total</dt>
                                    <dd id="totalText" class="text-brand-orange">
                                        <?php echo $currency . number_format($total); ?>
                                    </dd>
                                </div>
                            </dl>

                            <!-- Checkout button -->
                            <button id="checkoutBtn"
                                class="w-full py-4 rounded-full text-sm font-bold bg-brand-orange text-white shadow-lg shadow-brand-orange/30 hover:scale-[1.02] active:scale-[0.98] transition-all">
                                Proceed to checkout
                            </button>

                            <!-- Small note -->
                            <p class="mt-6 text-[11px] text-white/40 leading-relaxed italic text-center">
                                Payments are secured via LocalTrade escrow. <br>You’ll review delivery options at the next
                                step.
                            </p>
                        </aside>
                    </div>
                <?php endif; ?>
            </div>
        </main>

        <!-- FOOTER -->
        <footer class="border-t border-brand-forest/5 bg-brand-cream/30 mt-12 py-8">
            <div
                class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 text-xs text-brand-ink/50 flex flex-col sm:flex-row gap-4 sm:items-center sm:justify-between">
                <p>© <span id="year"></span> LocalTrade. All rights reserved.</p>
                <div class="flex gap-6 font-medium">
                    <a href="#" class="hover:text-brand-orange transition-colors">Privacy</a>
                    <a href="#" class="hover:text-brand-orange transition-colors">Terms</a>
                    <a href="#" class="hover:text-brand-orange transition-colors">Support</a>
                </div>
            </div>
        </footer>
    </div>

    <!-- Custom Confirmation Modal -->
    <div id="confirmModal"
        class="fixed inset-0 z-50 flex items-center justify-center opacity-0 pointer-events-none transition-opacity duration-300">
        <!-- Backdrop -->
        <div class="absolute inset-0 bg-brand-forest/60 backdrop-blur-md" id="modalBackdrop"></div>

        <!-- Modal Content -->
        <div class="bg-white border border-brand-forest/5 rounded-3xl p-8 max-w-sm w-full mx-4 shadow-2xl transform scale-95 transition-transform duration-300"
            id="modalContent">
            <div class="w-16 h-16 bg-red-50 rounded-full flex items-center justify-center mb-6">
                <span class="text-2xl">🗑️</span>
            </div>
            <h3 class="text-xl font-bold text-brand-forest mb-2">Remove Item?</h3>
            <p class="text-sm text-brand-ink/50 mb-8 leading-relaxed">
                Are you sure you want to remove this from your cart? You can always find it again in the marketplace
                later.
            </p>
            <div class="flex gap-3">
                <button id="modalCancel"
                    class="flex-1 px-6 py-3 rounded-full text-xs font-bold border border-brand-forest/10 text-brand-forest hover:bg-brand-parchment transition-all">
                    Keep It
                </button>
                <button id="modalConfirm"
                    class="flex-1 px-6 py-3 rounded-full text-xs font-bold bg-red-500 text-white shadow-lg shadow-red-500/20 hover:scale-[1.05] transition-all">
                    Remove
                </button>
            </div>
        </div>
    </div>

    <!-- Messages container -->
    <div id="messageContainer" class="fixed top-4 right-4 z-50 max-w-sm"></div>

    <script>
        // Footer year
        document.getElementById('year').textContent = new Date().getFullYear();

        const currency = '<?php echo $currency; ?>';
        let delivery = <?php echo (int) $deliveryEstimate; ?>;

        // Helpers
        function formatMoney(n) {
            return n.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        }

        function showMessage(message, type = 'info') {
            const container = document.getElementById('messageContainer');

            const messageDiv = document.createElement('div');
            messageDiv.className = `mb-4 p-4 rounded-2xl border text-sm font-bold shadow-xl backdrop-blur-md transform animate-bounce-in ${type === 'success' ? 'border-brand-forest/20 bg-brand-forest text-white' :
                type === 'error' ? 'border-red-500/20 bg-red-500 text-white' :
                    'border-brand-orange/20 bg-brand-orange text-white'
                }`;
            messageDiv.textContent = message;

            container.appendChild(messageDiv);

            setTimeout(() => {
                messageDiv.remove();
            }, 3000);
        }

        async function updateCartItem(cartId, quantity, rowElement) {
            try {
                const formData = new FormData();
                formData.append('cart_id', cartId);
                formData.append('quantity', quantity);

                const response = await fetch('process/update-cart.php', {
                    method: 'POST',
                    body: formData
                });

                const result = await response.json();

                if (result.success) {
                    // Update subtotal
                    const subtotalEl = document.getElementById('subtotalText');
                    const totalEl = document.getElementById('totalText');

                    const newSubtotal = parseFloat(result.subtotal);
                    subtotalEl.textContent = currency + formatMoney(newSubtotal);
                    totalEl.textContent = currency + formatMoney(newSubtotal + delivery);

                    // Update line total
                    const price = parseInt(rowElement.dataset.price);
                    const lineTotalEl = rowElement.querySelector('.line-total');
                    lineTotalEl.textContent = currency + formatMoney(price * quantity);

                    // Optional: show subtle success or just update silently
                    // showMessage('Cart updated', 'success');
                } else {
                    showMessage(result.message || 'Update failed', 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                showMessage('Network error', 'error');
            }
        }

        // Modal state
        const modal = document.getElementById('confirmModal');
        const modalContent = document.getElementById('modalContent');
        const modalCancel = document.getElementById('modalCancel');
        const modalConfirm = document.getElementById('modalConfirm');
        const modalBackdrop = document.getElementById('modalBackdrop');
        let itemToRemove = null;
        let itemToRemoveId = null;

        function showModal(id, element) {
            itemToRemove = element;
            itemToRemoveId = id;
            modal.classList.remove('opacity-0', 'pointer-events-none');
            modalContent.classList.remove('scale-95');
            modalContent.classList.add('scale-100');
        }

        function hideModal() {
            modal.classList.add('opacity-0', 'pointer-events-none');
            modalContent.classList.remove('scale-100');
            modalContent.classList.add('scale-95');
            itemToRemove = null;
            itemToRemoveId = null;
        }

        modalCancel.addEventListener('click', hideModal);
        modalBackdrop.addEventListener('click', hideModal);

        modalConfirm.addEventListener('click', () => {
            if (itemToRemoveId && itemToRemove) {
                removeCartItem(itemToRemoveId, itemToRemove);
                hideModal();
            }
        });

        async function removeCartItem(cartId, rowElement) {
            try {
                const formData = new FormData();
                formData.append('cart_id', cartId);

                const response = await fetch('process/remove-from-cart.php', {
                    method: 'POST',
                    body: formData
                });

                const result = await response.json();

                if (result.success) {
                    // Animate removal
                    rowElement.style.transition = 'all 0.3s ease';
                    rowElement.style.opacity = '0';
                    rowElement.style.transform = 'translateX(20px)';

                    setTimeout(() => {
                        rowElement.remove();

                        // Update totals
                        const subtotalEl = document.getElementById('subtotalText');
                        const totalEl = document.getElementById('totalText');

                        const newSubtotal = parseFloat(result.subtotal);
                        subtotalEl.textContent = currency + formatMoney(newSubtotal);
                        totalEl.textContent = currency + formatMoney(newSubtotal + delivery);

                        // Check if empty
                        if (document.querySelectorAll('.cart-item').length === 0) {
                            location.reload();
                        }
                    }, 300);

                    showMessage('Item removed', 'success');
                } else {
                    showMessage(result.message || 'Removal failed', 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                showMessage('Network error', 'error');
            }
        }

        // Attach listeners
        document.querySelectorAll('.cart-item').forEach(item => {
            const minusBtn = item.querySelector('.qty-minus');
            const plusBtn = item.querySelector('.qty-plus');
            const qtyInput = item.querySelector('.qty-input');
            const removeBtn = item.querySelector('.remove-item');
            const cartId = item.dataset.id;

            minusBtn.addEventListener('click', () => {
                let v = parseInt(qtyInput.value || '1', 10);
                if (v > 1) {
                    v--;
                    qtyInput.value = v;
                    updateCartItem(cartId, v, item);
                }
            });

            plusBtn.addEventListener('click', () => {
                let v = parseInt(qtyInput.value || '1', 10);
                if (v < 100) { // arbitrary max
                    v++;
                    qtyInput.value = v;
                    updateCartItem(cartId, v, item);
                }
            });

            qtyInput.addEventListener('change', () => {
                let v = parseInt(qtyInput.value || '1', 10);
                if (isNaN(v) || v < 1) v = 1;
                qtyInput.value = v;
                updateCartItem(cartId, v, item);
            });

            removeBtn.addEventListener('click', () => {
                showModal(cartId, item);
            });
        });

        // Checkout button
        document.getElementById('checkoutBtn')?.addEventListener('click', () => {
            window.location.href = 'checkout.php';
        });
    </script>
</body>

</html>