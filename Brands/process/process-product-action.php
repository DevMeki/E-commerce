<?php
require_once 'check_brand_login.php';

// Include DB connection
if (file_exists('../../config.php')) {
    require_once '../../config.php';
}

header('Content-Type: application/json');

$response = [
    'success' => false,
    'message' => ''
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $brand_id = $_SESSION['user']['id'];
    $product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
    $action = isset($_POST['action']) ? $_POST['action'] : '';

    if ($product_id <= 0 && $action !== 'delete_image') {
        $response['message'] = 'Invalid request parameters.';
        echo json_encode($response);
        exit;
    }

    if (!in_array($action, ['archive', 'unarchive', 'delete', 'delete_image'])) {
        $response['message'] = 'Invalid action.';
        echo json_encode($response);
        exit;
    }

    // Verify ownership
    $checkStmt = $conn->prepare("SELECT id, main_image FROM product WHERE id = ? AND brand_id = ?");
    $checkStmt->bind_param("ii", $product_id, $brand_id);
    $checkStmt->execute();
    $res = $checkStmt->get_result();
    $product = $res->fetch_assoc();
    $checkStmt->close();

    if ($action === 'delete_image') {
        $image_id = isset($_POST['image_id']) ? intval($_POST['image_id']) : 0;

        // Verify ownership through product join
        $stmt = $conn->prepare("
            SELECT pi.id, pi.image_url 
            FROM productimage pi 
            JOIN product p ON pi.product_id = p.id 
            WHERE pi.id = ? AND p.brand_id = ?
        ");
        $stmt->bind_param("ii", $image_id, $brand_id);
        $stmt->execute();
        $img = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if (!$img) {
            $response['message'] = 'Image not found or access denied.';
            echo json_encode($response);
            exit;
        }

        // Delete file
        $file = '../../' . $img['image_url'];
        if (file_exists($file)) {
            unlink($file);
        }

        // Delete from DB
        $delStmt = $conn->prepare("DELETE FROM productimage WHERE id = ?");
        $delStmt->bind_param("i", $image_id);
        if ($delStmt->execute()) {
            $response['success'] = true;
            $response['message'] = 'Image deleted successfully.';
        } else {
            $response['message'] = 'Failed to delete record.';
        }
        $delStmt->close();
        echo json_encode($response);
        exit;
    }

    if (!$product) {
        $response['message'] = 'Product not found or access denied.';
        echo json_encode($response);
        exit;
    }

    if ($action === 'archive') {
        $stmt = $conn->prepare("UPDATE product SET status = 'archived' WHERE id = ? AND brand_id = ?");
        $stmt->bind_param("ii", $product_id, $brand_id);
        if ($stmt->execute()) {
            $response['success'] = true;
            $response['message'] = 'Product archived successfully.';
        } else {
            $response['message'] = 'Failed to archive product.';
        }
        $stmt->close();
    } elseif ($action === 'unarchive') {
        $stmt = $conn->prepare("UPDATE product SET status = 'active' WHERE id = ? AND brand_id = ?");
        $stmt->bind_param("ii", $product_id, $brand_id);
        if ($stmt->execute()) {
            $response['success'] = true;
            $response['message'] = 'Product unarchived successfully.';
        } else {
            $response['message'] = 'Failed to unarchive product.';
        }
        $stmt->close();
    } elseif ($action === 'delete') {
        // 1. Get all gallery images
        $galleryStmt = $conn->prepare("SELECT image_url FROM productimage WHERE product_id = ?");
        $galleryStmt->bind_param("i", $product_id);
        $galleryStmt->execute();
        $galleryRes = $galleryStmt->get_result();
        while ($row = $galleryRes->fetch_assoc()) {
            $file = '../../' . $row['image_url'];
            if (file_exists($file)) {
                unlink($file);
            }
        }
        $galleryStmt->close();

        // 2. Delete main image
        if (!empty($product['main_image'])) {
            $mainFile = '../../' . $product['main_image'];
            if (file_exists($mainFile)) {
                unlink($mainFile);
            }
        }

        // 3. Delete from DB (Foreign keys should handle productimage cascading if set, but we handle it manually for safety if not)
        // Actually, let's delete gallery entries first if no cascade
        $conn->query("DELETE FROM productimage WHERE product_id = $product_id");
        $conn->query("DELETE FROM cart WHERE product_id = $product_id");
        $conn->query("DELETE FROM wishlist WHERE product_id = $product_id");

        $stmt = $conn->prepare("DELETE FROM product WHERE id = ? AND brand_id = ?");
        $stmt->bind_param("ii", $product_id, $brand_id);
        if ($stmt->execute()) {
            $response['success'] = true;
            $response['message'] = 'Product deleted successfully.';
        } else {
            $response['message'] = 'Failed to delete product.';
        }
        $stmt->close();
    }
} else {
    $response['message'] = 'Invalid request method.';
}

echo json_encode($response);
