<?php
session_start();

header('Content-Type: application/json; charset=utf-8');

// Load DB connection from config.php (expects $conn as mysqli)
if (file_exists(__DIR__ . '/../config.php')) {
    require_once __DIR__ . '/../config.php';
} else {
    http_response_code(500);
    echo json_encode(['success' => false, 'errors' => ['Missing config.php']]);
    exit;
}

$response = ['success' => false, 'errors' => []];

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'errors' => ['Invalid request method']]);
    exit;
}

$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$redirectTo = trim($_POST['redirect_to'] ?? '');

// Validate email
if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'errors' => ['Please enter a valid email address.']]);
    exit;
}

if ($password === '') {
    http_response_code(400);
    echo json_encode(['success' => false, 'errors' => ['Please enter your password.']]);
    exit;
}

if (!isset($conn) || !($conn instanceof mysqli)) {
    http_response_code(500);
    echo json_encode(['success' => false, 'errors' => ['Database connection not available.']]);
    exit;
}

// Lookup user by email in Buyer table first
$user = null;
$type = null;

$stmt = $conn->prepare('SELECT id, fullname, password, avatar, phone FROM Buyer WHERE email = ? LIMIT 1');
if (!$stmt) {
    http_response_code(500);
    echo json_encode(['success' => false, 'errors' => ['Failed to prepare query: ' . $conn->error]]);
    exit;
}

$stmt->bind_param('s', $email);
$stmt->execute();
$stmt->bind_result($id, $fullname, $passwordHash, $avatar, $phone);
if ($stmt->fetch()) {
    $type = 'buyer';
    $user = ['id' => $id, 'fullname' => $fullname, 'password' => $passwordHash, 'avatar' => $avatar, 'phone' => $phone];
}
$stmt->close();

// If not found in Buyer, check Brand table
if (!$user) {
    $stmt = $conn->prepare('SELECT id, owner_name, brand_name, password, logo FROM Brand WHERE email = ? LIMIT 1');
    if (!$stmt) {
        http_response_code(500);
        echo json_encode(['success' => false, 'errors' => ['Failed to prepare query: ' . $conn->error]]);
        exit;
    }

    $stmt->bind_param('s', $email);
    $stmt->execute();
    $stmt->bind_result($id, $owner_name, $brand_name, $passwordHash, $logo);
    if ($stmt->fetch()) {
        $type = 'brand';
        $user = ['id' => $id, 'fullname' => $owner_name, 'brand_name' => $brand_name, 'password' => $passwordHash, 'avatar' => $logo];
    }
    $stmt->close();
}

if ($user && password_verify($password, $user['password'])) {
// Authenticated
$_SESSION['user'] = [
    'id' => $user['id'],
    'email' => $email,
    'fullname' => $user['fullname'],
    'type' => $type
];

if ($type === 'buyer') {
    $_SESSION['user']['avatar'] = $user['avatar'];
    $_SESSION['user']['phone'] = $user['phone'];
} elseif ($type === 'brand') {
    $_SESSION['user']['avatar'] = $user['avatar'];
    $_SESSION['user']['brand_name'] = $user['brand_name'];
}    echo json_encode(['success' => true, 'redirect' => $redirect]);
    exit;
} else {
    http_response_code(401);
    echo json_encode(['success' => false, 'errors' => ['Invalid email or password.']]);
    exit;
}

?>