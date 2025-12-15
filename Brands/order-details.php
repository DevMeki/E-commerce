<?php
// --------- MOCK DATA (replace with DB lookup later) ---------
function moneyNaira($n) {
    return '₦' . number_format($n);
}

// Example orders. In production, query from DB using $orderId.
$orders = [
    '#LT-1024' => [
        'id'         => '#LT-1024',
        'status'     => 'Paid',
        'created_at' => '2025-02-10 09:21',
        'when_label' => '2h ago',
        'total'      => 18500,
        'subtotal'   => 16000,
        'shipping'   => 2500,
        'payment'    => [
            'method'    => 'Card',
            'reference' => 'PAY-9XK12345',
            'channel'   => 'Flutterwave',
        ],
        'customer'   => [
            'name'    => 'Chidi Okafor',
            'email'   => 'chidi@example.com',
            'phone'   => '+2348012345678',
        ],
        'shipping'   => [
            'name'      => 'Chidi Okafor',
            'address1'  => '12 Freedom Street',
            'address2'  => 'Yaba',
            'city'      => 'Lagos',
            'state'     => 'Lagos',
            'country'   => 'Nigeria',
            'note'      => 'Call on arrival, estate gate is busy.',
        ],
        'items'      => [
            [
                'name'       => 'Ankara Panel Hoodie',
                'variant'    => 'Size L · Black/Orange',
                'unit_price' => 12000,
                'qty'        => 1,
            ],
            [
                'name'       => 'Naija Drip Tee',
                'variant'    => 'Size M · Black',
                'unit_price' => 4000,
                'qty'        => 1,
            ],
        ],
    ],
    '#LT-1023' => [
        'id'         => '#LT-1023',
        'status'     => 'Shipped',
        'created_at' => '2025-02-10 06:05',
        'when_label' => '5h ago',
        'total'      => 7500,
        'subtotal'   => 6000,
        'shipping'   => 1500,
        'payment'    => [
            'method'    => 'Card',
            'reference' => 'PAY-ABCD5678',
            'channel'   => 'Paystack',
        ],
        'customer'   => [
            'name'    => 'Amaka John',
            'email'   => 'amaka@example.com',
            'phone'   => '+2348098765432',
        ],
        'shipping'   => [
            'name'      => 'Amaka John',
            'address1'  => '22 Unity Close',
            'address2'  => 'Wuse 2',
            'city'      => 'Abuja',
            'state'     => 'FCT',
            'country'   => 'Nigeria',
            'note'      => '',
        ],
        'items'      => [
            [
                'name'       => 'Naija Drip Tee',
                'variant'    => 'Size L · White',
                'unit_price' => 6000,
                'qty'        => 1,
            ],
        ],
    ],
];

// Get order ID from query
$orderId = $_GET['id'] ?? '';
$orderId = urldecode($orderId);
$order   = $orders[$orderId] ?? null;

// Flash message for status updates
$statusMessage = '';
$statusMessageClass = '';

// Handle status update POST (demo only – no real DB)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $order) {
    $action    = $_POST['status_action'] ?? null;
    $newStatus = null;
    $allowed   = ['Processing', 'Paid', 'Shipped', 'Delivered', 'Cancelled'];

    if ($action === 'set_from_dropdown') {
        $selected = $_POST['order_status'] ?? '';
        if (in_array($selected, $allowed, true)) {
            $newStatus = $selected;
        } else {
            $statusMessage      = 'Invalid status selected.';
            $statusMessageClass = 'bg-red-500/10 border-red-500/40 text-red-200';
        }
    } elseif ($action === 'mark_shipped') {
        $newStatus = 'Shipped';
    } elseif ($action === 'mark_delivered') {
        $newStatus = 'Delivered';
    }

    if ($newStatus !== null) {
        // Update current order (demo – in real app, update DB)
        $order['status']     = $newStatus;
        $orders[$orderId]    = $order;

        $statusMessage      = "Order status updated to {$newStatus}.";
        $statusMessageClass = 'bg-green-500/10 border-green-500/40 text-green-200';
    }
}

// Map status to timeline completion
$timelineSteps = ['Order placed', 'Payment confirmed', 'Shipped', 'Delivered'];

$statusToStepIndex = [
    'processing' => 0,
    'paid'       => 1,
    'shipped'    => 2,
    'delivered'  => 3,
    'cancelled'  => 2, // up to "Shipped", then cancelled style
];

// For badge styling
function statusBadgeClass(string $status): string {
    $k = strtolower($status);
    return match ($k) {
        'paid'       => 'bg-emerald-500/15 text-emerald-300 border-emerald-500/40',
        'shipped'    => 'bg-blue-500/15 text-blue-300 border-blue-500/40',
        'delivered'  => 'bg-gray-500/15 text-gray-200 border-gray-500/40',
        'processing' => 'bg-amber-500/15 text-amber-300 border-amber-500/40',
        'cancelled'  => 'bg-red-500/15 text-red-300 border-red-500/40',
        default      => 'bg-white/10 text-gray-200 border-white/20',
    };
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order Details | LocalTrade</title>
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

<!-- HEADER -->
<?php
    // $currentBrandPage = 'products';
    include 'brand-header.php';
    ?>

<!-- MAIN -->
<main class="flex-1 py-6 sm:py-8">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-5 sm:space-y-6">

        <!-- If order not found -->
        <?php if (!$order): ?>
            <section class="bg-[#111111] border border-white/10 rounded-2xl p-6 sm:p-8 text-center text-sm">
                <p class="text-lg font-semibold mb-2">Order not found</p>
                <p class="text-xs text-gray-400 mb-4">
                    We couldn’t find any order with ID
                    <span class="font-mono text-gray-200"><?= htmlspecialchars($orderId ?: '(none provided)'); ?></span>.
                </p>
                <a href="orders" class="inline-flex items-center gap-2 px-4 py-2 rounded-full text-xs font-semibold border border-white/20 bg-[#0B0B0B] hover:border-orange-400">
                    ← Back to orders
                </a>
            </section>
        <?php else: ?>

            <?php
            $statusKey      = strtolower($order['status'] ?? '');
            $completedIndex = $statusToStepIndex[$statusKey] ?? 0;
            $isCancelled    = $statusKey === 'cancelled';
            ?>

            <!-- Flash message for status update -->
            <?php if ($statusMessage): ?>
                <div class="bg-[#111111] border rounded-2xl px-3 py-2 text-xs <?= $statusMessageClass; ?>">
                    <?= htmlspecialchars($statusMessage); ?>
                </div>
            <?php endif; ?>

            <!-- Breadcrumb + title -->
            <section class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <div class="flex items-center gap-2 text-[11px] text-gray-500 mb-1">
                        <a href="orders" class="hover:text-orange-400">Orders</a>
                        <span>/</span>
                        <span class="text-gray-300"><?= htmlspecialchars($order['id']); ?></span>
                    </div>
                    <h1 class="text-xl sm:text-2xl font-semibold">
                        Order <?= htmlspecialchars($order['id']); ?>
                    </h1>
                    <p class="text-xs sm:text-sm text-gray-400 mt-1">
                        Placed <?= htmlspecialchars($order['when_label']); ?> ·
                        <?= htmlspecialchars($order['created_at']); ?>
                    </p>
                </div>

                <div class="flex flex-wrap items-center gap-3 text-xs sm:text-sm">
                    <span class="inline-flex px-3 py-1 rounded-full border <?= statusBadgeClass($order['status']); ?>">
                        <?= htmlspecialchars($order['status']); ?>
                    </span>
                    <span class="px-3 py-1.5 rounded-xl bg-[#111111] border border-white/10 text-xs">
                        Total:
                        <span class="font-semibold text-orange-400 ml-1">
                            <?= moneyNaira($order['total']); ?>
                        </span>
                    </span>
                </div>
            </section>

            <!-- Top: timeline + summary + manage -->
            <section class="grid lg:grid-cols-[minmax(0,2fr)_minmax(260px,1fr)] gap-4 lg:gap-6">
                <!-- Timeline -->
                <div class="bg-[#111111] border border-white/10 rounded-2xl p-4 sm:p-5 text-xs sm:text-sm">
                    <h2 class="text-sm font-semibold mb-3">Order timeline</h2>

                    <div class="flex items-center justify-between gap-2">
                        <?php foreach ($timelineSteps as $index => $label): ?>
                            <?php
                            $active = $index <= $completedIndex;
                            $dotClasses = $active
                                ? 'bg-orange-500 text-black'
                                : 'bg-[#0B0B0B] border border-white/20 text-gray-400';
                            ?>
                            <div class="flex-1 flex flex-col items-center gap-2">
                                <div class="flex items-center gap-2 w-full">
                                    <div class="flex items-center justify-center w-7 h-7 rounded-full text-[11px] font-semibold <?= $dotClasses; ?>">
                                        <?= $index + 1; ?>
                                    </div>
                                    <?php if ($index < count($timelineSteps) - 1): ?>
                                        <div class="flex-1 h-px <?= $active ? 'bg-orange-500' : 'bg-white/15'; ?>"></div>
                                    <?php endif; ?>
                                </div>
                                <span class="text-[11px] text-center <?= $active ? 'text-gray-100' : 'text-gray-500'; ?>">
                                    <?= $label; ?>
                                </span>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <?php if ($isCancelled): ?>
                        <p class="mt-4 text-[11px] text-red-300">
                            This order has been cancelled. No further fulfilment is required.
                        </p>
                    <?php else: ?>
                        <p class="mt-4 text-[11px] text-gray-400">
                            Status updates from this page are for demo only. In production, connect this to your order management backend.
                        </p>
                    <?php endif; ?>
                </div>

                <!-- Right column: Payment summary + Manage order -->
                <div class="space-y-4">
                    <!-- Summary card -->
                    <div class="bg-[#111111] border border-white/10 rounded-2xl p-4 sm:p-5 text-xs sm:text-sm">
                        <h2 class="text-sm font-semibold mb-3">Payment summary</h2>

                        <div class="space-y-2 text-[11px] sm:text-xs">
                            <div class="flex justify-between">
                                <span class="text-gray-400">Subtotal</span>
                                <span class="text-gray-100"><?= moneyNaira($order['subtotal']); ?></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-400">Shipping</span>
                                <span class="text-gray-100"><?= moneyNaira($order['shipping']); ?></span>
                            </div>
                            <div class="flex justify-between pt-2 border-t border-white/10 mt-1">
                                <span class="text-gray-400">Total</span>
                                <span class="font-semibold text-orange-400"><?= moneyNaira($order['total']); ?></span>
                            </div>
                        </div>

                        <div class="mt-4 space-y-1 text-[11px] sm:text-xs text-gray-300">
                            <div class="flex justify-between">
                                <span class="text-gray-400">Payment method</span>
                                <span><?= htmlspecialchars($order['payment']['method']); ?></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-400">Payment channel</span>
                                <span><?= htmlspecialchars($order['payment']['channel']); ?></span>
                            </div>
                            <div class="flex flex-col mt-1">
                                <span class="text-gray-400">Reference</span>
                                <span class="font-mono text-xs text-gray-200">
                                    <?= htmlspecialchars($order['payment']['reference']); ?>
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Manage order -->
                    <div class="bg-[#111111] border border-white/10 rounded-2xl p-4 sm:p-5 text-xs sm:text-sm">
                        <h2 class="text-sm font-semibold mb-3">Manage order</h2>

                        <form method="post" class="space-y-3">
                            <!-- Status dropdown -->
                            <div>
                                <label class="block text-[11px] text-gray-400 mb-1">Update status</label>
                                <select
                                    name="order_status"
                                    class="w-full bg-[#0B0B0B] border border-white/20 rounded-full px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-orange-500"
                                >
                                    <?php
                                    $options = ['Processing', 'Paid', 'Shipped', 'Delivered', 'Cancelled'];
                                    foreach ($options as $opt):
                                        $sel = ($order['status'] === $opt) ? 'selected' : '';
                                        ?>
                                        <option value="<?= htmlspecialchars($opt); ?>" <?= $sel; ?>>
                                            <?= $opt; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <!-- Buttons -->
                            <div class="flex flex-wrap gap-2">
                                <button
                                    type="submit"
                                    name="status_action"
                                    value="set_from_dropdown"
                                    class="px-3 py-2 rounded-full text-xs font-semibold border border-white/20 bg-[#0B0B0B] hover:border-orange-400"
                                >
                                    Save status
                                </button>

                                <button
                                    type="submit"
                                    name="status_action"
                                    value="mark_shipped"
                                    class="px-3 py-2 rounded-full text-xs font-semibold bg-blue-500/90 text-black hover:bg-blue-500"
                                >
                                    Mark as Shipped
                                </button>

                                <button
                                    type="submit"
                                    name="status_action"
                                    value="mark_delivered"
                                    class="px-3 py-2 rounded-full text-xs font-semibold bg-emerald-500/90 text-black hover:bg-emerald-500"
                                >
                                    Mark as Delivered
                                </button>
                            </div>

                            <p class="text-[10px] text-gray-500">
                                In a real app, these actions would trigger backend updates and notify the customer.
                            </p>
                        </form>
                    </div>
                </div>
            </section>

            <!-- Middle: customer & shipping info -->
            <section class="grid lg:grid-cols-2 gap-4 lg:gap-6">
                <!-- Customer -->
                <div class="bg-[#111111] border border-white/10 rounded-2xl p-4 sm:p-5 text-xs sm:text-sm">
                    <h2 class="text-sm font-semibold mb-3">Customer</h2>
                    <div class="space-y-2">
                        <div>
                            <p class="text-gray-100"><?= htmlspecialchars($order['customer']['name']); ?></p>
                            <p class="text-[11px] text-gray-400">
                                <?= htmlspecialchars($order['customer']['email']); ?>
                            </p>
                        </div>
                        <div class="text-[11px] text-gray-300">
                            <span class="text-gray-400">Phone:</span>
                            <span class="ml-1"><?= htmlspecialchars($order['customer']['phone']); ?></span>
                        </div>
                    </div>
                </div>

                <!-- Shipping -->
                <div class="bg-[#111111] border border-white/10 rounded-2xl p-4 sm:p-5 text-xs sm:text-sm">
                    <h2 class="text-sm font-semibold mb-3">Shipping</h2>
                    <div class="space-y-2">
                        <p class="text-gray-100"><?= htmlspecialchars($order['shipping']['name']); ?></p>
                        <p class="text-[11px] text-gray-300 leading-snug">
                            <?= htmlspecialchars($order['shipping']['address1']); ?><br>
                            <?php if (!empty($order['shipping']['address2'])): ?>
                                <?= htmlspecialchars($order['shipping']['address2']); ?><br>
                            <?php endif; ?>
                            <?= htmlspecialchars($order['shipping']['city']); ?>,
                            <?= htmlspecialchars($order['shipping']['state']); ?><br>
                            <?= htmlspecialchars($order['shipping']['country']); ?>
                        </p>

                        <?php if (!empty($order['shipping']['note'])): ?>
                            <div class="mt-2">
                                <p class="text-[11px] text-gray-400 mb-1">Delivery notes</p>
                                <p class="text-[11px] text-gray-300">
                                    <?= htmlspecialchars($order['shipping']['note']); ?>
                                </p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </section>

            <!-- Items -->
            <section class="bg-[#111111] border border-white/10 rounded-2xl p-4 sm:p-5 text-xs sm:text-sm">
                <div class="flex items-center justify-between mb-3">
                    <h2 class="text-sm font-semibold">Items in this order</h2>
                    <span class="text-[11px] text-gray-400">
                        <?= count($order['items']); ?> product<?= count($order['items']) > 1 ? 's' : ''; ?>
                    </span>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full border-separate border-spacing-y-2">
                        <thead class="text-[11px] text-gray-400">
                        <tr>
                            <th class="text-left pr-3 pb-1">Product</th>
                            <th class="text-left pr-3 pb-1">Variant</th>
                            <th class="text-left pr-3 pb-1">Qty</th>
                            <th class="text-left pr-3 pb-1">Unit price</th>
                            <th class="text-left pb-1">Subtotal</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($order['items'] as $item): ?>
                            <tr class="bg-[#0B0B0B] border border-white/10 rounded-xl">
                                <td class="px-3 py-2 rounded-l-xl align-top">
                                    <span class="text-gray-100">
                                        <?= htmlspecialchars($item['name']); ?>
                                    </span>
                                </td>
                                <td class="px-3 py-2 align-top">
                                    <span class="text-[11px] text-gray-400">
                                        <?= htmlspecialchars($item['variant']); ?>
                                    </span>
                                </td>
                                <td class="px-3 py-2 align-top">
                                    <span class="text-gray-100"><?= (int)$item['qty']; ?></span>
                                </td>
                                <td class="px-3 py-2 align-top">
                                    <span class="text-gray-100">
                                        <?= moneyNaira($item['unit_price']); ?>
                                    </span>
                                </td>
                                <td class="px-3 py-2 rounded-r-xl align-top">
                                    <span class="font-semibold text-orange-400">
                                        <?= moneyNaira($item['unit_price'] * $item['qty']); ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </section>

        <?php endif; ?>
    </div>
</main>

</body>
</html>
