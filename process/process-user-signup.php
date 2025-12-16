<?php
// Load project config (expects `config.php` to define `$conn` as mysqli)
if (file_exists(__DIR__ . '/../config.php')) {
	require_once __DIR__ . '/../config.php';
} else {
	header('Content-Type: application/json; charset=utf-8');
	http_response_code(500);
	echo json_encode(['success' => false, 'errors' => ['Missing config.php']]);
	exit;
}

header('Content-Type: application/json; charset=utf-8');

$response = ['success' => false, 'errors' => []];

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
	http_response_code(405);
	$response['errors'][] = 'Invalid request method.';
	echo json_encode($response);
	exit;
}

// Ensure we only handle Buyer signup here
$accountType = $_POST['account_type'] ?? 'buyer';
if ($accountType !== 'buyer') {
	$response['errors'][] = 'Only buyer signup is supported by this endpoint.';
	echo json_encode($response);
	exit;
}

// Trim inputs
$fullname = trim($_POST['full_name'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$confirm = $_POST['confirm_password'] ?? '';

// Validate email first
if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
	$response['errors'][] = 'Please enter a valid email address.';
	echo json_encode($response);
	exit;
}

// Other strict validations
if ($fullname === '') {
	$response['errors'][] = 'Full name is required.';
}

// Full name should contain only letters and whitespace (Unicode-aware)
if ($fullname !== '' && !preg_match('/^[\p{L}\s]+$/u', $fullname)) {
	$response['errors'][] = 'Full name may only contain letters and spaces.';
}

if ($password === '' || $confirm === '') {
	$response['errors'][] = 'Password and confirm password are required.';
} elseif ($password !== $confirm) {
	$response['errors'][] = 'Passwords do not match.';
} elseif (strlen($password) < 8) {
	$response['errors'][] = 'Password must be at least 8 characters.';
}

if (!empty($response['errors'])) {
	echo json_encode($response);
	exit;
}

// Prepare DB connection using `config.php`'s `$conn` (mysqli)

// Use mysqli connection provided by config.php
if (!isset($conn) || !($conn instanceof mysqli)) {
	http_response_code(500);
	echo json_encode(['success' => false, 'errors' => ['Database connection not available.']]);
	exit;
}

if ($conn->connect_error) {
	http_response_code(500);
	echo json_encode(['success' => false, 'errors' => ['Database connection failed: ' . $conn->connect_error]]);
	exit;
}


// Check if email already exists in Buyer table
$check = $conn->prepare('SELECT id FROM Buyer WHERE email = ? LIMIT 1');
if ($check) {
	$check->bind_param('s', $email);
	$check->execute();
	$check->store_result();
	if ($check->num_rows > 0) {
		http_response_code(409);
		echo json_encode(['success' => false, 'errors' => ['An account with that email already exists.']]);
		$check->close();
		exit;
	}
	$check->close();
} else {
	// If prepare fails, return a server error
	http_response_code(500);
	echo json_encode(['success' => false, 'errors' => ['Database query failed: ' . $conn->error]]);
	exit;
}

// Check if email already exists in Brand table
$checkEmail = $conn->prepare('SELECT id FROM Brand WHERE email = ? LIMIT 1');
if ($checkEmail) {
	$checkEmail->bind_param('s', $email);
	$checkEmail->execute();
	$checkEmail->store_result();
	if ($checkEmail->num_rows > 0) {
		http_response_code(409);
		echo json_encode(['success' => false, 'errors' => ['An account with that email already exists.']]);
		$checkEmail->close();
		exit;
	}
	$checkEmail->close();
} else {
	http_response_code(500);
	echo json_encode(['success' => false, 'errors' => ['Database query failed: ' . $conn->error]]);
	exit;
}

$passwordHash = password_hash($password, PASSWORD_DEFAULT);

// Insert into Buyer table using prepared statement (mysqli)
$createdAt = date('Y-m-d H:i:s');
$stmt = $conn->prepare('INSERT INTO Buyer (fullname, password, email, created_at) VALUES (?, ?, ?, ?)');
if (!$stmt) {
	http_response_code(500);
	echo json_encode(['success' => false, 'errors' => ['Failed to prepare statement: ' . $conn->error]]);
	exit;
}

$stmt->bind_param('ssss', $fullname, $passwordHash, $email, $createdAt);
$ok = $stmt->execute();
if ($ok) {
	echo json_encode(['success' => true, 'message' => 'Account created successfully.']);
	$stmt->close();
	exit;
} else {
	http_response_code(500);
	echo json_encode(['success' => false, 'errors' => ['Failed to create account: ' . $stmt->error]]);
	$stmt->close();
	exit;
}
