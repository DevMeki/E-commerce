<?php
// process/remove-from-cart.php
ob_start();
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header('Content-Type: application/json');

if (!isset($_SESSION['user']) || $_SESSION['user']['type'] !== 'buyer') {
    ob_clean();
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$cart_id = isset($_POST['cart_id']) ? intval($_POST['cart_id']) : 0;

if ($cart_id <= 0) {
    ob_clean();
    echo json_encode(['success' => false, 'message' => 'Invalid input']);
    exit;
}

require_once __DIR__ . '/../config.php';

if (!isset($conn) || !($conn instanceof mysqli)) {
    ob_clean();
    echo json_encode(['success' => false, 'message' => 'Database error']);
    exit;
}

$user_id = $_SESSION['user']['id'];

$stmt = $conn->prepare("DELETE FROM cart WHERE id = ? AND buyer_id = ?");
$stmt->bind_param('ii', $cart_id, $user_id);
$success = $stmt->execute();
$stmt->close();

if ($success) {
    // Recalculate new total
    $totalStmt = $conn->prepare("
        SELECT SUM(c.quantity * p.price) as subtotal
        FROM cart c
        JOIN Product p ON c.product_id = p.id
        WHERE c.buyer_id = ?
    ");
    $totalStmt->bind_param('i', $user_id);
    $totalStmt->execute();
    $result = $totalStmt->get_result();
    $row = $result->fetch_assoc();
    $newSubtotal = $row['subtotal'] ?? 0;
    $totalStmt->close();

    ob_clean();
    echo json_encode([
        'success' => true, 
        'message' => 'Item removed',
        'subtotal' => $newSubtotal,
        'cartCount' => $result->num_rows // This might be wrong, need count distinct items
    ]);
} else {
    ob_clean();
    echo json_encode(['success' => false, 'message' => 'Removal failed']);
}
ob_end_flush();
?>
