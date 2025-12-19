<?php
require_once 'check_brand_login.php';

// Include DB connection
if (file_exists('../../config.php')) {
    require_once '../../config.php';
}

header('Content-Type: application/json');

$brandId = $_SESSION['user']['id'];

// Handle duration selection
$days = isset($_GET['days']) ? (int)$_GET['days'] : 30;
if (!in_array($days, [7, 30, 90])) {
    $days = 30;
}

$response = [
    'success' => false,
    'days' => $days,
    'metrics' => [],
    'chartLabels' => [],
    'chartData' => [],
    'advancedMetrics' => []
];

if (!isset($conn) || !($conn instanceof mysqli)) {
    echo json_encode($response);
    exit;
}

// Fetch Metrics
$metrics = [
    'revenue_today'   => 0,
    'revenue_period'  => 0,
    'orders_period'   => 0,
    'products_live'   => 0,
    'store_views'     => 0,
];

// Revenue Today
$today = date('Y-m-d');
$stmt = $conn->prepare("SELECT SUM(total) FROM `order` WHERE brand_id = ? AND DATE(created_at) = ? AND status != 'cancelled'");
$stmt->bind_param("is", $brandId, $today);
$stmt->execute();
$stmt->bind_result($revToday);
$stmt->fetch();
$metrics['revenue_today'] = $revToday ?? 0;
$stmt->close();

// Revenue & Orders for selected period
$dateX = date('Y-m-d H:i:s', strtotime("-$days days"));
$stmt = $conn->prepare("SELECT SUM(total), COUNT(id) FROM `order` WHERE brand_id = ? AND created_at >= ? AND status != 'cancelled'");
$stmt->bind_param("is", $brandId, $dateX);
$stmt->execute();
$stmt->bind_result($revX, $ordX);
$stmt->fetch();
$metrics['revenue_period'] = $revX ?? 0;
$metrics['orders_period'] = $ordX ?? 0;
$stmt->close();

// Live Products
$stmt = $conn->prepare("SELECT COUNT(id) FROM product WHERE brand_id = ? AND status = 'active'");
$stmt->bind_param("i", $brandId);
$stmt->execute();
$stmt->bind_result($prodLive);
$stmt->fetch();
$metrics['products_live'] = $prodLive ?? 0;
$stmt->close();

// Store Views
$stmt = $conn->prepare("SELECT store_views FROM Brand WHERE id = ?");
$stmt->bind_param("i", $brandId);
$stmt->execute();
$stmt->bind_result($sViews);
$stmt->fetch();
$metrics['store_views'] = $sViews ?? 0;
$stmt->close();

// Advanced Metrics
$advancedMetrics = [
    'avg_order_value' => 0,
    'repeat_rate'     => 0,
    'conversion_rate' => 0,
];

if ($metrics['orders_period'] > 0) {
    $advancedMetrics['avg_order_value'] = $metrics['revenue_period'] / $metrics['orders_period'];
}

// Repeat Customer Rate
$stmt = $conn->prepare("SELECT COUNT(DISTINCT buyer_id) FROM `order` WHERE brand_id = ?");
$stmt->bind_param("i", $brandId);
$stmt->execute();
$stmt->bind_result($totalBuyers);
$stmt->fetch();
$stmt->close();

$repeatBuyers = 0;
if ($totalBuyers > 0) {
    $stmt = $conn->prepare("SELECT COUNT(*) FROM (SELECT buyer_id FROM `order` WHERE brand_id = ? GROUP BY buyer_id HAVING COUNT(id) > 1) as repeats");
    $stmt->bind_param("i", $brandId);
    $stmt->execute();
    $stmt->bind_result($repeatBuyers);
    $stmt->fetch();
    $stmt->close();
    
    $advancedMetrics['repeat_rate'] = ($repeatBuyers / $totalBuyers) * 100;
}

// Conversion Rate
$stmt = $conn->prepare("SELECT COUNT(id) FROM `order` WHERE brand_id = ?");
$stmt->bind_param("i", $brandId);
$stmt->execute();
$stmt->bind_result($totalOrdersAllTime);
$stmt->fetch();
$stmt->close();

if ($metrics['store_views'] > 0) {
    $advancedMetrics['conversion_rate'] = ($totalOrdersAllTime / $metrics['store_views']) * 100;
}

// Chart Data
$chartLabels = [];
$chartData = [];

for ($i = $days - 1; $i >= 0; $i--) {
    $date = date('Y-m-d', strtotime("-$i days"));
    $chartLabels[] = date('M j', strtotime($date));
    $chartData[$date] = 0;
}

$dateX = date('Y-m-d', strtotime("-$days days"));
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

$response['success'] = true;
$response['metrics'] = $metrics;
$response['advancedMetrics'] = $advancedMetrics;
$response['chartLabels'] = $chartLabels;
$response['chartData'] = array_values($chartData);

echo json_encode($response);
