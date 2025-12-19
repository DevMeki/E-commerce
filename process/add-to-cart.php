<?php
// process/add-to-cart.php
// Prevent any error output from breaking JSON
error_reporting(E_ALL);
ini_set('display_errors', 0);

// Start output buffering
ob_start();

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header('Content-Type: application/json');

function send_json_response($success, $message, $redirect = null)
{
    ob_clean(); // Discard any prior output
    echo json_encode([
        'success' => $success,
        'message' => $message,
        'redirect' => $redirect
    ]);
    exit;
}

// Check if user is logged in as buyer
if (!isset($_SESSION['user']) || $_SESSION['user']['type'] !== 'buyer') {
    send_json_response(false, 'Please login as a buyer to add items to cart');
}

// Validate input
$product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
$quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;
$variants = isset($_POST['variants']) ? json_decode($_POST['variants'], true) : [];
$redirect_to_checkout = isset($_POST['redirect_to_checkout']) && $_POST['redirect_to_checkout'] === 'true';

if ($product_id <= 0) {
    send_json_response(false, 'Invalid product');
}

if ($quantity <= 0 || $quantity > 100) {
    send_json_response(false, 'Invalid quantity (1-100 allowed)');
}

// Include database
require_once __DIR__ . '/../config.php';

if (!isset($conn) || !($conn instanceof mysqli)) {
    send_json_response(false, 'Database connection failed');
}

// Check if product exists and is available
$stmt = $conn->prepare("
    SELECT id, name, price, stock 
    FROM Product 
    WHERE id = ? AND status = 'active'
");
$stmt->bind_param('i', $product_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    $stmt->close();
    send_json_response(false, 'Product not available');
}

$product = $result->fetch_assoc();
$stmt->close();

// Check stock availability
if ($product['stock'] < $quantity) {
    send_json_response(false, 'Insufficient stock. Only ' . $product['stock'] . ' available');
}

$user_id = $_SESSION['user']['id'];

// Check if product with SAME variants is already in cart
$checkStmt = $conn->prepare("
    SELECT id, quantity, variants FROM cart 
    WHERE buyer_id = ? AND product_id = ?
");
$checkStmt->bind_param('ii', $user_id, $product_id);
$checkStmt->execute();
$checkResult = $checkStmt->get_result();

$existingCartItem = null;

// Iterate to find matching variants (safest way to compare JSON)
while ($row = $checkResult->fetch_assoc()) {
    $dbVariants = !empty($row['variants']) ? json_decode($row['variants'], true) : [];
    // Compare arrays
    if ($dbVariants == $variants) {
        $existingCartItem = $row;
        break;
    }
}
$checkStmt->close();

$variants_json = !empty($variants) ? json_encode($variants) : null;
$message = '';
$success = false;

if ($existingCartItem) {
    // Update existing cart item
    $newQuantity = $existingCartItem['quantity'] + $quantity;

    // Check if new quantity exceeds stock
    if ($newQuantity > $product['stock']) {
        send_json_response(false, 'Cannot add more. You already have ' . $existingCartItem['quantity'] . ' in cart and stock is ' . $product['stock']);
    }

    $updateStmt = $conn->prepare("
        UPDATE cart 
        SET quantity = ?, added_at = NOW() 
        WHERE id = ?
    ");
    $updateStmt->bind_param('ii', $newQuantity, $existingCartItem['id']);
    $success = $updateStmt->execute();
    $updateStmt->close();

    $message = 'Cart updated. You now have ' . $newQuantity . ' of this item.';

} else {
    // Insert new cart item
    $insertStmt = $conn->prepare("
        INSERT INTO cart (buyer_id, product_id, quantity, variants, added_at) 
        VALUES (?, ?, ?, ?, NOW())
    ");
    $insertStmt->bind_param('iiis', $user_id, $product_id, $quantity, $variants_json);
    $success = $insertStmt->execute();
    $insertStmt->close();

    $message = 'Product added to cart successfully';
}

if ($success) {
    send_json_response(true, $message, $redirect_to_checkout ? 'checkout.php' : null);
} else {
    send_json_response(false, 'Failed to save to cart. Please try again.');
}

ob_end_flush();
?>