<?php
session_start();

// Load project config (expects `config.php` to define `$conn` as mysqli)
if (file_exists(__DIR__ . '/../config.php')) {
	require_once __DIR__ . '/../config.php';
} else {
	http_response_code(500);
	echo 'Missing config.php';
	exit;
}

// Get OAuth client credentials from environment or config (set these securely)
$googleClientId = getenv('GOOGLE_CLIENT_ID') ?: ($google_client_id ?? '');
$googleClientSecret = getenv('GOOGLE_CLIENT_SECRET') ?: ($google_client_secret ?? '');

// Build redirect URI for callbacks (extensionless mapping expected)
$scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'];
$base = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
$redirectUri = $scheme . '://' . $host . $base . '/google_oauth_start';

// Accept two modes: start (no ?code) and callback (with ?code)
if (!empty($_GET['code'])) {
	// Callback: validate state
	$state = $_GET['state'] ?? '';
	if (empty($state) || !isset($_SESSION['oauth_state']) || $_SESSION['oauth_state'] !== $state) {
		http_response_code(400);
		echo 'Invalid OAuth state.';
		exit;
	}

	if (empty($googleClientId) || empty($googleClientSecret)) {
		http_response_code(500);
		echo 'OAuth client not configured.';
		exit;
	}

	$code = $_GET['code'];

	// Exchange code for tokens
	$tokenUrl = 'https://oauth2.googleapis.com/token';
	$post = http_build_query([
		'code' => $code,
		'client_id' => $googleClientId,
		'client_secret' => $googleClientSecret,
		'redirect_uri' => $redirectUri,
		'grant_type' => 'authorization_code',
	]);

	$ch = curl_init($tokenUrl);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
	curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/x-www-form-urlencoded']);
	$tokenResp = curl_exec($ch);
	$err = curl_error($ch);
	curl_close($ch);

	if ($tokenResp === false) {
		http_response_code(500);
		echo 'Token exchange failed: ' . $err;
		exit;
	}

	$tokenData = json_decode($tokenResp, true);
	if (empty($tokenData['access_token'])) {
		http_response_code(500);
		echo 'No access token returned.';
		exit;
	}

	$accessToken = $tokenData['access_token'];

	// Fetch user info
	$userinfoUrl = 'https://openidconnect.googleapis.com/v1/userinfo';
	$ch = curl_init($userinfoUrl . '?access_token=' . urlencode($accessToken));
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$uiResp = curl_exec($ch);
	$err = curl_error($ch);
	curl_close($ch);
	if ($uiResp === false) {
		http_response_code(500);
		echo 'Failed to fetch user info: ' . $err;
		exit;
	}

	$user = json_decode($uiResp, true);
	$email = $user['email'] ?? '';
	$name = $user['name'] ?? ($user['given_name'] ?? '');
	$emailVerified = $user['email_verified'] ?? false;

	if (empty($email) || !$emailVerified) {
		// Redirect back with error
		header('Location: /signup?error=' . urlencode('Google account email not available or not verified.'));
		exit;
	}

	// Use mysqli $conn from config.php
	if (!isset($conn) || !($conn instanceof mysqli)) {
		http_response_code(500);
		echo 'Database connection missing.';
		exit;
	}

	// Check if buyer exists
	$stmt = $conn->prepare('SELECT id FROM Buyer WHERE email = ? LIMIT 1');
	if ($stmt) {
		$stmt->bind_param('s', $email);
		$stmt->execute();
		$stmt->bind_result($existingId);
		$found = $stmt->fetch();
		$stmt->close();
	} else {
		$found = false;
	}

	if ($found && !empty($existingId)) {
		// Existing user — log them in
		$_SESSION['user'] = ['id' => $existingId, 'email' => $email, 'name' => $name];
		header('Location: /');
		exit;
	}

	// Create new Buyer — generate a random password hash since user signs in with Google
	$randomPwd = bin2hex(random_bytes(16));
	$pwdHash = password_hash($randomPwd, PASSWORD_DEFAULT);
	$createdAt = date('Y-m-d H:i:s');

	$ins = $conn->prepare('INSERT INTO Buyer (fullname, password, email, created_at) VALUES (?, ?, ?, ?)');
	if (!$ins) {
		http_response_code(500);
		echo 'Failed to prepare insert: ' . $conn->error;
		exit;
	}
	$ins->bind_param('ssss', $name, $pwdHash, $email, $createdAt);
	$ok = $ins->execute();
	if (!$ok) {
		http_response_code(500);
		echo 'Failed to create account: ' . $ins->error;
		exit;
	}
	$newId = $ins->insert_id;
	$ins->close();

	// Log user in
	$_SESSION['user'] = ['id' => $newId, 'email' => $email, 'name' => $name];
	header('Location: /');
	exit;

} else {
	// Start flow: generate state and redirect to Google
	if (empty($googleClientId) || empty($googleClientSecret)) {
		http_response_code(500);
		echo 'OAuth client not configured.';
		exit;
	}

	$state = bin2hex(random_bytes(16));
	$_SESSION['oauth_state'] = $state;

	$authUrl = 'https://accounts.google.com/o/oauth2/v2/auth?' . http_build_query([
		'client_id' => $googleClientId,
		'redirect_uri' => $redirectUri,
		'response_type' => 'code',
		'scope' => 'openid email profile',
		'state' => $state,
		'access_type' => 'offline',
		'prompt' => 'select_account'
	]);

	header('Location: ' . $authUrl);
	exit;
}

