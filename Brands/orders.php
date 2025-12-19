<?php
require_once 'process/check_brand_login.php';
// -------- MOCK DATA (replace with DB later) --------
function moneyNaira($n) {
    return '₦' . number_format($n);
}

$orders = [
    [
        'id'           => '#LT-1024',
        'customer'     => 'Chidi Okafor',
        'email'        => 'chidi@example.com',
        'status'       => 'Paid',
        'items'        => 2,
        'total'        => 18500,
        'created_at'   => '2025-02-10 09:21',
        'when_label'   => '2h ago',
        'shipping_city'=> 'Lagos',
        'payment'      => 'Card',
    ],
    [
        'id'           => '#LT-1023',
        'customer'     => 'Amaka John',
        'email'        => 'amaka@example.com',
        'status'       => 'Shipped',
        'items'        => 1,
        'total'        => 7500,
        'created_at'   => '2025-02-10 06:05',
        'when_label'   => '5h ago',
        'shipping_city'=> 'Abuja',
        'payment'      => 'Card',
    ],
    [
        'id'           => '#LT-1022',
        'customer'     => 'Tolu Adesanya',
        'email'        => 'tolu@example.com',
        'status'       => 'Processing',
        'items'        => 3,
        'total'        => 14500,
        'created_at'   => '2025-02-09 19:32',
        'when_label'   => '1d ago',
        'shipping_city'=> 'Lagos',
        'payment'      => 'Transfer',
    ],
    [
        'id'           => '#LT-1021',
        'customer'     => 'Hauwa Bello',
        'email'        => 'hauwa@example.com',
        'status'       => 'Delivered',
        'items'        => 2,
        'total'        => 21000,
        'created_at'   => '2025-02-08 16:10',
        'when_label'   => '2d ago',
        'shipping_city'=> 'Kano',
        'payment'      => 'Card',
    ],
    [
        'id'           => '#LT-1020',
        'customer'     => 'Kunle Adebayo',
        'email'        => 'kunle@example.com',
        'status'       => 'Cancelled',
        'items'        => 1,
        'total'        => 6500,
        'created_at'   => '2025-02-07 11:47',
        'when_label'   => '3d ago',
        'shipping_city'=> 'Ibadan',
        'payment'      => 'Card',
    ],
];

// --- Filters from query string ---
$statusFilter = strtolower($_GET['status'] ?? 'all'); // all, processing, paid, shipped, delivered, cancelled
$search       = trim($_GET['q'] ?? '');

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
    'all'        => count($orders),
    'processing' => 0,
    'paid'       => 0,
    'shipped'    => 0,
    'delivered'  => 0,
    'cancelled'  => 0,
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
$currentBrandPage = 'orders';
include 'brand-header.php';
?>

<!-- MAIN -->
<main class="flex-1 py-6 sm:py-8">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-5 sm:space-y-6">

        <!-- Title + metrics -->
        <section class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <h1 class="text-xl sm:text-2xl font-semibold">Orders</h1>
                <p class="text-xs sm:text-sm text-gray-400 mt-1">
                    Track, filter and manage orders placed on your LocalTrade store.
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-3 text-[11px] sm:text-xs">
                <div class="px-3 py-1.5 rounded-xl bg-[#111111] border border-white/10">
                    <span class="text-gray-400">Orders:</span>
                    <span class="font-semibold text-gray-100 ml-1"><?= $totalOrders; ?></span>
                </div>
                <div class="px-3 py-1.5 rounded-xl bg-[#111111] border border-white/10">
                    <span class="text-gray-400">Total (shown):</span>
                    <span class="font-semibold text-orange-400 ml-1"><?= moneyNaira($totalRevenue); ?></span>
                </div>
            </div>
        </section>

        <!-- Filters -->
        <section class="bg-[#111111] border border-white/10 rounded-2xl p-4 sm:p-5 text-xs sm:text-sm">
            <form method="get" class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                <!-- Status pills -->
                <div class="flex flex-wrap gap-2">
                    <?php
                    $statusOptions = [
                        'all'        => 'All',
                        'processing' => 'Processing',
                        'paid'       => 'Paid',
                        'shipped'    => 'Shipped',
                        'delivered'  => 'Delivered',
                        'cancelled'  => 'Cancelled',
                    ];
                    foreach ($statusOptions as $key => $label):
                        $active = $statusFilter === $key;
                        ?>
                        <button
                            type="submit"
                            name="status"
                            value="<?= htmlspecialchars($key); ?>"
                            class="px-3 py-1.5 rounded-full border text-[11px] sm:text-xs
                            <?= $active
                                ? 'border-orange-500 bg-orange-500/10 text-orange-300'
                                : 'border-white/15 bg-[#0B0B0B] text-gray-300 hover:border-orange-400'; ?>"
                        >
                            <?= $label; ?>
                            <span class="ml-1 text-[10px] text-gray-400">
                                <?= $statusCounts[$key] ?? 0; ?>
                            </span>
                        </button>
                    <?php endforeach; ?>
                </div>

                <!-- Search -->
                <div class="flex items-center gap-2 w-full md:w-auto md:min-w-[260px]">
                    <input
                        type="hidden"
                        name="status"
                        value="<?= htmlspecialchars($statusFilter); ?>"
                    >
                    <input
                        type="text"
                        name="q"
                        value="<?= htmlspecialchars($search); ?>"
                        placeholder="Search by order ID or customer"
                        class="flex-1 bg-[#0B0B0B] border border-white/20 rounded-full px-3 py-2 text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-orange-500"
                    >
                    <button
                        type="submit"
                        class="px-3 py-2 rounded-full text-xs sm:text-sm font-semibold"
                        style="background-color: var(--lt-orange);"
                    >
                        Search
                    </button>
                </div>
            </form>
        </section>

        <!-- Orders table -->
        <section class="bg-[#111111] border border-white/10 rounded-2xl p-3 sm:p-5">
            <?php if (empty($filteredOrders)): ?>
                <div class="py-10 text-center text-xs text-gray-400">
                    No orders match your current filters.
                </div>
            <?php else: ?>
                <div class="overflow-x-auto text-xs sm:text-sm">
                    <table class="min-w-full border-separate border-spacing-y-2">
                        <thead class="text-[11px] sm:text-xs text-gray-400">
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
                                'paid'       => 'bg-emerald-500/15 text-emerald-300 border-emerald-500/40',
                                'shipped'    => 'bg-blue-500/15 text-blue-300 border-blue-500/40',
                                'delivered'  => 'bg-gray-500/15 text-gray-200 border-gray-500/40',
                                'processing' => 'bg-amber-500/15 text-amber-300 border-amber-500/40',
                                'cancelled'  => 'bg-red-500/15 text-red-300 border-red-500/40',
                                default      => 'bg-white/10 text-gray-200 border-white/20',
                            };
                            ?>
                            <tr class="bg-[#0B0B0B] border border-white/10 rounded-xl align-top">
                                <!-- Order ID + meta -->
                                <td class="px-3 py-2 rounded-l-xl align-top">
                                    <div class="flex flex-col">
                                        <a href="order-details.php?id=<?= urlencode($order['id']); ?>"
                                           class="font-semibold text-gray-100 hover:text-orange-400">
                                            <?= htmlspecialchars($order['id']); ?>
                                        </a>
                                        <span class="text-[11px] text-gray-500">
                                            Ship to <?= htmlspecialchars($order['shipping_city']); ?>
                                        </span>
                                    </div>
                                </td>

                                <!-- Customer -->
                                <td class="px-3 py-2 align-top">
                                    <div class="flex flex-col max-w-[160px] sm:max-w-[220px]">
                                        <span class="text-gray-100 truncate">
                                            <?= htmlspecialchars($order['customer']); ?>
                                        </span>
                                        <span class="text-[11px] text-gray-500 truncate">
                                            <?= htmlspecialchars($order['email']); ?>
                                        </span>
                                    </div>
                                </td>

                                <!-- Items -->
                                <td class="px-3 py-2 align-top">
                                    <span class="text-gray-100">
                                        <?= (int)$order['items']; ?> item<?= $order['items'] > 1 ? 's' : ''; ?>
                                    </span>
                                </td>

                                <!-- Total -->
                                <td class="px-3 py-2 align-top">
                                    <span class="font-semibold text-orange-400">
                                        <?= moneyNaira($order['total']); ?>
                                    </span>
                                </td>

                                <!-- Status -->
                                <td class="px-3 py-2 align-top">
                                    <span class="inline-flex px-2 py-0.5 rounded-full border text-[11px] <?= $badgeClass; ?>">
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
                                        <span class="text-[11px] text-gray-600">
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
