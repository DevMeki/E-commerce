<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in
if (empty($_SESSION['user'])) {
    header('Location: ../login');
    exit;
}

// Check if user is a brand account (assuming 'type' key exists, or maybe checking 'brand_id' existence?)
// Based on previous account.php, user has 'type' => 'buyer' or 'brand' (inferred).
// Let's check the database schema or previous code. 
// account.php line 60: if (($user['type'] ?? 'buyer') === 'buyer')
// So 'type' key is used.

if (($_SESSION['user']['type'] ?? '') !== 'brand') {
    // If logged in but not a brand, maybe redirect to main account page or home
    header('Location: ../account'); 
    exit;
}
?>
