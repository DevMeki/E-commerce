<?php
// process/process-login.php
ob_start();

session_start();

// Set JSON header
header('Content-Type: application/json; charset=utf-8');

// Load DB connection
if (file_exists(__DIR__ . '/../config.php')) {
    require_once __DIR__ . '/../config.php';
} else {
    ob_end_clean();
    http_response_code(500);
    echo json_encode(['success' => false, 'errors' => ['Missing config.php']]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    ob_end_clean();
    http_response_code(405);
    echo json_encode(['success' => false, 'errors' => ['Invalid request method']]);
    exit;
}

$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$redirectTo = trim($_POST['redirect_to'] ?? '');

// Validate email
if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    ob_end_clean();
    http_response_code(400);
    echo json_encode(['success' => false, 'errors' => ['Please enter a valid email address.']]);
    exit;
}

if ($password === '') {
    ob_end_clean();
    http_response_code(400);
    echo json_encode(['success' => false, 'errors' => ['Please enter your password.']]);
    exit;
}

if (!isset($conn) || !($conn instanceof mysqli)) {
    ob_end_clean();
    http_response_code(500);
    echo json_encode(['success' => false, 'errors' => ['Database connection not available.']]);
    exit;
}

// Lookup user
$user = null;
$type = null;

$stmt = $conn->prepare('SELECT id, fullname, password, avatar, phone FROM Buyer WHERE email = ? LIMIT 1');
if (!$stmt) {
    ob_end_clean();
    http_response_code(500);
    echo json_encode(['success' => false, 'errors' => ['Failed to prepare query: ' . $conn->error]]);
    exit;
}

$stmt->bind_param('s', $email);
if (!$stmt->execute()) {
    ob_end_clean();
    http_response_code(500);
    echo json_encode(['success' => false, 'errors' => ['Database query failed: ' . $stmt->error]]);
    exit;
}

$stmt->bind_result($id, $fullname, $passwordHash, $avatar, $phone);
if ($stmt->fetch()) {
    $type = 'buyer';
    $user = [
        'id' => $id, 
        'fullname' => $fullname, 
        'password' => $passwordHash, 
        'avatar' => $avatar, 
        'phone' => $phone
    ];
}
$stmt->close();

// Check Brand table
if (!$user) {
    $stmt = $conn->prepare('SELECT id, owner_name, brand_name, password, logo FROM Brand WHERE email = ? LIMIT 1');
    if (!$stmt) {
        ob_end_clean();
        http_response_code(500);
        echo json_encode(['success' => false, 'errors' => ['Failed to prepare query: ' . $conn->error]]);
        exit;
    }

    $stmt->bind_param('s', $email);
    if (!$stmt->execute()) {
        ob_end_clean();
        http_response_code(500);
        echo json_encode(['success' => false, 'errors' => ['Database query failed: ' . $stmt->error]]);
        exit;
    }

    $stmt->bind_result($id, $owner_name, $brand_name, $passwordHash, $logo);
    if ($stmt->fetch()) {
        $type = 'brand';
        $user = [
            'id' => $id, 
            'fullname' => $owner_name, 
            'brand_name' => $brand_name, 
            'password' => $passwordHash, 
            'avatar' => $logo
        ];
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
    }
    
    // Determine redirect URL based on user type and previous page
    $finalRedirect = determineRedirectUrl($type, $redirectTo);
    
    ob_end_clean();
    echo json_encode([
        'success' => true, 
        'redirect' => $finalRedirect,
        'user_type' => $type
    ]);
    exit;
} else {
    ob_end_clean();
    http_response_code(401);
    echo json_encode(['success' => false, 'errors' => ['Invalid email or password.']]);
    exit;
}

/**
 * Determine the appropriate redirect URL after login
 * 
 * @param string $userType 'buyer' or 'brand'
 * @param string $previousUrl The URL user came from
 * @return string The URL to redirect to
 */
// redirect function 
function determineRedirectUrl($userType, $previousUrl) {
    // Parse the previous URL
    $parsedUrl = parse_url($previousUrl);
    $path = $parsedUrl['path'] ?? '';
    $host = $parsedUrl['host'] ?? '';
    
    // Current domain
    $currentDomain = $_SERVER['HTTP_HOST'] ?? '';
    
    // For BRAND users: always go to brand dashboard
    if ($userType === 'brand') {
        return 'Brands/brand-dashboard';
    }
    
    // For BUYER users:
    // Check if previous page is within our project and not signup page
    $isLocalPage = ($host === '' || $host === $currentDomain || 
                   in_array($host, ['localhost', '127.0.0.1']));
    
    $isSignupPage = strpos($path, 'signup') !== false || 
                    strpos($path, 'register') !== false;
    
    // Return to previous page only if:
    // 1. It's a local page (within our project)
    // 2. It's not a signup page
    // 3. It's not the login page itself
    // 4. It's not empty
    if ($isLocalPage && 
        !$isSignupPage && 
        !empty($previousUrl) && 
        strpos($path, 'login') === false) {
        return $previousUrl;
    }
    
    // Default for buyers: index.php
    return 'index.php';
}

/**
 * Check if a URL is a valid local URL (not external)
 * 
 * @param string $url The URL to check
 * @return bool True if it's a valid local URL
 */
function isValidLocalUrl($url) {
    if (empty($url)) {
        return false;
    }
    
    // Parse the URL
    $parsed = parse_url($url);
    
    // Check if it's a relative URL (no host or same host)
    if (!isset($parsed['host'])) {
        return true; // Relative URL like "/page" or "page.php"
    }
    
    // Check if it's the same domain (optional, but good for security)
    $currentHost = $_SERVER['HTTP_HOST'] ?? '';
    if ($parsed['host'] === $currentHost) {
        return true;
    }
    
    // Allow certain trusted localhost variations
    $localHosts = ['localhost', '127.0.0.1', '::1'];
    if (in_array($parsed['host'], $localHosts)) {
        return true;
    }
    
    return false;
}