<?php
header('Content-Type: application/json; charset=utf-8');

// Load DB connection
if (file_exists(__DIR__ . '/../config.php')) {
    require_once __DIR__ . '/../config.php';
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Missing config.php']);
    exit;
}

if (!$conn || !($conn instanceof mysqli)) {
    http_response_code(500);
    echo json_encode(['error' => 'Database connection failed.']);
    exit;
}

$query = trim($_GET['q'] ?? '');
$results = [];

if (!empty($query)) {
    // Search products
    $stmt = $conn->prepare('SELECT p.id, p.name, p.slug, p.category, p.price, b.brand_name FROM Product p JOIN Brand b ON p.brand_id = b.id WHERE p.status = "active" AND (p.name LIKE ? OR p.category LIKE ?) ORDER BY p.name LIMIT 5');
    if ($stmt) {
        $searchTerm = '%' . $query . '%';
        $stmt->bind_param('ss', $searchTerm, $searchTerm);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $results[] = array_merge($row, ['type' => 'product']);
        }
        $stmt->close();
    }
    
    // Search brands
    $stmt = $conn->prepare('SELECT id, brand_name, slug, category, logo FROM Brand WHERE status = "active" AND (brand_name LIKE ? OR category LIKE ?) ORDER BY brand_name LIMIT 5');
    if ($stmt) {
        $stmt->bind_param('ss', $searchTerm, $searchTerm);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $results[] = array_merge($row, ['type' => 'brand']);
        }
        $stmt->close();
    }
}

echo json_encode($results);
?>