<?php
require_once 'process/check_brand_login.php';
// -------- MOCK DATA (replace with DB later) --------
function moneyNaira($n)
{
    return '₦' . number_format($n);
}

$orders = [
    [
        'id' => '#LT-1024',
        'customer' => 'Chidi Okafor',
        'email' => 'chidi@example.com',
        'status' => 'Paid',
        'items' => 2,
        'total' => 18500,
        'created_at' => '2025-02-10 09:21',
        'when_label' => '2h ago',
        'shipping_city' => 'Lagos',
        'payment' => 'Card',
    ],
    [
        'id' => '#LT-1023',
        'customer' => 'Amaka John',
        'email' => 'amaka@example.com',
        'status' => 'Shipped',
        'items' => 1,
        'total' => 7500,
        'created_at' => '2025-02-10 06:05',
        'when_label' => '5h ago',
        'shipping_city' => 'Abuja',
        'payment' => 'Card',
    ],
    [
        'id' => '#LT-1022',
        'customer' => 'Tolu Adesanya',
        'email' => 'tolu@example.com',
        'status' => 'Processing',
        'items' => 3,
        'total' => 14500,
        'created_at' => '2025-02-09 19:32',
        'when_label' => '1d ago',
        'shipping_city' => 'Lagos',
        'payment' => 'Transfer',
    ],
    [
        'id' => '#LT-1021',
        'customer' => 'Hauwa Bello',
        'email' => 'hauwa@example.com',
        'status' => 'Delivered',
        'items' => 2,
        'total' => 21000,
        'created_at' => '2025-02-08 16:10',
        'when_label' => '2d ago',
        'shipping_city' => 'Kano',
        'payment' => 'Card',
    ],
    [
        'id' => '#LT-1020',
        'customer' => 'Kunle Adebayo',
        'email' => 'kunle@example.com',
        'status' => 'Cancelled',
        'items' => 1,
        'total' => 6500,
        'created_at' => '2025-02-07 11:47',
        'when_label' => '3d ago',
        'shipping_city' => 'Ibadan',
        'payment' => 'Card',
    ],
];

// --- Filters from query string ---
$statusFilter = strtolower($_GET['status'] ?? 'all'); // all, processing, paid, shipped, delivered, cancelled
$search = trim($_GET['q'] ?? '');

// --- Filter orders ---
$filteredOrders = array_filter($orders, function ($order) use ($statusFilter, $search) {
    if ($statusFilter !== 'all') {
        if (strtolower($order['status']) !== $statusFilter) {
            return false;
        }
    }
    if ($search !== '') {
        $haystack = strtolower($order['id'] . ' ' . $order['customer'] . ' ' . $order['email']);
        if (strpos($haystack, strtolower($search)) === false) {
            return false;
        }
    }
    return true;
});

// --- Simple metrics ---
$totalOrders = count($filteredOrders);
$totalRevenue = array_sum(array_column($filteredOrders, 'total'));

$statusCounts = [
    'all' => count($orders),
    'processing' => 0,
    'paid' => 0,
    'shipped' => 0,
    'delivered' => 0,
    'cancelled' => 0,
];
foreach ($orders as $o) {
    $k = strtolower($o['status']);
    if (isset($statusCounts[$k])) {
        $statusCounts[$k]++;
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Orders | LocalTrade</title>
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
    $currentBrandPage = 'orders';
    include 'brand-header.php';
    ?>

    <!-- MAIN -->
    <main class="flex-1 py-6 sm:py-8">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-5 sm:space-y-6">

            <!-- Title + metrics -->
            <section class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <h1 class="text-xl sm:text-2xl font-semibold text-brand-forest">Orders</h1>
                    <p class="text-xs sm:text-sm text-brand-ink/50 mt-1">
                        Track, filter and manage orders placed on your LocalTrade store.
                    </p>
                </div>

                <div class="flex flex-wrap items-center gap-3 text-[11px] sm:text-xs">
                    <div class="px-3 py-1.5 rounded-xl bg-green-50 border border-brand-forest/5 shadow-sm">
                        <span class="text-brand-ink/40">Orders:</span>
                        <span class="font-semibold text-brand-forest ml-1"><?= $totalOrders; ?></span>
                    </div>
                    <div class="px-3 py-1.5 rounded-xl bg-green-50 border border-brand-forest/5 shadow-sm">
                        <span class="text-brand-ink/40">Total (shown):</span>
                        <span class="font-semibold text-brand-orange ml-1"><?= moneyNaira($totalRevenue); ?></span>
                    </div>
                </div>
            </section>

            <!-- Filters -->
            <section class="bg-white border border-brand-forest/10 rounded-2xl p-4 sm:p-5 text-xs sm:text-sm shadow-sm">
                <form method="get" class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                    <!-- Status pills -->
                    <div class="flex flex-wrap gap-2">
                        <?php
                        $statusOptions = [
                            'all' => 'All',
                            'processing' => 'Processing',
                            'paid' => 'Paid',
                            'shipped' => 'Shipped',
                            'delivered' => 'Delivered',
                            'cancelled' => 'Cancelled',
                        ];
                        foreach ($statusOptions as $key => $label):
                            $active = $statusFilter === $key;
                            ?>
                            <button type="submit" name="status" value="<?= htmlspecialchars($key); ?>"
                                class="px-3 py-1.5 rounded-full border text-[11px] sm:text-xs transition-all
                            <?= $active
                                ? 'border-brand-orange bg-brand-orange/10 text-brand-orange font-bold'
                                : 'border-brand-forest/10 bg-brand-parchment text-brand-ink/60 hover:border-brand-orange/50'; ?>">
                                <?= $label; ?>
                                <span class="ml-1 text-[10px] text-brand-ink/30">
                                    <?= $statusCounts[$key] ?? 0; ?>
                                </span>
                            </button>
                        <?php endforeach; ?>
                    </div>

                    <!-- Search -->
                    <div class="flex items-center gap-2 w-full md:w-auto md:min-w-[260px]">
                        <input type="hidden" name="status" value="<?= htmlspecialchars($statusFilter); ?>">
                        <input type="text" name="q" value="<?= htmlspecialchars($search); ?>"
                            placeholder="Search by order ID or customer"
                            class="flex-1 bg-brand-parchment border border-brand-forest/10 rounded-full px-4 py-2 text-xs sm:text-sm text-brand-ink placeholder-brand-ink/40 focus:outline-none focus:ring-1 focus:ring-brand-orange">
                        <button type="submit"
                            class="px-5 py-2 rounded-full text-xs sm:text-sm font-semibold text-white shadow-sm shadow-brand-orange/20"
                            style="background-color: var(--lt-orange);">
                            Search
                        </button>
                    </div>
                </form>
            </section>

            <!-- Orders table -->
            <section class="bg-green-50 border border-brand-forest/5 rounded-2xl p-3 sm:p-5 shadow-sm">
                <?php if (empty($filteredOrders)): ?>
                    <div class="py-10 text-center text-xs text-gray-400">
                        No orders match your current filters.
                    </div>
                <?php else: ?>
                    <div class="overflow-x-auto text-xs sm:text-sm">
                        <table class="min-w-full border-separate border-spacing-y-2">
                            <thead class="text-[11px] sm:text-xs text-brand-ink/40">
                                <tr>
                                    <th class="text-left pr-3 pb-1">Order</th>
                                    <th class="text-left pr-3 pb-1">Customer</th>
                                    <th class="text-left pr-3 pb-1">Items</th>
                                    <th class="text-left pr-3 pb-1">Total</th>
                                    <th class="text-left pr-3 pb-1">Status</th>
                                    <th class="text-left pr-3 pb-1">Payment</th>
                                    <th class="text-left pb-1">When</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($filteredOrders as $order): ?>
                                    <?php
                                    $status = $order['status'];
                                    $statusKey = strtolower($status);
                                    $badgeClass = match ($statusKey) {
                                        'paid' => 'bg-emerald-500/10 text-emerald-700 border-emerald-500/20',
                                        'shipped' => 'bg-blue-500/10 text-blue-700 border-blue-500/20',
                                        'delivered' => 'bg-gray-500/10 text-brand-ink/60 border-brand-forest/10',
                                        'processing' => 'bg-amber-500/10 text-amber-700 border-amber-500/20',
                                        'cancelled' => 'bg-red-500/10 text-red-700 border-red-500/20',
                                        default => 'bg-brand-forest/5 text-brand-ink/60 border-brand-forest/10',
                                    };
                                    ?>
                                    <tr class="bg-white border border-brand-forest/5 rounded-xl align-top shadow-sm">
                                        <!-- Order ID + meta -->
                                        <td class="px-3 py-2 rounded-l-xl align-top">
                                            <div class="flex flex-col">
                                                <a href="order-details.php?id=<?= urlencode($order['id']); ?>"
                                                    class="font-semibold text-brand-forest hover:text-brand-orange">
                                                    <?= htmlspecialchars($order['id']); ?>
                                                </a>
                                                <span class="text-[11px] text-brand-ink/40">
                                                    Ship to <?= htmlspecialchars($order['shipping_city']); ?>
                                                </span>
                                            </div>
                                        </td>

                                        <!-- Customer -->
                                        <td class="px-3 py-2 align-top">
                                            <div class="flex flex-col max-w-[160px] sm:max-w-[220px]">
                                                <span class="text-brand-forest truncate">
                                                    <?= htmlspecialchars($order['customer']); ?>
                                                </span>
                                                <span class="text-[11px] text-brand-ink/40 truncate">
                                                    <?= htmlspecialchars($order['email']); ?>
                                                </span>
                                            </div>
                                        </td>

                                        <!-- Items -->
                                        <td class="px-3 py-2 align-top">
                                            <span class="text-brand-forest">
                                                <?= (int) $order['items']; ?> item<?= $order['items'] > 1 ? 's' : ''; ?>
                                            </span>
                                        </td>

                                        <!-- Total -->
                                        <td class="px-3 py-2 align-top">
                                            <span class="font-semibold text-brand-orange">
                                                <?= moneyNaira($order['total']); ?>
                                            </span>
                                        </td>

                                        <!-- Status -->
                                        <td class="px-3 py-2 align-top">
                                            <span
                                                class="inline-flex px-2 py-0.5 rounded-full border text-[11px] <?= $badgeClass; ?>">
                                                <?= htmlspecialchars($status); ?>
                                            </span>
                                        </td>

                                        <!-- Payment -->
                                        <td class="px-3 py-2 align-top">
                                            <span class="text-gray-200">
                                                <?= htmlspecialchars($order['payment']); ?>
                                            </span>
                                        </td>

                                        <!-- When -->
                                        <td class="px-3 py-2 rounded-r-xl align-top text-gray-400">
                                            <div class="flex flex-col items-start">
                                                <span><?= htmlspecialchars($order['when_label']); ?></span>
                                                <span class="text-[11px] text-brand-ink/30">
                                                    <?= htmlspecialchars($order['created_at']); ?>
                                                </span>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </section>
        </div>
    </main>

</body>

</html>