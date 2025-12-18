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
                        Your cart is empty. <a href="index" class="text-orange-400 hover:underline">Start shopping</a>.
                    </div>
                <?php else: ?>

                    <div class="grid lg:grid-cols-[minmax(0,2fr)_minmax(280px,1fr)] gap-6 lg:gap-10">

                        <!-- CART ITEMS -->
                        <section class="space-y-3 sm:space-y-4">
                            <?php foreach ($cartItems as $index => $item): ?>
                                <div class="cart-item bg-[#111111] border border-white/10 rounded-2xl p-3 sm:p-4 flex gap-3 sm:gap-4"
                                    data-id="<?php echo $item['id']; ?>" data-price="<?php echo (int) $item['price']; ?>" id="cart-item-<?php echo $item['id']; ?>">
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
        </footer>
    </div>
    
    <!-- Custom Confirmation Modal -->
    <div id="confirmModal" class="fixed inset-0 z-50 flex items-center justify-center opacity-0 pointer-events-none transition-opacity duration-300">
        <!-- Backdrop -->
        <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" id="modalBackdrop"></div>
        
        <!-- Modal Content -->
        <div class="bg-[#1a1a1a] border border-white/10 rounded-2xl p-6 max-w-sm w-full mx-4 shadow-2xl transform scale-95 transition-transform duration-300" id="modalContent">
            <h3 class="text-lg font-semibold mb-2">Remove Item?</h3>
            <p class="text-sm text-gray-400 mb-6">
                Are you sure you want to remove this item from your cart? This action cannot be undone.
            </p>
            <div class="flex gap-3">
                <button id="modalCancel" class="flex-1 px-4 py-2.5 rounded-xl text-sm font-medium border border-white/10 hover:bg-white/5 transition">
                    Cancel
                </button>
                <button id="modalConfirm" class="flex-1 px-4 py-2.5 rounded-xl text-sm font-medium bg-red-500/10 text-red-400 hover:bg-red-500/20 border border-red-500/20 transition">
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
            messageDiv.className = `mb-2 p-3 rounded-xl border text-sm ${
                type === 'success' ? 'border-green-500/40 bg-green-500/10 text-green-200' :
                type === 'error' ? 'border-red-500/40 bg-red-500/10 text-red-200' :
                'border-blue-500/40 bg-blue-500/10 text-blue-200'
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