<?php
// -------- MOCK DATA (replace with DB later) --------
$brand = [
    'name'      => 'Lagos Streetwear Co.',
    'slug'      => 'lagos-streetwear-co',
    'location'  => 'Lagos, Nigeria',
    'since'     => '2021',
];

$metrics = [
    'revenue_today'   => 45200,
    'revenue_30d'     => 782000,
    'orders_30d'      => 124,
    'products_live'   => 18,
    'store_views_30d' => 2104,
];

$recentOrders = [
    [
        'id'        => '#LT-1024',
        'customer'  => 'Chidi Okafor',
        'item'      => 'Ankara Panel Hoodie',
        'total'     => 18500,
        'status'    => 'Paid',
        'created_at'=> '2h ago',
    ],
    [
        'id'        => '#LT-1023',
        'customer'  => 'Amaka John',
        'item'      => 'Naija Drip Tee (Black, L)',
        'total'     => 7500,
        'status'    => 'Shipped',
        'created_at'=> '5h ago',
    ],
    [
        'id'        => '#LT-1022',
        'customer'  => 'Tolu Adesanya',
        'item'      => 'Street Ankara Tote',
        'total'     => 14500,
        'status'    => 'Processing',
        'created_at'=> '1d ago',
    ],
    [
        'id'        => '#LT-1021',
        'customer'  => 'Hauwa Bello',
        'item'      => 'Patchwork Hoodie Limited',
        'total'     => 21000,
        'status'    => 'Delivered',
        'created_at'=> '2d ago',
    ],
];

$topProducts = [
    [
        'name'   => 'Ankara Panel Hoodie',
        'price'  => 18500,
        'sold'   => 42,
        'views'  => 620,
    ],
    [
        'name'   => 'Naija Drip Tee',
        'price'  => 7500,
        'sold'   => 63,
        'views'  => 810,
    ],
    [
        'name'   => 'Street Ankara Tote',
        'price'  => 14500,
        'sold'   => 28,
        'views'  => 470,
    ],
];

function moneyNaira($n) {
    return '₦' . number_format($n);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Brand Dashboard | LocalTrade</title>
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
$currentBrandPage = 'dashboard';
include 'brand-header.php';
?>

<!-- MAIN -->
<main class="flex-1 py-6 sm:py-8">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6 sm:space-y-8">

        <!-- Top row: Brand info + quick actions -->
        <section class="flex flex-col lg:flex-row gap-4 lg:gap-6">
            <!-- Brand summary -->
            <div class="flex-1 bg-[#111111] border border-white/10 rounded-2xl p-4 sm:p-5">
                <div class="flex items-start gap-3">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl bg-[#0B0B0B] border border-white/10 flex items-center justify-center text-sm font-semibold">
                        <?= strtoupper(substr($brand['name'], 0, 2)); ?>
                    </div>
                    <div>
                        <p class="text-xs uppercase tracking-[0.25em] text-orange-400 mb-1">
                            Brand dashboard
                        </p>
                        <h1 class="text-lg sm:text-xl font-semibold">
                            <?= htmlspecialchars($brand['name']); ?>
                        </h1>
                        <p class="text-[11px] sm:text-xs text-gray-400 mt-1">
                            📍 <?= htmlspecialchars($brand['location']); ?> · On LocalTrade since <?= htmlspecialchars($brand['since']); ?>
                        </p>
                    </div>
                </div>

                <div class="mt-4 grid grid-cols-2 sm:grid-cols-4 gap-3 text-xs">
                    <div>
                        <p class="text-gray-400 mb-1">Today’s revenue</p>
                        <p class="text-sm sm:text-base font-semibold text-orange-400">
                            <?= moneyNaira($metrics['revenue_today']); ?>
                        </p>
                    </div>
                    <div>
                        <p class="text-gray-400 mb-1">Last 30 days</p>
                        <p class="text-sm sm:text-base font-semibold">
                            <?= moneyNaira($metrics['revenue_30d']); ?>
                        </p>
                    </div>
                    <div>
                        <p class="text-gray-400 mb-1">Orders (30d)</p>
                        <p class="text-sm sm:text-base font-semibold">
                            <?= (int)$metrics['orders_30d']; ?>
                        </p>
                    </div>
                    <div>
                        <p class="text-gray-400 mb-1">Store views (30d)</p>
                        <p class="text-sm sm:text-base font-semibold">
                            <?= (int)$metrics['store_views_30d']; ?>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Quick actions -->
            <div class="w-full lg:w-64 bg-[#111111] border border-white/10 rounded-2xl p-4 sm:p-5 flex flex-col gap-3 text-xs">
                <p class="text-xs font-semibold mb-1">Quick actions</p>

                <a href="add-product"
                   class="flex items-center justify-between px-3 py-2 rounded-xl bg-orange-500 text-black text-xs font-semibold">
                    <span>+ Add new product</span>
                    <span>→</span>
                </a>

                <a href="products"
                   class="flex items-center justify-between px-3 py-2 rounded-xl bg-[#0B0B0B] border border-white/15 hover:border-orange-400">
                    <span>Manage products</span>
                    <span class="text-gray-400">→</span>
                </a>

                                <a href="orders"
                   class="flex items-center justify-between px-3 py-2 rounded-xl bg-[#0B0B0B] border border-white/15 hover:border-orange-400">
                    <span>View orders</span>
                    <span class="text-gray-400">→</span>
                </a>

                                <a href="onboarding"
                   class="flex items-center justify-between px-3 py-2 rounded-xl bg-[#0B0B0B] border border-white/15 hover:border-orange-400">
                    <span>Edit store profile & policies</span>
                    <span class="text-gray-400">→</span>
                </a>
            </div>
        </section>

        <!-- Middle: metrics and chart placeholder -->
        <section class="grid lg:grid-cols-[minmax(0,2.2fr)_minmax(0,1.3fr)] gap-4 lg:gap-6">
            <!-- Sales overview card -->
            <div class="bg-[#111111] border border-white/10 rounded-2xl p-4 sm:p-5 flex flex-col gap-3">
                <div class="flex items-center justify-between text-xs">
                    <div>
                        <p class="text-xs font-semibold">Sales overview</p>
                        <p class="text-[11px] text-gray-400">
                            Last 30 days (mock data – connect to real analytics later)
                        </p>
                    </div>
                    <select class="bg-[#0B0B0B] border border-white/15 rounded-full px-2 py-1 text-[11px]">
                        <option>Last 7 days</option>
                        <option selected>Last 30 days</option>
                        <option>Last 90 days</option>
                    </select>
                </div>

                <!-- Simple chart placeholder -->
                <div class="mt-2 h-40 sm:h-48 rounded-xl bg-[#0B0B0B] border border-dashed border-white/15 flex items-center justify-center text-[11px] text-gray-500">
                    Sales chart placeholder (implement real chart with JS later)
                </div>

                <div class="mt-3 grid grid-cols-3 gap-3 text-[11px] text-gray-300">
                    <div>
                        <p class="text-gray-400 mb-1">Avg. order value</p>
                        <p class="text-sm font-semibold"><?= moneyNaira(6300); ?></p>
                    </div>
                    <div>
                        <p class="text-gray-400 mb-1">Repeat customers</p>
                        <p class="text-sm font-semibold">34%</p>
                    </div>
                    <div>
                        <p class="text-gray-400 mb-1">Conversion rate</p>
                        <p class="text-sm font-semibold">2.8%</p>
                    </div>
                </div>
            </div>

            <!-- Top products -->
            <div class="bg-[#111111] border border-white/10 rounded-2xl p-4 sm:p-5 flex flex-col">
                <div class="flex items-center justify-between mb-3 text-xs">
                    <p class="text-xs font-semibold">Top products</p>
                    <a href="products" class="text-[11px] text-orange-300 hover:underline">View all</a>
                </div>

                <div class="space-y-3 text-xs">
                    <?php foreach ($topProducts as $p): ?>
                        <div class="flex items-center justify-between gap-3">
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold truncate">
                                    <?= htmlspecialchars($p['name']); ?>
                                </p>
                                <p class="text-[11px] text-gray-400">
                                    <?= moneyNaira($p['price']); ?> · <?= (int)$p['sold']; ?> sold
                                </p>
                            </div>
                            <div class="text-right text-[11px] text-gray-400">
                                <p><?= (int)$p['views']; ?> views</p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <p class="mt-4 text-[11px] text-gray-500">
                    Tip: Improve your product photos and descriptions to increase views and conversions.
                </p>
            </div>
        </section>

        <!-- Bottom: recent orders table -->
        <section class="bg-[#111111] border border-white/10 rounded-2xl p-4 sm:p-5">
            <div class="flex items-center justify-between mb-3 text-xs">
                <div>
                    <p class="text-xs font-semibold">Recent orders</p>
                    <p class="text-[11px] text-gray-400">
                        Latest activity across your store
                    </p>
                </div>
                <a href="orders" class="text-[11px] text-orange-300 hover:underline">View all orders</a>
            </div>

            <div class="overflow-x-auto text-xs">
                <table class="min-w-full border-separate border-spacing-y-2">
                    <thead class="text-[11px] text-gray-400">
                    <tr>
                        <th class="text-left pr-3 pb-1">Order</th>
                        <th class="text-left pr-3 pb-1">Customer</th>
                        <th class="text-left pr-3 pb-1">Item</th>
                        <th class="text-left pr-3 pb-1">Total</th>
                        <th class="text-left pr-3 pb-1">Status</th>
                        <th class="text-left pb-1">When</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($recentOrders as $order): ?>
                        <tr class="bg-[#0B0B0B] border border-white/10 rounded-xl">
                            <td class="px-3 py-2 rounded-l-xl align-top">
                                <span class="font-semibold text-gray-100">
                                    <?= htmlspecialchars($order['id']); ?>
                                </span>
                            </td>
                            <td class="px-3 py-2 align-top">
                                <span class="text-gray-100">
                                    <?= htmlspecialchars($order['customer']); ?>
                                </span>
                            </td>
                            <td class="px-3 py-2 align-top max-w-xs">
                                <span class="text-gray-300 line-clamp-2">
                                    <?= htmlspecialchars($order['item']); ?>
                                </span>
                            </td>
                            <td class="px-3 py-2 align-top">
                                <span class="font-semibold text-orange-400">
                                    <?= moneyNaira($order['total']); ?>
                                </span>
                            </td>
                            <td class="px-3 py-2 align-top">
                                <?php
                                $status = $order['status'];
                                $badgeClass = match ($status) {
                                    'Paid'       => 'bg-emerald-500/15 text-emerald-300 border-emerald-500/40',
                                    'Shipped'    => 'bg-blue-500/15 text-blue-300 border-blue-500/40',
                                    'Delivered'  => 'bg-gray-500/15 text-gray-200 border-gray-500/40',
                                    'Processing' => 'bg-amber-500/15 text-amber-300 border-amber-500/40',
                                    default      => 'bg-white/10 text-gray-200 border-white/20',
                                };
                                ?>
                                <span class="inline-flex px-2 py-0.5 rounded-full border text-[11px] <?= $badgeClass; ?>">
                                    <?= htmlspecialchars($status); ?>
                                </span>
                            </td>
                            <td class="px-3 py-2 rounded-r-xl align-top text-gray-400">
                                <?= htmlspecialchars($order['created_at']); ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </section>

    </div>
</main>

</body>
</html>
