<?php
session_start();
header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION['user'])) {
    echo json_encode(['success' => false, 'message' => 'Please login to follow brands', 'redirect' => 'login.php']);
    exit;
}

$user = $_SESSION['user'];
if ($user['type'] !== 'buyer') {
    echo json_encode(['success' => false, 'message' => 'Only buyers can follow brands']);
    exit;
}

require_once '../config.php';

// Get JSON input
$input = json_decode(file_get_contents('php://input'), true);
$brandId = isset($input['brand_id']) ? (int)$input['brand_id'] : 0;
$buyerId = $user['id'];

if ($brandId <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid brand ID']);
    exit;
}

// Check if already following
$checkStmt = $conn->prepare("SELECT id FROM BrandFollower WHERE buyer_id = ? AND brand_id = ?");
$checkStmt->bind_param("ii", $buyerId, $brandId);
$checkStmt->execute();
$checkResult = $checkStmt->get_result();

$isFollowing = false;

if ($checkResult->num_rows > 0) {
    // Already following -> Unfollow
    $deleteStmt = $conn->prepare("DELETE FROM BrandFollower WHERE buyer_id = ? AND brand_id = ?");
    $deleteStmt->bind_param("ii", $buyerId, $brandId);
    if ($deleteStmt->execute()) {
        $isFollowing = false;
        $msg = 'Unfollowed successfully';
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to unfollow']);
        exit;
    }
} else {
    // Not following -> Follow
    $insertStmt = $conn->prepare("INSERT INTO BrandFollower (buyer_id, brand_id, followed_at) VALUES (?, ?, NOW())");
    $insertStmt->bind_param("ii", $buyerId, $brandId);
    if ($insertStmt->execute()) {
        $isFollowing = true;
        $msg = 'Followed successfully';
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to follow']);
        exit;
    }
}

// Get updated follower count
$countStmt = $conn->prepare("SELECT followers FROM Brand WHERE id = ?");
$countStmt->bind_param("i", $brandId);
$countStmt->execute();
$countRes = $countStmt->get_result();
$newCount = 0;
if ($row = $countRes->fetch_assoc()) {
    $newCount = $row['followers'];
}

echo json_encode([
    'success' => true,
    'message' => $msg,
    'following' => $isFollowing,
    'newCount' => $newCount
]);
exit;
