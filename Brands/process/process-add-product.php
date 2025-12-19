<?php
require_once 'check_brand_login.php';

// Include DB connection
if (file_exists('../../config.php')) {
    require_once '../../config.php';
}

header('Content-Type: application/json');

$response = [
    'success' => false,
    'message' => '',
    'errors' => []
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $brand_id = $_SESSION['user']['id'];
    $errors = [];

    // List of allowed values
    $allowed_statuses = ['draft', 'active', 'archived'];
    $allowed_visibilities = ['public', 'private'];
    $allowed_categories = [
        'Fashion',
        'Beauty',
        'Electronics',
        'Home & Living',
        'Food & Drinks',
        'Art & Craft',
        'Gadgets',
        'Furniture',
        'Paintings',
        'Sculptures',
        'Prints',
        'Snacks',
        'Herbs',
        'Spices',
        'Kitchen',
        'Streetwear',
        'Skincare',
        'Textiles',
        'Fashion Accessories',
        'Footwear',
        'Decor',
        'Toiletries',
        'Cosmetics',
        'Education',
        'Other'
    ];

    // Validate required fields
    $required_fields = ['name', 'category', 'price', 'stock', 'short_desc', 'ships_from', 'status', 'visibility'];
    foreach ($required_fields as $field) {
        if (!isset($_POST[$field]) || trim($_POST[$field]) === '') {
            $errors[] = ucfirst(str_replace('_', ' ', $field)) . ' is required';
        }
    }

    // Strict enum validation
    if (!empty($_POST['status']) && !in_array($_POST['status'], $allowed_statuses)) {
        $errors[] = 'Invalid status selected';
    }
    if (!empty($_POST['visibility']) && !in_array($_POST['visibility'], $allowed_visibilities)) {
        $errors[] = 'Invalid visibility selected';
    }
    if (!empty($_POST['category']) && !in_array($_POST['category'], $allowed_categories)) {
        $errors[] = 'Invalid category selected';
    }

    // Validate numeric fields
    if (isset($_POST['price']) && (!is_numeric($_POST['price']) || $_POST['price'] < 0)) {
        $errors[] = 'Price must be a non-negative number';
    }
    if (isset($_POST['stock']) && (!is_numeric($_POST['stock']) || $_POST['stock'] < 0)) {
        $errors[] = 'Stock must be a non-negative number';
    }
    if (!empty($_POST['compare_at_price']) && (!is_numeric($_POST['compare_at_price']) || $_POST['compare_at_price'] < 0)) {
        $errors[] = 'Compare at price must be a non-negative number';
    }
    if (!empty($_POST['shipping_fee']) && (!is_numeric($_POST['shipping_fee']) || $_POST['shipping_fee'] < 0)) {
        $errors[] = 'Shipping fee must be a non-negative number';
    }

    // Check if we are updating
    $action_type = isset($_POST['action_type']) ? $_POST['action_type'] : 'insert';
    $product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;

    if ($action_type === 'update') {
        if ($product_id <= 0) {
            $errors[] = 'Invalid product ID for update';
        } else {
            // Verify ownership
            $checkStmt = $conn->prepare("SELECT id, main_image FROM product WHERE id = ? AND brand_id = ?");
            $checkStmt->bind_param("ii", $product_id, $brand_id);
            $checkStmt->execute();
            $existingProduct = $checkStmt->get_result()->fetch_assoc();
            $checkStmt->close();

            if (!$existingProduct) {
                $errors[] = 'Product not found or access denied';
            }
        }
    }

    // Check main image - only required for new products
    if ($action_type === 'insert') {
        if (!isset($_FILES['main_image']) || $_FILES['main_image']['error'] != UPLOAD_ERR_OK) {
            $errors[] = 'Main product image is required';
        }
    }

    if (empty($errors)) {
        try {
            // Assign and sanitize
            $name = trim($_POST['name']);
            $category = $_POST['category'];
            $price = floatval($_POST['price']);
            $compare_at_price = !empty($_POST['compare_at_price']) ? floatval($_POST['compare_at_price']) : null;
            $stock = intval($_POST['stock']);
            $short_desc = trim($_POST['short_desc']);
            $long_desc = isset($_POST['long_desc']) ? trim($_POST['long_desc']) : null;
            $status = $_POST['status'];
            $visibility = $_POST['visibility'];
            $featured = isset($_POST['featured']) ? (int) $_POST['featured'] : 0;
            $shipping_fee = !empty($_POST['shipping_fee']) ? floatval($_POST['shipping_fee']) : 0.00;
            $ships_from = trim($_POST['ships_from']);
            $processing_time = isset($_POST['processing_time']) ? trim($_POST['processing_time']) : null;
            $variants_text = isset($_POST['variants_text']) ? trim($_POST['variants_text']) : null;

            // Generate slug
            $slug = !empty($_POST['slug']) ? sanitizeSlug($_POST['slug']) : sanitizeSlug($name);

            // Check slug uniqueness (excluding current product if updating)
            if ($action_type === 'update') {
                $slugCheckQuery = $conn->prepare("SELECT id FROM product WHERE slug = ? AND id != ?");
                $slugCheckQuery->bind_param("si", $slug, $product_id);
            } else {
                $slugCheckQuery = $conn->prepare("SELECT id FROM product WHERE slug = ?");
                $slugCheckQuery->bind_param("s", $slug);
            }
            $slugCheckQuery->execute();
            if ($slugCheckQuery->get_result()->num_rows > 0) {
                $slug = $slug . '-' . time();
            }
            $slugCheckQuery->close();

            // SKU
            $sku = !empty($_POST['sku']) ? trim($_POST['sku']) : 'PROD-' . strtoupper(substr(md5(uniqid()), 0, 8));

            // Main image
            $main_image_url = null;
            if (isset($_FILES['main_image']) && $_FILES['main_image']['error'] == UPLOAD_ERR_OK) {
                $main_image_url = uploadImage($_FILES['main_image']);
                // If update, delete old one
                if ($action_type === 'update' && !empty($existingProduct['main_image'])) {
                    $oldFile = '../../' . $existingProduct['main_image'];
                    if (file_exists($oldFile))
                        unlink($oldFile);
                }
            } elseif ($action_type === 'update') {
                $main_image_url = $existingProduct['main_image'];
            }

            if ($action_type === 'update') {
                $published_at_clause = "";
                if ($status === 'active' && empty($existingProduct['published_at'])) {
                    $published_at_clause = ", published_at = NOW()";
                }

                $stmt = $conn->prepare("
                    UPDATE product SET 
                        name = ?, slug = ?, sku = ?, category = ?, price = ?, compare_at_price = ?, stock = ?,
                        short_desc = ?, long_desc = ?, status = ?, visibility = ?, featured = ?, main_image = ?,
                        shipping_fee = ?, ships_from = ?, processing_time = ?, variants_text = ?,
                        updated_at = NOW() $published_at_clause
                    WHERE id = ? AND brand_id = ?
                ");
                $stmt->bind_param(
                    "ssssddissssssdsssii",
                    $name,
                    $slug,
                    $sku,
                    $category,
                    $price,
                    $compare_at_price,
                    $stock,
                    $short_desc,
                    $long_desc,
                    $status,
                    $visibility,
                    $featured,
                    $main_image_url,
                    $shipping_fee,
                    $ships_from,
                    $processing_time,
                    $variants_text,
                    $product_id,
                    $brand_id
                );
            } else {
                $stmt = $conn->prepare("
                    INSERT INTO product (
                        brand_id, name, slug, sku, category, price, compare_at_price, stock,
                        short_desc, long_desc, status, visibility, featured, main_image,
                        shipping_fee, ships_from, processing_time, variants_text,
                        rating, total_reviews, total_sales, views, created_at, published_at
                    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 0, 0, 0, 0, NOW(), ?)
                ");
                $published_at = ($status == 'active') ? date('Y-m-d H:i:s') : null;
                $stmt->bind_param(
                    "issssddissssssdssss",
                    $brand_id,
                    $name,
                    $slug,
                    $sku,
                    $category,
                    $price,
                    $compare_at_price,
                    $stock,
                    $short_desc,
                    $long_desc,
                    $status,
                    $visibility,
                    $featured,
                    $main_image_url,
                    $shipping_fee,
                    $ships_from,
                    $processing_time,
                    $variants_text,
                    $published_at
                );
            }

            if ($stmt->execute()) {
                if ($action_type === 'insert') {
                    $product_id = $stmt->insert_id;
                }

                // Handle gallery images
                if (!empty($_FILES['gallery']['name'][0])) {
                    handleGalleryImages($conn, $product_id);
                }

                $response['success'] = true;
                $response['message'] = $action_type === 'update'
                    ? 'Product updated successfully!'
                    : ($status == 'draft' ? 'Product saved as draft successfully!' : 'Product published successfully!');
            } else {
                $response['errors'][] = 'Failed to save product: ' . $stmt->error;
            }
            $stmt->close();
        } catch (Exception $e) {
            $response['errors'][] = 'An error occurred: ' . $e->getMessage();
        }
    } else {
        $response['errors'] = $errors;
    }
} else {
    $response['errors'][] = 'Invalid request method';
}

echo json_encode($response);

// Helper functions
function sanitizeSlug($text)
{
    $text = preg_replace('~[^\pL\d]+~u', '-', $text);
    $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
    $text = preg_replace('~[^-\w]+~', '', $text);
    $text = trim($text, '-');
    $text = preg_replace('~-+~', '-', $text);
    $text = strtolower($text);
    if (empty($text))
        $text = 'product-' . time();
    return $text;
}

function uploadImage($file)
{
    $uploadDir = '../../Assets/products/';
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $allowed_types = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/webp'];
    $max_size = 5 * 1024 * 1024;

    if (!in_array($file['type'], $allowed_types)) {
        throw new Exception('Invalid file type. Only JPG, PNG, GIF, and WebP are allowed.');
    }
    if ($file['size'] > $max_size) {
        throw new Exception('File size exceeds 5MB limit.');
    }

    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = 'product_main_' . time() . '_' . uniqid() . '.' . $extension;
    $filepath = $uploadDir . $filename;

    if (move_uploaded_file($file['tmp_name'], $filepath)) {
        return 'Assets/products/' . $filename;
    } else {
        throw new Exception('Failed to upload image.');
    }
}

function handleGalleryImages($conn, $product_id)
{
    $uploadDir = '../../Assets/products/';
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $galleryStmt = $conn->prepare("INSERT INTO productimage (product_id, image_url, sort_order, created_at) VALUES (?, ?, ?, NOW())");
    $allowed_types = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/webp'];
    $max_size = 5 * 1024 * 1024;
    $max_images = 8;
    $uploaded_count = 0;

    foreach ($_FILES['gallery']['tmp_name'] as $index => $tmp_name) {
        if ($uploaded_count >= $max_images)
            break;

        if ($_FILES['gallery']['error'][$index] == UPLOAD_ERR_OK) {
            $file_type = $_FILES['gallery']['type'][$index];
            $file_size = $_FILES['gallery']['size'][$index];

            if (!in_array($file_type, $allowed_types))
                continue;
            if ($file_size > $max_size)
                continue;

            $extension = pathinfo($_FILES['gallery']['name'][$index], PATHINFO_EXTENSION);
            $filename = 'product_gallery_' . time() . '_' . $index . '_' . uniqid() . '.' . $extension;
            $filepath = $uploadDir . $filename;

            if (move_uploaded_file($tmp_name, $filepath)) {
                $image_url = 'Assets/products/' . $filename;
                $galleryStmt->bind_param("isi", $product_id, $image_url, $uploaded_count);
                $galleryStmt->execute();
                $uploaded_count++;
            }
        }
    }
    $galleryStmt->close();
}
