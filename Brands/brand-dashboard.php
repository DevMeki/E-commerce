<?php
require_once 'process/check_brand_login.php';
// Include DB connection
if (file_exists('../config.php')) {
    require_once '../config.php';
}

$brandId = $_SESSION['user']['id'];
$brandName = $_SESSION['user']['brand_name'] ?? 'Brand';

// Handle duration selection
$days = isset($_GET['days']) ? (int)$_GET['days'] : 30;
if (!in_array($days, [7, 30, 90])) {
    $days = 30; // Default to 30 if invalid
}

// 1. Fetch Brand Details
$brandLoc = 'Nigeria';
$brandSince = date('Y');

if (isset($conn) && $conn instanceof mysqli) {
    $stmt = $conn->prepare("SELECT location, since_year, brand_name FROM Brand WHERE id = ?");
    $stmt->bind_param("i", $brandId);
    $stmt->execute();
    $stmt->bind_result($bLoc, $bSince, $bName);
    if ($stmt->fetch()) {
        $brandLoc = $bLoc ?? 'Nigeria';
        $brandSince = $bSince ?? date('Y');
        // Update session name if changed
        if ($bName) {
            $brandName = $bName;
            $_SESSION['user']['brand_name'] = $bName;
        }
    }
    $stmt->close();
}

$brand = [
    'name'      => $brandName,
    'slug'      => '', // We might need to fetch slug if used in links, but mostly dashboard is internal. 
                   // Actually, viewed previously, slug was used for "Brand dashboard" label? No, just name.
                   // Let's assume slug isn't critical for the dashboard view itself unless creating links to store.
                   // The mock had 'slug' => 'lagos-streetwear-co', used? 
                   // Checked previous file content: slug is NOT used in the HTML provided in previous turn?
                   // Wait, line 103 in previous view was: $brand['slug'] => NOT USED.
                   // Line 111 uses $brand['name'].
                   // Line 118 uses $brand['name'].
                   // Line 121 uses $brand['location'], $brand['since'].
                   // So slug is likely not needed for this viewing part.
    'location'  => $brandLoc,
    'since'     => $brandSince,
];

// 2. Fetch Metrics
$metrics = [
    'revenue_today'   => 0,
    'revenue_30d'     => 0,
    'orders_30d'      => 0,
    'products_live'   => 0,
    'store_views_30d' => 0, // Using total store views for now as schema doesn't have history table
];

if (isset($conn) && $conn instanceof mysqli) {
    // Revenue Today
    $today = date('Y-m-d');
    $stmt = $conn->prepare("SELECT SUM(total) FROM `order` WHERE brand_id = ? AND DATE(created_at) = ? AND status != 'cancelled'");
    $stmt->bind_param("is", $brandId, $today);
    $stmt->execute();
    $stmt->bind_result($revToday);
    $stmt->fetch();
    $metrics['revenue_today'] = $revToday ?? 0;
    $stmt->close();

    // Revenue & Orders Last X Days
    $dateX = date('Y-m-d H:i:s', strtotime("-$days days"));
    $stmt = $conn->prepare("SELECT SUM(total), COUNT(id) FROM `order` WHERE brand_id = ? AND created_at >= ? AND status != 'cancelled'");
    $stmt->bind_param("is", $brandId, $dateX);
    $stmt->execute();
    $stmt->bind_result($revX, $ordX);
    $stmt->fetch();
    $metrics['revenue_30d'] = $revX ?? 0;
    $metrics['orders_30d'] = $ordX ?? 0;
    $stmt->close();

    // Live Products
    $stmt = $conn->prepare("SELECT COUNT(id) FROM product WHERE brand_id = ? AND status = 'active'");
    $stmt->bind_param("i", $brandId);
    $stmt->execute();
    $stmt->bind_result($prodLive);
    $stmt->fetch();
    $metrics['products_live'] = $prodLive ?? 0;
    $stmt->close();
    
    // Store Views (Total)
    $stmt = $conn->prepare("SELECT store_views FROM Brand WHERE id = ?");
    $stmt->bind_param("i", $brandId);
    $stmt->execute();
    $stmt->bind_result($sViews);
    $stmt->fetch();
    $metrics['store_views_30d'] = $sViews ?? 0;
    $stmt->close();
}

// 2b. Advanced Metrics (Sales Overview)
$advancedMetrics = [
    'avg_order_value' => 0,
    'repeat_rate'     => 0,
    'conversion_rate' => 0,
];

if (isset($conn) && $conn instanceof mysqli) {
    // Avg Order Value (30d)
    if ($metrics['orders_30d'] > 0) {
        $advancedMetrics['avg_order_value'] = $metrics['revenue_30d'] / $metrics['orders_30d'];
    }

    // Repeat Customer Rate (Lifetime)
    // Count total unique buyers
    $stmt = $conn->prepare("SELECT COUNT(DISTINCT buyer_id) FROM `order` WHERE brand_id = ?");
    $stmt->bind_param("i", $brandId);
    $stmt->execute();
    $stmt->bind_result($totalBuyers);
    $stmt->fetch();
    $stmt->close();

    // Count repeat buyers (buyers with > 1 order)
    $repeatBuyers = 0;
    if ($totalBuyers > 0) {
        // Need a subquery count or having clause
        $stmt = $conn->prepare("SELECT COUNT(*) FROM (SELECT buyer_id FROM `order` WHERE brand_id = ? GROUP BY buyer_id HAVING COUNT(id) > 1) as repeats");
        $stmt->bind_param("i", $brandId);
        $stmt->execute();
        $stmt->bind_result($repeatBuyers);
        $stmt->fetch();
        $stmt->close();
        
        $advancedMetrics['repeat_rate'] = ($repeatBuyers / $totalBuyers) * 100;
    }

    // Conversion Rate (Total Orders / Total Views)
    // We already have total store views in $metrics['store_views_30d'] (which is actually total)
    // We need total orders count for accuracy
    $stmt = $conn->prepare("SELECT COUNT(id) FROM `order` WHERE brand_id = ?");
    $stmt->bind_param("i", $brandId);
    $stmt->execute();
    $stmt->bind_result($totalOrdersAllTime);
    $stmt->fetch();
    $stmt->close();

    if ($metrics['store_views_30d'] > 0) {
        $advancedMetrics['conversion_rate'] = ($totalOrdersAllTime / $metrics['store_views_30d']) * 100;
    }
}

// 2c. Chart Data (Last X Days Daily Sales)
$chartLabels = [];
$chartData = [];
// Initialize last X days with 0
for ($i = $days - 1; $i >= 0; $i--) {
    $date = date('Y-m-d', strtotime("-$i days"));
    $chartLabels[] = date('M j', strtotime($date)); // e.g. Dec 10
    $chartData[$date] = 0;
}

if (isset($conn) && $conn instanceof mysqli) {
    $dateX = date('Y-m-d', strtotime("-$days days"));
    // Group by Date
    $stmt = $conn->prepare("
        SELECT DATE(created_at) as sale_date, SUM(total) 
        FROM `order` 
        WHERE brand_id = ? AND created_at >= ? AND status != 'cancelled'
        GROUP BY DATE(created_at)
    ");
    $stmt->bind_param("is", $brandId, $dateX);
    $stmt->execute();
    $res = $stmt->get_result();
    while ($row = $res->fetch_assoc()) {
        if (isset($chartData[$row['sale_date']])) {
            $chartData[$row['sale_date']] = (float)$row['SUM(total)'];
        }
    }
    $stmt->close();
}
// Convert keys to just values for JS
$chartValues = array_values($chartData);


// 3. Recent Orders
$recentOrders = [];
if (isset($conn) && $conn instanceof mysqli) {
    // Join Order with Buyer to get customer name. 
    // Join with OrderItem to get ONE item name (LIMIT 1 per order via logic or just SUBSTRING_INDEX/GROUP_CONCAT?)
    // Easier to just fetch orders and then for each fetch first item or doing a subquery.
    // Let's do a left join and group by order id to just get one.
    // "SELECT o.id, o.order_number, o.total, o.status, o.created_at, o.customer_name 
    // FROM `order` o WHERE brand_id = ? ORDER BY created_at DESC LIMIT 5"
    // Note: order table has customer_name column directly (from schema `customer_name` varchar(100)).
    // And `order` table has `order_number`.
    
    $stmt = $conn->prepare("
        SELECT id, order_number, customer_name, total, status, created_at 
        FROM `order` 
        WHERE brand_id = ? 
        ORDER BY created_at DESC 
        LIMIT 5
    ");
    $stmt->bind_param("i", $brandId);
    $stmt->execute();
    $res = $stmt->get_result();
    while ($row = $res->fetch_assoc()) {
        // Fetch first item for this order
        $item = 'Unknown Item';
        $iStmt = $conn->prepare("SELECT product_name FROM orderitem WHERE order_id = ? LIMIT 1");
        $iStmt->bind_param("i", $row['id']);
        $iStmt->execute();
        $iStmt->bind_result($pName);
        if ($iStmt->fetch()) {
            $item = $pName;
        }
        $iStmt->close();
        
        // Time ago helper
        $timeAgo = humanTiming(strtotime($row['created_at']));

        $recentOrders[] = [
            'id'        => '#' . ($row['order_number'] ?: $row['id']),
            'customer'  => $row['customer_name'],
            'item'      => $item,
            'total'     => $row['total'],
            'status'    => ucfirst($row['status']),
            'created_at'=> $timeAgo,
        ];
    }
    $stmt->close();
}

// 4. Top Products
$topProducts = [];
if (isset($conn) && $conn instanceof mysqli) {
    $stmt = $conn->prepare("
        SELECT name, price, total_sales, views 
        FROM product 
        WHERE brand_id = ? 
        ORDER BY total_sales DESC 
        LIMIT 3
    ");
    $stmt->bind_param("i", $brandId);
    $stmt->execute();
    $res = $stmt->get_result();
    while ($row = $res->fetch_assoc()) {
        $topProducts[] = [
            'name'   => $row['name'],
            'price'  => $row['price'],
            'sold'   => $row['total_sales'],
            'views'  => $row['views'],
        ];
    }
    $stmt->close();
}

function moneyNaira($n) {
    return '₦' . number_format($n);
}

function humanTiming ($time) {
    $time = time() - $time; // to get the time since that moment
    $time = ($time<1)? 1 : $time;
    $tokens = array (
        31536000 => 'year',
        2592000 => 'month',
        604800 => 'week',
        86400 => 'day',
        3600 => 'h',
        60 => 'min',
        1 => 's'
    );
    foreach ($tokens as $unit => $text) {
        if ($time < $unit) continue;
        $numberOfUnits = floor($time / $unit);
        return $numberOfUnits.' '.$text.(($numberOfUnits>1)?'s':'').' ago';
    }
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
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
                        <p class="text-gray-400 mb-1">Last <span class="period-label"><?= $days ?></span> days</p>
                        <p class="text-sm sm:text-base font-semibold" id="revenuePeriod">
                            <?= moneyNaira($metrics['revenue_30d']); ?>
                        </p>
                    </div>
                    <div>
                        <p class="text-gray-400 mb-1">Orders (<span class="period-label"><?= $days ?></span>d)</p>
                        <p class="text-sm sm:text-base font-semibold" id="ordersPeriod">
                            <?= (int)$metrics['orders_30d']; ?>
                        </p>
                    </div>
                    <div>
                        <p class="text-gray-400 mb-1">Store views (total)</p>
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
                        <p class="text-[11px] text-gray-400 sales-overview-label">
                            Last <?= $days ?> days
                        </p>
                    </div>
                    <select id="durationSelect" class="bg-[#0B0B0B] border border-white/15 rounded-full px-2 py-1 text-[11px]">
                        <option value="7" <?= $days === 7 ? 'selected' : '' ?>>Last 7 days</option>
                        <option value="30" <?= $days === 30 ? 'selected' : '' ?>>Last 30 days</option>
                        <option value="90" <?= $days === 90 ? 'selected' : '' ?>>Last 90 days</option>
                    </select>
                </div>

                <!-- Chart -->
                <div class="mt-2 h-56 w-full bg-[#0B0B0B] border border-white/10 rounded-xl p-2 relative">
                    <canvas id="salesChart"></canvas>
                </div>

                <div class="mt-3 grid grid-cols-3 gap-3 text-[11px] text-gray-300">
                    <div>
                        <p class="text-gray-400 mb-1">Avg. order value</p>
                        <p class="text-sm font-semibold" id="avgOrderValue"><?= moneyNaira($advancedMetrics['avg_order_value']); ?></p>
                    </div>
                    <div>
                        <p class="text-gray-400 mb-1">Repeat customers</p>
                        <p class="text-sm font-semibold" id="repeatRate"><?= number_format($advancedMetrics['repeat_rate'], 1); ?>%</p>
                    </div>
                    <div>
                        <p class="text-gray-400 mb-1">Conversion rate</p>
                        <p class="text-sm font-semibold" id="conversionRate"><?= number_format($advancedMetrics['conversion_rate'], 2); ?>%</p>
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

<script>
    const ctx = document.getElementById('salesChart').getContext('2d');
    let salesChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: <?= json_encode($chartLabels) ?>,
            datasets: [{
                label: 'Revenue (₦)',
                data: <?= json_encode($chartValues) ?>,
                borderColor: '#F36A1D',
                backgroundColor: 'rgba(243, 106, 29, 0.1)',
                borderWidth: 2,
                tension: 0.4,
                pointRadius: 0,
                pointHoverRadius: 4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    mode: 'index',
                    intersect: false,
                    callbacks: {
                        label: function(context) {
                            return '₦' + context.parsed.y.toLocaleString();
                        }
                    }
                }
            },
            scales: {
                x: {
                    grid: { display: false, drawBorder: false },
                    ticks: { color: '#666', maxTicksLimit: 7 }
                },
                y: {
                    grid: { color: 'rgba(255,255,255,0.05)', drawBorder: false },
                    ticks: { color: '#666', callback: (val) => '₦' + (val/1000) + 'k' }
                }
            },
            interaction: {
                mode: 'nearest',
                axis: 'x',
                intersect: false
            }
        }
    });

    // AJAX duration change handler
    const durationSelect = document.getElementById('durationSelect');
    durationSelect.addEventListener('change', async function() {
        const days = this.value;
        
        try {
            const response = await fetch(`process/get-dashboard-data.php?days=${days}`);
            const data = await response.json();
            
            if (data.success) {
                // Update chart
                salesChart.data.labels = data.chartLabels;
                salesChart.data.datasets[0].data = data.chartData;
                salesChart.update();
                
                // Update metrics
                document.getElementById('revenuePeriod').textContent = '₦' + data.metrics.revenue_period.toLocaleString();
                document.getElementById('ordersPeriod').textContent = data.metrics.orders_period;
                document.getElementById('avgOrderValue').textContent = '₦' + Math.round(data.advancedMetrics.avg_order_value).toLocaleString();
                document.getElementById('repeatRate').textContent = data.advancedMetrics.repeat_rate.toFixed(1) + '%';
                document.getElementById('conversionRate').textContent = data.advancedMetrics.conversion_rate.toFixed(2) + '%';
                
                // Update labels
                document.querySelectorAll('.period-label').forEach(el => {
                    el.textContent = days;
                });
                document.querySelector('.sales-overview-label').textContent = `Last ${days} days`;
            }
        } catch (error) {
            console.error('Error fetching dashboard data:', error);
        }
    });
</script>
</body>
</html>
