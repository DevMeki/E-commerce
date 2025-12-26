<?php
require_once 'process/check_brand_login.php';

// Include DB connection
if (file_exists('../config.php')) {
    require_once '../config.php';
}

$brand_id = $_SESSION['user']['id'];

// Check if we are in edit mode
$isEdit = false;
$productData = [];
$galleryImages = [];

if (isset($_GET['id'])) {
    $product_id = intval($_GET['id']);
    // Fetch product details
    $stmt = $conn->prepare("SELECT * FROM product WHERE id = ? AND brand_id = ?");
    $stmt->bind_param("ii", $product_id, $brand_id);
    $stmt->execute();
    $res = $stmt->get_result();
    $productData = $res->fetch_assoc();
    $stmt->close();

    if ($productData) {
        $isEdit = true;

        // Fetch gallery images
        $galleryStmt = $conn->prepare("SELECT id, image_url FROM productimage WHERE product_id = ?");
        $galleryStmt->bind_param("i", $product_id);
        $galleryStmt->execute();
        $galleryRes = $galleryStmt->get_result();
        while ($row = $galleryRes->fetch_assoc()) {
            $galleryImages[] = $row;
        }
        $galleryStmt->close();
    }
}

// Get brand details for shipping location
$brandQuery = $conn->prepare("SELECT location FROM brand WHERE id = ?");
$brandQuery->bind_param("i", $brand_id);
$brandQuery->execute();
$brandResult = $brandQuery->get_result();
$brandData = $brandResult->fetch_assoc();
$defaultShipping = $brandData['location'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title><?= $isEdit ? 'Edit Product' : 'Add Product' ?> | LocalTrade</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'brand-forest': '#1E3932',
                        'brand-orange': '#F36A1D',
                        'brand-parchment': '#FCFBF7',
                        'brand-ink': '#1A1A1A',
                        'brand-cream': '#F3F0E6',
                    }
                }
            }
        }
    </script>
</head>

<body class="bg-brand-parchment text-brand-ink min-h-screen flex flex-col">

    <!-- HEADER -->
    <?php
    $currentBrandPage = 'products';
    include 'brand-header.php';
    ?>

    <!-- MAIN -->
    <main class="flex-1 py-6 sm:py-8">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Title row -->
            <div class="flex items-center justify-between mb-5 sm:mb-7">
                <div>
                    <h1 class="text-xl sm:text-2xl font-semibold text-brand-forest">
                        <?= $isEdit ? 'Edit product' : 'Add new product' ?>
                    </h1>
                    <p class="text-xs sm:text-sm text-brand-ink/50 mt-1">
                        <?= $isEdit ? 'Update your product details and availability.' : 'Create a new product listing for your LocalTrade store.' ?>
                    </p>
                </div>
                <a href="products" class="text-xs text-brand-ink/60 hover:text-brand-orange transition-colors">
                    ← Back to Products
                </a>
            </div>

            <!-- Messages -->
            <div id="messageContainer"></div>

            <!-- FORM -->
            <form id="productForm" method="post" enctype="multipart/form-data"
                class="grid lg:grid-cols-[minmax(0,2fr)_minmax(260px,1fr)] gap-6 lg:gap-8">
                <!-- Add featured field as hidden input -->
                <input type="hidden" name="featured" value="<?= htmlspecialchars($productData['featured'] ?? '0') ?>">
                <?php if ($isEdit): ?>
                    <input type="hidden" name="product_id" value="<?= $productData['id'] ?>">
                    <input type="hidden" name="action_type" value="update">
                <?php endif; ?>

                <!-- LEFT: Product details -->
                <section class="space-y-5">
                    <!-- Basic info -->
                    <div
                        class="bg-green-50 border border-brand-forest/5 rounded-2xl p-4 sm:p-5 space-y-4 text-sm shadow-sm">
                        <h2 class="text-sm font-semibold mb-1 text-brand-forest">Product details</h2>

                        <div>
                            <label class="block text-xs mb-1 text-brand-ink/70 font-medium">Product name *</label>
                            <input type="text" name="name" required
                                value="<?= htmlspecialchars($productData['name'] ?? $_POST['name'] ?? '') ?>"
                                class="w-full bg-brand-parchment border border-brand-forest/10 rounded-xl px-3 py-2 text-sm text-brand-ink placeholder-brand-ink/40 focus:outline-none focus:ring-1 focus:ring-brand-orange"
                                placeholder="e.g. Ankara Panel Hoodie">
                        </div>

                        <div>
                            <label class="block text-xs mb-1 text-brand-ink/70 font-medium">Product URL slug
                                (optional)</label>
                            <div class="flex items-center gap-2 text-xs">
                                <span
                                    class="bg-brand-parchment border border-brand-forest/10 rounded-xl px-2 py-2 text-brand-ink/40">
                                    localtrade.ng/product/
                                </span>
                                <input type="text" name="slug"
                                    value="<?= htmlspecialchars($productData['slug'] ?? $_POST['slug'] ?? '') ?>"
                                    class="flex-1 bg-brand-parchment border border-brand-forest/10 rounded-xl px-3 py-2 text-xs text-brand-ink placeholder-brand-ink/40 focus:outline-none focus:ring-1 focus:ring-brand-orange"
                                    placeholder="ankara-panel-hoodie">
                            </div>
                            <p class="mt-1 text-[10px] text-brand-ink/40">
                                Only letters, numbers and hyphens. Leave blank to auto-generate.
                            </p>
                        </div>

                        <div class="grid sm:grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs mb-1 text-brand-ink/70 font-medium">Category *</label>
                                <select name="category" id="categorySelect" required
                                    class="w-full bg-brand-parchment border border-brand-forest/10 rounded-xl px-3 py-2 text-xs text-brand-ink focus:outline-none focus:ring-1 focus:ring-brand-orange">
                                    <option value="">Select a category</option>
                                    <?php
                                    $categories = [
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
                                    $selected = $productData['category'] ?? $_POST['category'] ?? '';

                                    $categoryDescriptions = [
                                        'Fashion' => 'Fashion (Streetwear, Ankara, accessories)',
                                        'Beauty' => 'Beauty (Skincare, haircare, self-care)',
                                        'Electronics' => 'Electronics (Headphones, accessories, smart tech)',
                                        'Home & Living' => 'Home & Living (Decor, kitchen, furniture)',
                                        'Food & Drinks' => 'Food & Drinks (Snacks, pantry, local specials)',
                                        'Art & Craft' => 'Art & Craft (Handmade pieces from Nigerian artists)',
                                        'Gadgets' => 'Gadgets (Headphones, accessories, smart tech)',
                                        'Furniture' => 'Furniture (Home and office furniture)',
                                        'Paintings' => 'Paintings (Artworks and paintings)',
                                        'Sculptures' => 'Sculptures (Handmade sculptures)',
                                        'Prints' => 'Prints (Art prints and posters)',
                                        'Snacks' => 'Snacks (Local snacks and treats)',
                                        'Herbs' => 'Herbs (Fresh and dried herbs)',
                                        'Spices' => 'Spices (Local spices and seasonings)',
                                        'Kitchen' => 'Kitchen (Kitchen utensils and appliances)',
                                        'Streetwear' => 'Streetwear (Urban fashion and accessories)',
                                        'Skincare' => 'Skincare (Natural skincare products)',
                                        'Textiles' => 'Textiles (Fabrics and clothing materials)',
                                        'Fashion Accessories' => 'Fashion Accessories (Jewelry and bags)',
                                        'Footwear' => 'Footwear (Shoes, slippers and sandals)',
                                        'Decor' => 'Decor (Home decoration items)',
                                        'Toiletries' => 'Toiletries (Personal care products)',
                                        'Cosmetics' => 'Cosmetics (Makeup, perfume, skincare)',
                                        'Education' => 'Education (Books, stationery, learning materials)',
                                        'Other' => 'Other'
                                    ];

                                    foreach ($categories as $cat) {
                                        $sel = ($selected === $cat) ? 'selected' : '';
                                        $display = $categoryDescriptions[$cat] ?? $cat;
                                        echo "<option value=\"$cat\" $sel>$display</option>";
                                    }
                                    ?>
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs mb-1 text-brand-ink/70 font-medium">SKU (optional)</label>
                                <input type="text" name="sku"
                                    value="<?= htmlspecialchars($productData['sku'] ?? $_POST['sku'] ?? '') ?>"
                                    class="w-full bg-brand-parchment border border-brand-forest/10 rounded-xl px-3 py-2 text-sm text-brand-ink placeholder-brand-ink/40 focus:outline-none focus:ring-1 focus:ring-brand-orange"
                                    placeholder="Internal stock code">
                            </div>
                        </div>
                    </div>

                    <!-- Pricing / inventory -->
                    <div
                        class="bg-green-50 border border-brand-forest/5 rounded-2xl p-4 sm:p-5 space-y-4 text-sm shadow-sm">
                        <h2 class="text-sm font-semibold mb-1 text-brand-forest">Pricing & inventory</h2>

                        <div class="grid sm:grid-cols-3 gap-3">
                            <div>
                                <label class="block text-xs mb-1 text-brand-ink/70 font-medium">Price (₦) *</label>
                                <input type="number" name="price" required min="0" step="0.01"
                                    value="<?= htmlspecialchars($productData['price'] ?? $_POST['price'] ?? '') ?>"
                                    class="w-full bg-brand-parchment border border-brand-forest/10 rounded-xl px-3 py-2 text-sm text-brand-ink placeholder-brand-ink/40 focus:outline-none focus:ring-1 focus:ring-brand-orange"
                                    placeholder="18500">
                            </div>
                            <div>
                                <label class="block text-xs mb-1 text-brand-ink/70 font-medium">Compare at price
                                    (optional)</label>
                                <input type="number" name="compare_at_price" min="0" step="0.01"
                                    value="<?= htmlspecialchars($productData['compare_at_price'] ?? $_POST['compare_at_price'] ?? '') ?>"
                                    class="w-full bg-brand-parchment border border-brand-forest/10 rounded-xl px-3 py-2 text-sm text-brand-ink placeholder-brand-ink/40 focus:outline-none focus:ring-1 focus:ring-brand-orange"
                                    placeholder="e.g. 22000">
                            </div>
                            <div>
                                <label class="block text-xs mb-1 text-brand-ink/70 font-medium">Stock *</label>
                                <input type="number" name="stock" required min="0"
                                    value="<?= htmlspecialchars($productData['stock'] ?? $_POST['stock'] ?? '') ?>"
                                    class="w-full bg-brand-parchment border border-brand-forest/10 rounded-xl px-3 py-2 text-sm text-brand-ink placeholder-brand-ink/40 focus:outline-none focus:ring-1 focus:ring-brand-orange"
                                    placeholder="e.g. 25">
                            </div>
                        </div>

                        <div class="grid sm:grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs mb-1 text-brand-ink/70 font-medium">Status</label>
                                <select name="status"
                                    class="w-full bg-brand-parchment border border-brand-forest/10 rounded-xl px-3 py-2 text-xs text-brand-ink focus:outline-none focus:ring-1 focus:ring-brand-orange">
                                    <option value="active" <?= ($productData['status'] ?? $_POST['status'] ?? 'active') === 'active' ? 'selected' : '' ?>>Active</option>
                                    <option value="draft" <?= ($productData['status'] ?? $_POST['status'] ?? '') === 'draft' ? 'selected' : '' ?>>Draft</option>
                                    <option value="archived" <?= ($productData['status'] ?? $_POST['status'] ?? '') === 'archived' ? 'selected' : '' ?>>Archived</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs mb-1 text-brand-ink/70 font-medium">Visibility</label>
                                <select name="visibility"
                                    class="w-full bg-brand-parchment border border-brand-forest/10 rounded-xl px-3 py-2 text-xs text-brand-ink focus:outline-none focus:ring-1 focus:ring-brand-orange">
                                    <option value="public" <?= ($productData['visibility'] ?? $_POST['visibility'] ?? 'public') === 'public' ? 'selected' : '' ?>>Public (visible in marketplace)
                                    </option>
                                    <option value="private" <?= ($productData['visibility'] ?? $_POST['visibility'] ?? '') === 'private' ? 'selected' : '' ?>>Private (only via direct link)</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Descriptions -->
                    <div
                        class="bg-green-50 border border-brand-forest/5 rounded-2xl p-4 sm:p-5 space-y-4 text-sm shadow-sm">
                        <h2 class="text-sm font-semibold mb-1 text-brand-forest">Descriptions</h2>

                        <div>
                            <label class="block text-xs mb-1 text-brand-ink/70 font-medium">Short description *</label>
                            <textarea name="short_desc" rows="2" required
                                class="w-full bg-brand-parchment border border-brand-forest/10 rounded-xl px-3 py-2 text-sm text-brand-ink placeholder-brand-ink/40 focus:outline-none focus:ring-1 focus:ring-brand-orange"
                                placeholder="One or two lines that summarise the product..."><?= htmlspecialchars($productData['short_desc'] ?? $_POST['short_desc'] ?? '') ?></textarea>
                        </div>

                        <div>
                            <label class="block text-xs mb-1 text-brand-ink/70 font-medium">Detailed description</label>
                            <textarea name="long_desc" rows="5"
                                class="w-full bg-brand-parchment border border-brand-forest/10 rounded-xl px-3 py-2 text-sm text-brand-ink placeholder-brand-ink/40 focus:outline-none focus:ring-1 focus:ring-brand-orange"
                                placeholder="Materials, sizing, care instructions, what makes it unique..."><?= htmlspecialchars($productData['long_desc'] ?? $_POST['long_desc'] ?? '') ?></textarea>
                            <p class="mt-1 text-[10px] text-brand-ink/40">
                                Use short paragraphs and bullet points for readability.
                            </p>
                        </div>
                    </div>

                    <!-- Shipping -->
                    <div
                        class="bg-green-50 border border-brand-forest/5 rounded-2xl p-4 sm:p-5 space-y-4 text-sm shadow-sm">
                        <h2 class="text-sm font-semibold mb-1 text-brand-forest">Shipping</h2>

                        <div class="grid sm:grid-cols-3 gap-3">
                            <div class="sm:col-span-2">
                                <label class="block text-xs mb-1 text-brand-ink/70 font-medium">Ships from *</label>
                                <input type="text" name="ships_from" required
                                    value="<?= htmlspecialchars($productData['ships_from'] ?? $_POST['ships_from'] ?? $defaultShipping) ?>"
                                    class="w-full bg-brand-parchment border border-brand-forest/10 rounded-xl px-3 py-2 text-sm text-brand-ink placeholder-brand-ink/40 focus:outline-none focus:ring-1 focus:ring-brand-orange"
                                    placeholder="e.g. Lagos, Nigeria">
                            </div>
                            <div>
                                <label class="block text-xs mb-1 text-brand-ink/70 font-medium">Base shipping fee
                                    (₦)</label>
                                <input type="number" name="shipping_fee" min="0" step="0.01"
                                    value="<?= htmlspecialchars($productData['shipping_fee'] ?? $_POST['shipping_fee'] ?? '') ?>"
                                    class="w-full bg-brand-parchment border border-brand-forest/10 rounded-xl px-3 py-2 text-sm text-brand-ink placeholder-brand-ink/40 focus:outline-none focus:ring-1 focus:ring-brand-orange"
                                    placeholder="e.g. 2500">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs mb-1 text-brand-ink/70 font-medium">Processing time
                                (optional)</label>
                            <input type="text" name="processing_time"
                                value="<?= htmlspecialchars($productData['processing_time'] ?? $_POST['processing_time'] ?? '') ?>"
                                class="w-full sm:w-64 bg-brand-parchment border border-brand-forest/10 rounded-xl px-3 py-2 text-sm text-brand-ink placeholder-brand-ink/40 focus:outline-none focus:ring-1 focus:ring-brand-orange"
                                placeholder="e.g. 2–4 business days">
                        </div>
                    </div>
                </section>

                <!-- RIGHT: Images, variants, actions -->
                <aside class="space-y-5">
                    <!-- Images -->
                    <div class="bg-green-50 border border-brand-forest/5 rounded-2xl p-4 sm:p-5 text-sm shadow-sm">
                        <h2 class="text-sm font-semibold mb-2 text-brand-forest">Product images</h2>
                        <p class="text-[11px] text-brand-ink/40 mb-3">
                            Upload at least one clear photo. Add more angles if possible.
                        </p>

                        <!-- Main image preview -->
                        <div class="flex flex-col gap-3">
                            <div id="mainImagePreview"
                                class="w-full aspect-[4/3] rounded-2xl bg-brand-parchment border border-brand-forest/10 flex items-center justify-center text-[11px] text-brand-ink/40 overflow-hidden"
                                style="<?= $isEdit && !empty($productData['main_image']) ? "background-image: url('../{$productData['main_image']}'); background-size: cover; background-position: center;" : "border-style: dashed;" ?>">
                                <?= $isEdit && !empty($productData['main_image']) ? '' : 'Main image preview' ?>
                            </div>
                            <input type="file" id="mainImageInput" name="main_image" accept="image/*" <?= $isEdit ? '' : 'required' ?>
                                class="block w-full text-xs text-brand-ink/40 file:mr-3 file:px-3 file:py-1.5 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-brand-orange file:text-white hover:file:bg-brand-orange/90 cursor-pointer">
                        </div>

                        <!-- Gallery images -->
                        <div class="mt-4">
                            <label class="block text-xs mb-1 text-brand-ink/70 font-medium">Additional images
                                (optional)</label>
                            <input type="file" id="galleryInput" name="gallery[]" accept="image/*" multiple
                                class="block w-full text-xs text-brand-ink/40 file:mr-3 file:px-3 file:py-1.5 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-brand-orange file:text-white hover:file:bg-brand-orange/90 cursor-pointer">
                            <p class="mt-1 text-[10px] text-brand-ink/40">
                                You can upload up to 8 additional images.
                            </p>
                            <div id="existingGalleryPreview" class="mt-2 grid grid-cols-4 gap-2">
                                <?php foreach ($galleryImages as $img): ?>
                                    <div id="gallery-img-<?= $img['id'] ?>"
                                        class="relative w-full aspect-square rounded-xl overflow-hidden bg-brand-parchment border border-brand-forest/10">
                                        <img src="../<?= $img['image_url'] ?>" class="w-full h-full object-cover">
                                        <button type="button" onclick="deleteExistingImage(<?= $img['id'] ?>)"
                                            class="absolute top-1 right-1 w-6 h-6 rounded-full bg-brand-forest/80 text-white text-sm flex items-center justify-center border border-white/20 hover:bg-red-600 transition-colors">
                                            ×
                                        </button>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <div id="galleryPreview" class="mt-2 grid grid-cols-4 gap-2"></div>
                        </div>

                        <script>
                            async function deleteExistingImage(imgId) {
                                if (confirm('Are you sure you want to delete this gallery image?')) {
                                    try {
                                        const formData = new FormData();
                                        formData.append('image_id', imgId);
                                        formData.append('action', 'delete_image');

                                        const response = await fetch('process/process-product-action.php', {
                                            method: 'POST',
                                            body: formData
                                        });
                                        const data = await response.json();

                                        if (data.success) {
                                            const el = document.getElementById(`gallery-img-${imgId}`);
                                            if (el) el.remove();
                                        } else {
                                            alert(data.message || 'Failed to delete image.');
                                        }
                                    } catch (error) {
                                        alert('Error connecting to server.');
                                    }
                                }
                            }
                        </script>
                    </div>

                    <!-- Variants -->
                    <div class="bg-green-50 border border-brand-forest/5 rounded-2xl p-4 sm:p-5 text-sm shadow-sm">
                        <h2 class="text-sm font-semibold mb-2 text-brand-forest">Variants (optional)</h2>
                        <p class="text-[11px] text-brand-ink/40 mb-3">
                            Use this field to note sizes, colours, etc.
                        </p>
                        <textarea name="variants_text" rows="3"
                            class="w-full bg-brand-parchment border border-brand-forest/10 rounded-xl px-3 py-2 text-sm text-brand-ink placeholder-brand-ink/40 focus:outline-none focus:ring-1 focus:ring-brand-orange"
                            placeholder="e.g. Sizes: S, M, L, XL · Colours: Black, White, Orange"><?= htmlspecialchars($productData['variants_text'] ?? $_POST['variants_text'] ?? '') ?></textarea>
                    </div>

                    <!-- Submit -->
                    <div class="bg-green-50 border border-brand-forest/5 rounded-2xl p-4 sm:p-5 text-sm shadow-sm">
                        <h2 class="text-sm font-semibold mb-2 text-brand-forest">
                            <?= $isEdit ? 'Update product' : 'Save product' ?>
                        </h2>
                        <p class="text-[11px] text-brand-ink/40 mb-3">
                            <?= $isEdit ? 'Save your changes to update the live product.' : 'You can always edit this product later from your dashboard.' ?>
                        </p>

                        <div class="flex flex-col gap-2">
                            <button type="submit" id="submitBtn"
                                class="w-full px-4 py-2.5 rounded-full text-sm font-bold bg-brand-orange text-white hover:bg-brand-orange/90 shadow-sm shadow-brand-orange/20 transition-all">
                                <?= $isEdit ? 'Save changes' : 'Publish product' ?>
                            </button>
                            <button type="button" id="draftBtn"
                                class="w-full px-4 py-2.5 rounded-full text-sm font-semibold bg-white border border-brand-forest/10 text-brand-forest hover:border-brand-orange transition-all">
                                Save as draft
                            </button>
                        </div>

                        <p class="mt-3 text-[10px] text-brand-ink/40">
                            Products marked as <span class="text-brand-forest font-semibold">Draft</span> will not be
                            visible in the marketplace.
                        </p>
                    </div>
                </aside>
            </form>
        </div>
    </main>

    <script src="js/add-product.js"></script>
</body>

</html>