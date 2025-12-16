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

// Ensure we only handle Brand signup here
$accountType = $_POST['account_type'] ?? 'buyer';
if ($accountType !== 'brand') {
	$response['errors'][] = 'Only brand signup is supported by this endpoint.';
	echo json_encode($response);
	exit;
}

// Trim inputs
$ownerName = trim($_POST['owner_name'] ?? '');
$brandName = trim($_POST['brand_name'] ?? '');
$brandSlug = trim($_POST['brand_slug'] ?? '');
$brandCategory = trim($_POST['brand_category'] ?? '');
$brandLocation = trim($_POST['brand_location'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$confirm = $_POST['confirm_password'] ?? '';

// ============ VALIDATION ============

// Validate email first
if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
	$response['errors'][] = 'Please enter a valid email address.';
	echo json_encode($response);
	exit;
}

// Owner name validation
if ($ownerName === '') {
	$response['errors'][] = 'Owner name is required.';
}

// Owner name should contain only letters and whitespace (Unicode-aware)
if ($ownerName !== '' && !preg_match('/^[\p{L}\s]+$/u', $ownerName)) {
	$response['errors'][] = 'Owner name may only contain letters and spaces.';
}

// Brand name validation
if ($brandName === '') {
	$response['errors'][] = 'Brand/Store name is required.';
}

// Brand name should be reasonable length
if (strlen($brandName) > 100) {
	$response['errors'][] = 'Brand name must be 100 characters or less.';
}

// Category validation
if ($brandCategory === '') {
	$response['errors'][] = 'Please select a primary category.';
}

// Validate category against allowed list
$allowedCategories = ['Fashion', 'Beauty', 'Electronics', 'Home & Living', 'Food & Drinks', 'Art & Craft', 'Other'];
if ($brandCategory !== '' && !in_array($brandCategory, $allowedCategories, true)) {
	$response['errors'][] = 'Invalid category selected.';
}

// Location validation
if ($brandLocation === '') {
	$response['errors'][] = 'Brand location is required.';
}

// Slug validation if provided
if ($brandSlug !== '' && !preg_match('/^[a-zA-Z0-9_-]+$/', $brandSlug)) {
	$response['errors'][] = 'Store URL may only contain letters, numbers, hyphens, and underscores.';
}

// Password validation
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

// ============ DATABASE OPERATIONS ============

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

// Generate slug if not provided
if ($brandSlug === '') {
	// Convert brand name to slug: lowercase, replace spaces with hyphens
	$brandSlug = strtolower($brandName);
	$brandSlug = preg_replace('/[^a-z0-9]+/', '-', $brandSlug);
	$brandSlug = trim($brandSlug, '-');

	// Ensure uniqueness by appending number if needed
	$baseSlug = $brandSlug;
	$counter = 1;

	while (true) {
		$checkSlug = $conn->prepare('SELECT id FROM Brand WHERE slug = ? LIMIT 1');
		if ($checkSlug) {
			$checkSlug->bind_param('s', $brandSlug);
			$checkSlug->execute();
			$checkSlug->store_result();

			if ($checkSlug->num_rows === 0) {
				$checkSlug->close();
				break; // Slug is unique
			}
			$checkSlug->close();

			// Try next variation
			$brandSlug = $baseSlug . '-' . $counter;
			$counter++;

			if ($counter > 100) {
				// Safety limit
				http_response_code(500);
				echo json_encode(['success' => false, 'errors' => ['Unable to generate unique store URL. Please specify one manually.']]);
				exit;
			}
		} else {
			http_response_code(500);
			echo json_encode(['success' => false, 'errors' => ['Database query failed: ' . $conn->error]]);
			exit;
		}
	}
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

// Check if slug already exists (even if auto-generated, double-check)
$checkSlug = $conn->prepare('SELECT id FROM Brand WHERE slug = ? LIMIT 1');
if ($checkSlug) {
	$checkSlug->bind_param('s', $brandSlug);
	$checkSlug->execute();
	$checkSlug->store_result();
	if ($checkSlug->num_rows > 0) {
		http_response_code(409);
		echo json_encode(['success' => false, 'errors' => ['This store URL is already taken. Please choose another.']]);
		$checkSlug->close();
		exit;
	}
	$checkSlug->close();
} else {
	http_response_code(500);
	echo json_encode(['success' => false, 'errors' => ['Database query failed: ' . $conn->error]]);
	exit;
}

// Hash password
$passwordHash = password_hash($password, PASSWORD_DEFAULT);

// Insert into Brand table
$createdAt = date('Y-m-d H:i:s');
$stmt = $conn->prepare('INSERT INTO Brand (owner_name, brand_name, slug, category, location, email, password, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?)');
if (!$stmt) {
	http_response_code(500);
	echo json_encode(['success' => false, 'errors' => ['Failed to prepare statement: ' . $conn->error]]);
	exit;
}

$stmt->bind_param('ssssssss', $ownerName, $brandName, $brandSlug, $brandCategory, $brandLocation, $email, $passwordHash, $createdAt);
$ok = $stmt->execute();
if ($ok) {
	echo json_encode(['success' => true, 'message' => 'Brand account created successfully.', 'redirect' => 'Brands/onboarding.php']);
	$stmt->close();
	exit;
} else {
	http_response_code(500);
	echo json_encode(['success' => false, 'errors' => ['Failed to create account: ' . $stmt->error]]);
	$stmt->close();
	exit;
}
