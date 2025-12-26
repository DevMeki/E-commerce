<?php
require_once 'process/check_brand_login.php';
// --------- MOCK DATA (replace with DB lookup later) ---------
function moneyNaira($n)
{
    return '₦' . number_format($n);
}

// Example orders. In production, query from DB using $orderId.
$orders = [
    '#LT-1024' => [
        'id' => '#LT-1024',
        'status' => 'Paid',
        'created_at' => '2025-02-10 09:21',
        'when_label' => '2h ago',
        'total' => 18500,
        'subtotal' => 16000,
        'shipping' => 2500,
        'payment' => [
            'method' => 'Card',
            'reference' => 'PAY-9XK12345',
            'channel' => 'Flutterwave',
        ],
        'customer' => [
            'name' => 'Chidi Okafor',
            'email' => 'chidi@example.com',
            'phone' => '+2348012345678',
        ],
        'shipping' => [
            'name' => 'Chidi Okafor',
            'address1' => '12 Freedom Street',
            'address2' => 'Yaba',
            'city' => 'Lagos',
            'state' => 'Lagos',
            'country' => 'Nigeria',
            'note' => 'Call on arrival, estate gate is busy.',
        ],
        'items' => [
            [
                'name' => 'Ankara Panel Hoodie',
                'variant' => 'Size L · Black/Orange',
                'unit_price' => 12000,
                'qty' => 1,
            ],
            [
                'name' => 'Naija Drip Tee',
                'variant' => 'Size M · Black',
                'unit_price' => 4000,
                'qty' => 1,
            ],
        ],
    ],
    '#LT-1023' => [
        'id' => '#LT-1023',
        'status' => 'Shipped',
        'created_at' => '2025-02-10 06:05',
        'when_label' => '5h ago',
        'total' => 7500,
        'subtotal' => 6000,
        'shipping' => 1500,
        'payment' => [
            'method' => 'Card',
            'reference' => 'PAY-ABCD5678',
            'channel' => 'Paystack',
        ],
        'customer' => [
            'name' => 'Amaka John',
            'email' => 'amaka@example.com',
            'phone' => '+2348098765432',
        ],
        'shipping' => [
            'name' => 'Amaka John',
            'address1' => '22 Unity Close',
            'address2' => 'Wuse 2',
            'city' => 'Abuja',
            'state' => 'FCT',
            'country' => 'Nigeria',
            'note' => '',
        ],
        'items' => [
            [
                'name' => 'Naija Drip Tee',
                'variant' => 'Size L · White',
                'unit_price' => 6000,
                'qty' => 1,
            ],
        ],
    ],
];

// Get order ID from query
$orderId = $_GET['id'] ?? '';
$orderId = urldecode($orderId);
$order = $orders[$orderId] ?? null;

// Flash message for status updates
$statusMessage = '';
$statusMessageClass = '';

// Handle status update POST (demo only – no real DB)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $order) {
    $action = $_POST['status_action'] ?? null;
    $newStatus = null;
    $allowed = ['Processing', 'Paid', 'Shipped', 'Delivered', 'Cancelled'];

    if ($action === 'set_from_dropdown') {
        $selected = $_POST['order_status'] ?? '';
        if (in_array($selected, $allowed, true)) {
            $newStatus = $selected;
        } else {
            $statusMessage = 'Invalid status selected.';
            $statusMessageClass = 'bg-red-500/10 border-red-500/40 text-red-200';
        }
    } elseif ($action === 'mark_shipped') {
        $newStatus = 'Shipped';
    } elseif ($action === 'mark_delivered') {
        $newStatus = 'Delivered';
    }

    if ($newStatus !== null) {
        // Update current order (demo – in real app, update DB)
        $order['status'] = $newStatus;
        $orders[$orderId] = $order;

        $statusMessage = "Order status updated to {$newStatus}.";
        $statusMessageClass = 'bg-green-500/10 border-green-500/40 text-green-200';
    }
}

// Map status to timeline completion
$timelineSteps = ['Order placed', 'Payment confirmed', 'Shipped', 'Delivered'];

$statusToStepIndex = [
    'processing' => 0,
    'paid' => 1,
    'shipped' => 2,
    'delivered' => 3,
    'cancelled' => 2, // up to "Shipped", then cancelled style
];

// For badge styling
function statusBadgeClass(string $status): string
{
    $k = strtolower($status);
    return match ($k) {
        'paid' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
        'shipped' => 'bg-blue-50 text-blue-700 border-blue-200',
        'delivered' => 'bg-gray-50 text-gray-700 border-gray-200',
        'processing' => 'bg-amber-50 text-amber-700 border-amber-200',
        'cancelled' => 'bg-red-50 text-red-700 border-red-200',
        default => 'bg-brand-parchment text-brand-ink/60 border-brand-forest/10',
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
                <section
                    class="bg-white border border-brand-forest/10 rounded-2xl p-6 sm:p-8 text-center text-sm shadow-sm">
                    <p class="text-lg font-semibold mb-2 text-brand-forest">Order not found</p>
                    <p class="text-xs text-brand-ink/50 mb-4">
                        We couldn’t find any order with ID
                        <span
                            class="font-mono text-brand-forest font-bold"><?= htmlspecialchars($orderId ?: '(none provided)'); ?></span>.
                    </p>
                    <a href="orders"
                        class="inline-flex items-center gap-2 px-5 py-2 rounded-full text-xs font-bold border border-brand-forest/10 bg-white text-brand-forest hover:border-brand-orange transition-all">
                        ← Back to orders
                    </a>
                </section>
            <?php else: ?>

                <?php
                $statusKey = strtolower($order['status'] ?? '');
                $completedIndex = $statusToStepIndex[$statusKey] ?? 0;
                $isCancelled = $statusKey === 'cancelled';
                ?>

                <!-- Flash message for status update -->
                <?php if ($statusMessage): ?>
                    <div class="bg-white border rounded-2xl px-4 py-3 text-xs shadow-sm <?= $statusMessageClass; ?>">
                        <?= htmlspecialchars($statusMessage); ?>
                    </div>
                <?php endif; ?>

                <!-- Breadcrumb + title -->
                <section class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <div class="flex items-center gap-2 text-[11px] text-brand-ink/40 mb-1">
                            <a href="orders" class="hover:text-brand-orange transition-colors">Orders</a>
                            <span>/</span>
                            <span class="text-brand-ink/60"><?= htmlspecialchars($order['id']); ?></span>
                        </div>
                        <h1 class="text-xl sm:text-2xl font-semibold text-brand-forest">
                            Order <?= htmlspecialchars($order['id']); ?>
                        </h1>
                        <p class="text-xs sm:text-sm text-brand-ink/50 mt-1">
                            Placed <?= htmlspecialchars($order['when_label']); ?> ·
                            <?= htmlspecialchars($order['created_at']); ?>
                        </p>
                    </div>

                    <div class="flex flex-wrap items-center gap-3 text-xs sm:text-sm">
                        <span
                            class="inline-flex px-3 py-1 rounded-full border text-[10px] font-bold uppercase tracking-wider <?= statusBadgeClass($order['status']); ?>">
                            <?= htmlspecialchars($order['status']); ?>
                        </span>
                        <span
                            class="px-4 py-2 rounded-xl bg-green-50 border border-brand-forest/5 text-xs text-brand-ink/70 shadow-sm font-medium">
                            Total:
                            <span class="font-bold text-brand-orange ml-1">
                                <?= moneyNaira($order['total']); ?>
                            </span>
                        </span>
                    </div>
                </section>

                <!-- Top: timeline + summary + manage -->
                <section class="grid lg:grid-cols-[minmax(0,2fr)_minmax(260px,1fr)] gap-4 lg:gap-6">
                    <!-- Timeline -->
                    <div
                        class="bg-green-50 border border-brand-forest/5 rounded-2xl p-4 sm:p-5 text-xs sm:text-sm shadow-sm">
                        <h2 class="text-sm font-semibold mb-4 text-brand-forest">Order timeline</h2>

                        <div class="flex items-center justify-between gap-2 max-w-lg mx-auto lg:mx-0">
                            <?php foreach ($timelineSteps as $index => $label): ?>
                                <?php
                                $active = $index <= $completedIndex;
                                $dotClasses = $active
                                    ? 'bg-brand-orange text-white'
                                    : 'bg-white border border-brand-forest/10 text-brand-ink/30';
                                ?>
                                <div class="flex-1 flex flex-col items-center gap-2">
                                    <div class="flex items-center gap-2 w-full">
                                        <div
                                            class="flex items-center justify-center w-7 h-7 rounded-full text-[10px] font-bold <?= $dotClasses; ?> shadow-sm">
                                            <?= $index + 1; ?>
                                        </div>
                                        <?php if ($index < count($timelineSteps) - 1): ?>
                                            <div class="flex-1 h-0.5 <?= $active ? 'bg-brand-orange' : 'bg-brand-forest/5'; ?>">
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <span
                                        class="text-[10px] text-center leading-tight <?= $active ? 'text-brand-forest font-bold' : 'text-brand-ink/40 font-medium'; ?>">
                                        <?= $label; ?>
                                    </span>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <?php if ($isCancelled): ?>
                            <p class="mt-5 text-[11px] text-red-600 font-medium bg-red-50 p-2 rounded-lg border border-red-100">
                                This order has been cancelled. No further fulfilment is required.
                            </p>
                        <?php else: ?>
                            <p class="mt-5 text-[11px] text-brand-ink/40 italic">
                                Status updates from this page are for demo only. In production, connect this to your order
                                management backend.
                            </p>
                        <?php endif; ?>
                    </div>

                    <!-- Right column: Payment summary + Manage order -->
                    <div class="space-y-4">
                        <!-- Summary card -->
                        <div
                            class="bg-green-50 border border-brand-forest/5 rounded-2xl p-4 sm:p-5 text-xs sm:text-sm shadow-sm">
                            <h2 class="text-sm font-semibold mb-3 text-brand-forest">Payment summary</h2>

                            <div class="space-y-2 text-[11px] sm:text-xs">
                                <div class="flex justify-between">
                                    <span class="text-brand-ink/50 uppercase tracking-tighter font-bold">Subtotal</span>
                                    <span class="text-brand-ink font-medium"><?= moneyNaira($order['subtotal']); ?></span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-brand-ink/50 uppercase tracking-tighter font-bold">Shipping</span>
                                    <span class="text-brand-ink font-medium"><?= moneyNaira($order['shipping']); ?></span>
                                </div>
                                <div class="flex justify-between pt-2 border-t border-brand-forest/5 mt-1">
                                    <span class="text-brand-ink/70 font-bold">Total</span>
                                    <span
                                        class="font-bold text-brand-orange text-sm"><?= moneyNaira($order['total']); ?></span>
                                </div>
                            </div>

                            <div
                                class="mt-4 space-y-1.5 text-[10px] sm:text-[11px] text-brand-ink/60 border-t border-brand-forest/5 pt-3">
                                <div class="flex justify-between">
                                    <span class="text-brand-ink/40">Payment method</span>
                                    <span
                                        class="text-brand-forest font-semibold"><?= htmlspecialchars($order['payment']['method']); ?></span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-brand-ink/40">Payment channel</span>
                                    <span
                                        class="text-brand-forest font-semibold"><?= htmlspecialchars($order['payment']['channel']); ?></span>
                                </div>
                                <div class="flex flex-col mt-1">
                                    <span class="text-brand-ink/40">Reference</span>
                                    <span
                                        class="font-mono text-[10px] text-brand-ink/70 bg-brand-forest/5 px-2 py-0.5 rounded-lg mt-1 w-fit">
                                        <?= htmlspecialchars($order['payment']['reference']); ?>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Manage order -->
                        <div
                            class="bg-white border border-brand-forest/10 rounded-2xl p-4 sm:p-5 text-xs sm:text-sm shadow-sm">
                            <h2 class="text-sm font-semibold mb-3 text-brand-forest">Manage order</h2>

                            <form method="post" class="space-y-4">
                                <!-- Status dropdown -->
                                <div>
                                    <label
                                        class="block text-[11px] text-brand-ink/50 font-medium mb-1.5 uppercase tracking-tight">Update
                                        status</label>
                                    <select name="order_status"
                                        class="w-full bg-brand-parchment border border-brand-forest/10 rounded-full px-4 py-2 text-xs text-brand-ink focus:outline-none focus:ring-1 focus:ring-brand-orange">
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
                                <div class="flex flex-col gap-2">
                                    <button type="submit" name="status_action" value="set_from_dropdown"
                                        class="w-full px-4 py-2.5 rounded-full text-xs font-bold border border-brand-forest/10 bg-white text-brand-forest hover:border-brand-orange transition-all">
                                        Save status
                                    </button>

                                    <div class="grid grid-cols-2 gap-2">
                                        <button type="submit" name="status_action" value="mark_shipped"
                                            class="px-3 py-2.5 rounded-full text-[10px] font-bold bg-blue-600 text-white hover:bg-blue-700 shadow-sm shadow-blue-500/20 transition-all uppercase tracking-tight">
                                            Mark Shipped
                                        </button>

                                        <button type="submit" name="status_action" value="mark_delivered"
                                            class="px-3 py-2.5 rounded-full text-[10px] font-bold bg-emerald-600 text-white hover:bg-emerald-700 shadow-sm shadow-emerald-500/20 transition-all uppercase tracking-tight">
                                            Mark Delivered
                                        </button>
                                    </div>
                                </div>

                                <p class="text-[10px] text-brand-ink/40 leading-relaxed italic">
                                    Note: These actions will notify the customer via email and push notification.
                                </p>
                            </form>
                        </div>
                    </div>
                </section>

                <!-- Middle: customer & shipping info -->
                <section class="grid lg:grid-cols-2 gap-4 lg:gap-6">
                    <!-- Customer -->
                    <div
                        class="bg-green-50 border border-brand-forest/5 rounded-2xl p-4 sm:p-5 text-xs sm:text-sm shadow-sm">
                        <h2 class="text-sm font-semibold mb-3 text-brand-forest">Customer</h2>
                        <div class="space-y-3">
                            <div>
                                <p class="text-brand-ink font-bold"><?= htmlspecialchars($order['customer']['name']); ?></p>
                                <p class="text-[11px] text-brand-ink/50 mt-0.5">
                                    <?= htmlspecialchars($order['customer']['email']); ?>
                                </p>
                            </div>
                            <div
                                class="text-[11px] bg-brand-forest/5 rounded-xl px-3 py-2 flex items-center justify-between border border-brand-forest/5">
                                <span class="text-brand-ink/40 font-medium">WhatsApp / Phone:</span>
                                <span
                                    class="text-brand-forest font-bold"><?= htmlspecialchars($order['customer']['phone']); ?></span>
                            </div>
                        </div>
                    </div>

                    <!-- Shipping -->
                    <div
                        class="bg-green-50 border border-brand-forest/5 rounded-2xl p-4 sm:p-5 text-xs sm:text-sm shadow-sm shadow-brand-forest/5">
                        <h2 class="text-sm font-semibold mb-3 text-brand-forest">Shipping</h2>
                        <div class="space-y-3">
                            <p class="text-brand-ink font-bold"><?= htmlspecialchars($order['shipping']['name']); ?>
                            </p>
                            <p
                                class="text-[11px] text-brand-ink/60 leading-relaxed bg-white/50 p-3 rounded-xl border border-brand-forest/5">
                                <?= htmlspecialchars($order['shipping']['address1']); ?><br>
                                <?php if (!empty($order['shipping']['address2'])): ?>
                                    <?= htmlspecialchars($order['shipping']['address2']); ?><br>
                                <?php endif; ?>
                                <?= htmlspecialchars($order['shipping']['city']); ?>,
                                <?= htmlspecialchars($order['shipping']['state']); ?><br>
                                <?= htmlspecialchars($order['shipping']['country']); ?>
                            </p>

                            <?php if (!empty($order['shipping']['note'])): ?>
                                <div class="mt-2 bg-amber-50 p-3 rounded-xl border border-amber-100/50">
                                    <p class="text-[10px] text-brand-orange font-bold uppercase tracking-wider mb-1">Delivery
                                        notes</p>
                                    <p class="text-[11px] text-brand-ink/70 leading-normal">
                                        <?= htmlspecialchars($order['shipping']['note']); ?>
                                    </p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </section>

                <!-- Items -->
                <section
                    class="bg-white border border-brand-forest/10 rounded-2xl p-4 sm:p-5 text-xs sm:text-sm shadow-sm pt-6">
                    <div class="flex items-center justify-between mb-5">
                        <h2 class="text-sm font-semibold text-brand-forest">Items in this order</h2>
                        <span
                            class="text-[10px] font-bold text-brand-orange bg-brand-orange/10 px-2 py-0.5 rounded-full uppercase tracking-tight">
                            <?= count($order['items']); ?> product<?= count($order['items']) > 1 ? 's' : ''; ?>
                        </span>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full border-separate border-spacing-y-2.5">
                            <thead class="text-[10px] text-brand-ink/40 uppercase tracking-[0.1em] font-bold">
                                <tr>
                                    <th class="text-left pr-3 pb-2 pl-2">Product</th>
                                    <th class="text-left pr-3 pb-2">Variant</th>
                                    <th class="text-left pr-3 pb-2">Qty</th>
                                    <th class="text-left pr-3 pb-2">Unit price</th>
                                    <th class="text-left pb-2 pr-2 text-right">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($order['items'] as $item): ?>
                                    <tr
                                        class="bg-green-50 border border-brand-forest/5 rounded-2xl group transition-all hover:shadow-md hover:shadow-brand-forest/5 shadow-sm">
                                        <td
                                            class="px-4 py-4 rounded-l-2xl align-middle border-y border-l border-brand-forest/5 group-hover:bg-white transition-colors">
                                            <span class="text-brand-forest font-bold text-[13px]">
                                                <?= htmlspecialchars($item['name']); ?>
                                            </span>
                                        </td>
                                        <td
                                            class="px-3 py-4 align-middle border-y border-brand-forest/5 group-hover:bg-white transition-colors">
                                            <span
                                                class="text-[11px] text-brand-ink/60 font-medium bg-white/50 px-2.5 py-1 rounded-lg border border-brand-forest/5">
                                                <?= htmlspecialchars($item['variant']); ?>
                                            </span>
                                        </td>
                                        <td
                                            class="px-3 py-4 align-middle border-y border-brand-forest/5 group-hover:bg-white transition-colors">
                                            <span class="text-brand-forest font-bold"><?= (int) $item['qty']; ?></span>
                                        </td>
                                        <td
                                            class="px-3 py-4 align-middle border-y border-brand-forest/5 group-hover:bg-white transition-colors">
                                            <span class="text-brand-ink/70 font-medium">
                                                <?= moneyNaira($item['unit_price']); ?>
                                            </span>
                                        </td>
                                        <td
                                            class="px-4 py-4 rounded-r-2xl align-middle border-y border-r border-brand-forest/5 group-hover:bg-white transition-colors text-right">
                                            <span class="font-bold text-brand-orange text-[14px]">
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