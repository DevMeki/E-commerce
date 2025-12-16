<?php
session_start();

header('Content-Type: application/json; charset=utf-8');

// Load DB connection
if (file_exists(__DIR__ . '/../config.php')) {
    require_once __DIR__ . '/../config.php';
} else {
    http_response_code(500);
    echo json_encode(['success' => false, 'errors' => ['Missing config.php']]);
    exit;
}

if (!$conn || !($conn instanceof mysqli)) {
    http_response_code(500);
    echo json_encode(['success' => false, 'errors' => ['Database connection failed.']]);
    exit;
}

if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(['success' => false, 'errors' => ['Database connection failed: ' . $conn->connect_error]]);
    exit;
}

$response = ['success' => false, 'errors' => []];

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'errors' => ['Invalid request method']]);
    exit;
}

if (empty($_SESSION['user'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'errors' => ['Not logged in']]);
    exit;
}

$user = $_SESSION['user'];
$userId = $user['id'];
$userType = $user['type'] ?? 'buyer'; // default to buyer

$fullname = trim($_POST['fullname'] ?? '');
$phone = trim($_POST['phone'] ?? '');

// Validation
if ($fullname === '') {
    $response['errors'][] = 'Full name is required.';
}

if ($phone !== '' && !preg_match('/^\+?[\d\s\-\(\)]+$/', $phone)) {
    $response['errors'][] = 'Please enter a valid phone number.';
}

if (!empty($response['errors'])) {
    http_response_code(400);
    echo json_encode($response);
    exit;
}

// Handle avatar upload
$avatarPath = null;
if (!empty($_FILES['avatar']['name'])) {
    $uploadDir = __DIR__ . '/../Assets/avatars/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    $fileName = uniqid('avatar_') . '_' . basename($_FILES['avatar']['name']);
    $targetFile = $uploadDir . $fileName;

    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    if (!in_array($_FILES['avatar']['type'], $allowedTypes)) {
        $response['errors'][] = 'Invalid image type. Only JPG, PNG, GIF, WEBP allowed.';
    } elseif ($_FILES['avatar']['size'] > 2 * 1024 * 1024) { // 2MB
        $response['errors'][] = 'Image too large. Max 2MB.';
    } elseif (!move_uploaded_file($_FILES['avatar']['tmp_name'], $targetFile)) {
        $response['errors'][] = 'Failed to upload image.';
    } else {
        $avatarPath = 'Assets/avatars/' . $fileName;
    }
}

if (!empty($response['errors'])) {
    http_response_code(400);
    echo json_encode($response);
    exit;
}

// Get current avatar before updating
$oldAvatar = null;
if ($userType === 'buyer') {
    $stmt = $conn->prepare('SELECT avatar FROM Buyer WHERE id = ?');
    $stmt->bind_param('i', $userId);
    $stmt->execute();
    $stmt->bind_result($oldAvatar);
    $stmt->fetch();
    $stmt->close();
} else {
    $stmt = $conn->prepare('SELECT logo FROM Brand WHERE id = ?');
    $stmt->bind_param('i', $userId);
    $stmt->execute();
    $stmt->bind_result($oldAvatar);
    $stmt->fetch();
    $stmt->close();
}

// Update database
if ($userType === 'buyer') {
    $table = 'Buyer';
    $setParts = ["fullname = ?"];
    $params = [$fullname];
    $types = 's';

    if ($phone !== '') {
        $setParts[] = "phone = ?";
        $params[] = $phone;
        $types .= 's';
    }

    if ($avatarPath) {
        $setParts[] = "avatar = ?";
        $params[] = $avatarPath;
        $types .= 's';
    }

    $query = "UPDATE $table SET " . implode(', ', $setParts) . " WHERE id = ?";
    $params[] = $userId;
    $types .= 'i';
} else {
    // For brand, update owner_name and logo
    $table = 'Brand';
    $setParts = ["owner_name = ?"];
    $params = [$fullname];
    $types = 's';

    if ($avatarPath) {
        $setParts[] = "logo = ?";
        $params[] = $avatarPath;
        $types .= 's';
    }

    $query = "UPDATE $table SET " . implode(', ', $setParts) . " WHERE id = ?";
    $params[] = $userId;
    $types .= 'i';
}

$stmt = $conn->prepare($query);
if (!$stmt) {
    http_response_code(500);
    echo json_encode(['success' => false, 'errors' => ['Failed to prepare update query: ' . $conn->error]]);
    exit;
}

$stmt->bind_param($types, ...$params);
if (!$stmt->execute()) {
    http_response_code(500);
    echo json_encode(['success' => false, 'errors' => ['Failed to update account: ' . $stmt->error]]);
    exit;
}

$stmt->close();

// Update session
$_SESSION['user']['fullname'] = $fullname;
if ($userType === 'buyer') {
    $_SESSION['user']['phone'] = $phone;
}
if ($avatarPath) {
    $_SESSION['user']['avatar'] = $avatarPath;
}

// Delete old avatar if new one uploaded
if ($avatarPath && $oldAvatar) {
    $oldFile = __DIR__ . '/../' . $oldAvatar;
    if (file_exists($oldFile)) {
        unlink($oldFile);
    }
}

$response['success'] = true;
$response['message'] = 'Account updated successfully.';
echo json_encode($response);