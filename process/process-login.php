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

// Lookup user by email
$stmt = $conn->prepare('SELECT id, fullname, password FROM Buyer WHERE email = ? LIMIT 1');
if (!$stmt) {
    http_response_code(500);
    echo json_encode(['success' => false, 'errors' => ['Failed to prepare query: ' . $conn->error]]);
    exit;
}

$stmt->bind_param('s', $email);
$stmt->execute();
$stmt->bind_result($id, $fullname, $passwordHash);
if ($stmt->fetch()) {
    $stmt->close();
    if (password_verify($password, $passwordHash)) {
        // Authenticated
        $_SESSION['user'] = ['id' => $id, 'email' => $email, 'name' => $fullname];

        // Determine redirect: prefer posted redirect_to, fall back to HTTP_REFERER or '/'
        $redirect = '/';
        if (!empty($redirectTo)) {
            $redirect = $redirectTo;
        } elseif (!empty($_SERVER['HTTP_REFERER'])) {
            $redirect = $_SERVER['HTTP_REFERER'];
        }

        echo json_encode(['success' => true, 'redirect' => $redirect]);
        exit;
    } else {
        http_response_code(401);
        echo json_encode(['success' => false, 'errors' => ['Invalid email or password.']]);
        exit;
    }
} else {
    $stmt->close();
    http_response_code(401);
    echo json_encode(['success' => false, 'errors' => ['Invalid email or password.']]);
    exit;
}

?>