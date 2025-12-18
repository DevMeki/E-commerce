<?php
// ---------- SIMPLE PLACEHOLDER BACKEND LOGIC ----------
// In production you will:
// - Check seller is logged in
// - Validate deeply
// - Save product + images to DB
// - Move uploaded files to /uploads/products

$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name         = trim($_POST['name']        ?? '');
    $slug         = trim($_POST['slug']        ?? '');
    $category     = trim($_POST['category']    ?? '');
    $price        = trim($_POST['price']       ?? '');
    $stock        = trim($_POST['stock']       ?? '');
    $sku          = trim($_POST['sku']         ?? '');
    $status       = trim($_POST['status']      ?? 'active');
    $visibility   = trim($_POST['visibility']  ?? 'public');
    $shortDesc    = trim($_POST['short_desc']  ?? '');
    $longDesc     = trim($_POST['long_desc']   ?? '');
    $shippingFee  = trim($_POST['shipping_fee'] ?? '');
    $shipsFrom    = trim($_POST['ships_from']  ?? '');
    $processing   = trim($_POST['processing_time'] ?? '');
    $variantsText = trim($_POST['variants_text'] ?? '');

    if ($name === '')      $errors[] = 'Product name is required.';
    if ($category === '')  $errors[] = 'Category is required.';
    if ($price === '' || !is_numeric($price)) $errors[] = 'Valid price is required.';
    if ($stock === '' || !ctype_digit($stock)) $errors[] = 'Stock must be a whole number.';
    if ($shortDesc === '') $errors[] = 'A short description is required.';
    if ($shipsFrom === '') $errors[] = 'Shipping origin is required.';

    // Basic image requirement (optional – you can enforce later)
    if (!isset($_FILES['main_image']) || $_FILES['main_image']['error'] !== UPLOAD_ERR_OK) {
        // $errors[] = 'Main product image is required.';
    }

    // Enforce max 8 additional images (server-side)
    $galleryCount = 0;
    if (isset($_FILES['gallery']) && is_array($_FILES['gallery']['name'])) {
        foreach ($_FILES['gallery']['name'] as $idx => $fileName) {
            if (!empty($fileName) && ($_FILES['gallery']['error'][$idx] === UPLOAD_ERR_OK)) {
                $galleryCount++;
            }
        }
    }
    if ($galleryCount > 8) {
        $errors[] = 'You can upload a maximum of 8 additional images.';
    }

    if (empty($errors)) {
        // TODO: move_uploaded_file() for main_image & gallery[]
        // TODO: insert into DB
        $success = 'Product created successfully (demo only – connect to database + storage).';
    }
}
// If editing via query params and not submitting the form, prefill variables from GET
if ($_SERVER['REQUEST_METHOD'] !== 'POST' && isset($_GET['edit'])) {
    // Only assign if present in query string; this keeps POST values winning when form is submitted
    $name = $_GET['name'] ?? ($name ?? '');
    $slug = $_GET['slug'] ?? ($slug ?? '');
    $category = $_GET['category'] ?? ($category ?? '');
    $price = $_GET['price'] ?? ($price ?? '');
    $stock = $_GET['stock'] ?? ($stock ?? '');
    $sku = $_GET['sku'] ?? ($sku ?? '');
    $status = $_GET['status'] ?? ($status ?? 'active');
    $visibility = $_GET['visibility'] ?? ($visibility ?? 'public');
    $shortDesc = $_GET['short_desc'] ?? ($shortDesc ?? '');
    $longDesc = $_GET['long_desc'] ?? ($longDesc ?? '');
    $shippingFee = $_GET['shipping_fee'] ?? ($shippingFee ?? '');
    $shipsFrom = $_GET['ships_from'] ?? ($shipsFrom ?? '');
    $processing = $_GET['processing_time'] ?? ($processing ?? '');
    $variantsText = $_GET['variants_text'] ?? ($variantsText ?? '');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Product | LocalTrade</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        :root {
            --lt-orange: #F36A1D;
            --lt-black: #0D0D0D;
        }
    </style>
</head>
<body class="bg-[#0D0D0D] text-white min-h-screen flex flex-col">

<!-- HEADER -->
<header class="border-b border-white/10 bg-black/60 backdrop-blur">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 h-14 flex items-center justify-between">
        <a href="index" class="flex items-center gap-2">
            <div class="w-8 h-8 rounded-full flex items-center justify-center"
                 style="background-color: var(--lt-orange);">
                <div class="w-4 h-3 border-2 border-white border-b-0 rounded-sm relative">
                    <span class="w-1 h-1 bg-white rounded-full absolute -bottom-1 left-0.5"></span>
                    <span class="w-1 h-1 bg-white rounded-full absolute -bottom-1 right-0.5"></span>
                </div>
            </div>
            <span class="font-semibold tracking-tight text-lg">LocalTrade</span>
        </a>
        <div class="flex items-center gap-4 text-xs sm:text-sm">
            <a href="brand-dashboard" class="text-gray-300 hover:text-orange-400">Dashboard</a>
            <a href="../store" class="text-gray-300 hover:text-orange-400">Store</a>
        </div>
    </div>
</header>

<!-- MAIN -->
<main class="flex-1 py-6 sm:py-8">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Title row -->
        <div class="flex items-center justify-between mb-5 sm:mb-7">
            <div>
                <h1 class="text-xl sm:text-2xl font-semibold">Add new product</h1>
                <p class="text-xs sm:text-sm text-gray-400 mt-1">
                    Create a new product listing for your LocalTrade store.
                </p>
            </div>
            <a href="store" class="text-xs text-gray-300 hover:text-orange-400">
                ← Back to your Store
            </a>
        </div>

        <!-- Errors / success -->
        <?php if (!empty($errors)): ?>
            <div class="mb-4 bg-red-500/10 border border-red-500/40 text-red-200 px-3 py-3 rounded-xl text-xs">
                <ul class="list-disc list-inside space-y-1">
                    <?php foreach ($errors as $e): ?>
                        <li><?= htmlspecialchars($e) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="mb-4 bg-green-500/10 border border-green-500/40 text-green-200 px-3 py-3 rounded-xl text-xs">
                <?= htmlspecialchars($success) ?>
            </div>
        <?php endif; ?>

        <!-- FORM -->
        <form method="post" enctype="multipart/form-data" class="grid lg:grid-cols-[minmax(0,2fr)_minmax(260px,1fr)] gap-6 lg:gap-8">

            <!-- LEFT: Product details -->
            <section class="space-y-5">
                <!-- Basic info -->
                <div class="bg-[#111111] border border-white/10 rounded-2xl p-4 sm:p-5 space-y-4 text-sm">
                    <h2 class="text-sm font-semibold mb-1">Product details</h2>

                    <div>
                        <label class="block text-xs mb-1">Product name</label>
                        <input
                            type="text"
                            name="name"
                            value="<?= htmlspecialchars($name ?? '') ?>"
                            class="w-full bg-[#0B0B0B] border border-white/20 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500"
                            placeholder="e.g. Ankara Panel Hoodie"
                        >
                    </div>

                    <div>
                        <label class="block text-xs mb-1">Product URL slug (optional)</label>
                        <div class="flex items-center gap-2 text-xs">
                            <span class="bg-[#0B0B0B] border border-white/20 rounded-xl px-2 py-2 text-gray-500">
                                localtrade.ng/product/
                            </span>
                            <input
                                type="text"
                                name="slug"
                                value="<?= htmlspecialchars($slug ?? '') ?>"
                                class="flex-1 bg-[#0B0B0B] border border-white/20 rounded-xl px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-orange-500"
                                placeholder="ankara-panel-hoodie"
                            >
                        </div>
                        <p class="mt-1 text-[10px] text-gray-500">
                            Only letters, numbers and hyphens. Leave blank to auto-generate.
                        </p>
                    </div>

                    <div class="grid sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs mb-1">Category</label>
                            <select
                                name="category"
                                id="categorySelect"
                                class="w-full bg-[#0B0B0B] border border-white/20 rounded-xl px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-orange-500"
                            >
                                <option value="">Select a category</option>
                                <?php
                                $productCategories = [
                                    'Fashion (Streetwear, Ankara, accessories)',
                                    'Beauty (Skincare, haircare, self-care)', 
                                    'Electronics (Headphones, accessories, smart tech)', 
                                    'Home & Living (Decor, kitchen, furniture)', 
                                    'Food & Drinks (Snacks, pantry, local specials)', 
                                    'Art & Craft (Handmade pieces from Nigerian artists)', 
                                    'Gadgets (Headphones, accessories, smart tech)',
                                    'Furniture (Home and office furniture)', 
                                    'Paintings (Artworks and paintings)', 
                                    'Sculptures (Handmade sculptures)', 
                                    'Prints (Art prints and posters)', 
                                    'Snacks (Local snacks and treats)', 
                                    'Herbs (Fresh and dried herbs)', 
                                    'Spices (Local spices and seasonings)', 
                                    'Kitchen (Kitchen utensils and appliances)', 
                                    'Streetwear (Urban fashion and accessories)', 
                                    'Skincare (Natural skincare products)', 
                                    'Textiles (Fabrics and clothing materials)', 
                                    'Fashion Accessories (Jewelry and bags)',
                                    'Footwear (Shoes, slippers and sandals)', 
                                    'Decor (Home decoration items)', 
                                    'Other'
                                ];
                                
                                foreach ($productCategories as $cat):
                                    // Extract the clean category name (before the bracket)
                                    $cleanCat = trim(explode('(', $cat)[0]);
                                    
                                    // Check if this option should be selected
                                    $sel = (($category ?? '') === $cleanCat) ? 'selected' : '';
                                    
                                    // Output option with clean value but full display text
                                    echo "<option value=\"" . htmlspecialchars($cleanCat) . "\" $sel>" . 
                                         htmlspecialchars($cat) . "</option>";
                                endforeach;
                                ?>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs mb-1">SKU (optional)</label>
                            <input
                                type="text"
                                name="sku"
                                value="<?= htmlspecialchars($sku ?? '') ?>"
                                class="w-full bg-[#0B0B0B] border border-white/20 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500"
                                placeholder="Internal stock code"
                            >
                        </div>
                    </div>
                </div>

                <!-- Pricing / inventory -->
                <div class="bg-[#111111] border border-white/10 rounded-2xl p-4 sm:p-5 space-y-4 text-sm">
                    <h2 class="text-sm font-semibold mb-1">Pricing & inventory</h2>

                    <div class="grid sm:grid-cols-3 gap-3">
                        <div>
                            <label class="block text-xs mb-1">Price (₦)</label>
                            <input
                                type="number"
                                name="price"
                                value="<?= htmlspecialchars($price ?? '') ?>"
                                class="w-full bg-[#0B0B0B] border border-white/20 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500"
                                placeholder="18500"
                                min="0"
                            >
                        </div>
                        <div>
                            <label class="block text-xs mb-1">Stock</label>
                            <input
                                type="number"
                                name="stock"
                                value="<?= htmlspecialchars($stock ?? '') ?>"
                                class="w-full bg-[#0B0B0B] border border-white/20 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500"
                                placeholder="e.g. 25"
                                min="0"
                            >
                        </div>
                        <div>
                            <label class="block text-xs mb-1">Status</label>
                            <select
                                name="status"
                                class="w-full bg-[#0B0B0B] border border-white/20 rounded-xl px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-orange-500"
                            >
                                <option value="active"   <?= ($status ?? '') === 'active' ? 'selected' : '' ?>>Active</option>
                                <option value="draft"    <?= ($status ?? '') === 'draft' ? 'selected' : '' ?>>Draft</option>
                                <option value="archived" <?= ($status ?? '') === 'archived' ? 'selected' : '' ?>>Archived</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs mb-1">Visibility</label>
                        <select
                            name="visibility"
                            class="w-full sm:w-48 bg-[#0B0B0B] border border-white/20 rounded-xl px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-orange-500"
                        >
                            <option value="public"  <?= ($visibility ?? '') === 'public' ? 'selected' : '' ?>>Public (visible in marketplace)</option>
                            <option value="hidden"  <?= ($visibility ?? '') === 'hidden' ? 'selected' : '' ?>>Hidden (only via direct link)</option>
                        </select>
                    </div>
                </div>

                <!-- Descriptions -->
                <div class="bg-[#111111] border border-white/10 rounded-2xl p-4 sm:p-5 space-y-4 text-sm">
                    <h2 class="text-sm font-semibold mb-1">Descriptions</h2>

                    <div>
                        <label class="block text-xs mb-1">Short description</label>
                        <textarea
                            name="short_desc"
                            rows="2"
                            class="w-full bg-[#0B0B0B] border border-white/20 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500"
                            placeholder="One or two lines that summarise the product..."
                        ><?= htmlspecialchars($shortDesc ?? '') ?></textarea>
                    </div>

                    <div>
                        <label class="block text-xs mb-1">Detailed description</label>
                        <textarea
                            name="long_desc"
                            rows="5"
                            class="w-full bg-[#0B0B0B] border border-white/20 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500"
                            placeholder="Materials, sizing, care instructions, what makes it unique..."
                        ><?= htmlspecialchars($longDesc ?? '') ?></textarea>
                        <p class="mt-1 text-[10px] text-gray-500">
                            Use short paragraphs and bullet points for readability.
                        </p>
                    </div>
                </div>

                <!-- Shipping -->
                <div class="bg-[#111111] border border-white/10 rounded-2xl p-4 sm:p-5 space-y-4 text-sm">
                    <h2 class="text-sm font-semibold mb-1">Shipping</h2>

                    <div class="grid sm:grid-cols-3 gap-3">
                        <div class="sm:col-span-2">
                            <label class="block text-xs mb-1">Ships from</label>
                            <input
                                type="text"
                                name="ships_from"
                                value="<?= htmlspecialchars($shipsFrom ?? '') ?>"
                                class="w-full bg-[#0B0B0B] border border-white/20 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500"
                                placeholder="e.g. Lagos, Nigeria"
                            >
                        </div>
                        <div>
                            <label class="block text-xs mb-1">Base shipping fee (₦, optional)</label>
                            <input
                                type="number"
                                name="shipping_fee"
                                value="<?= htmlspecialchars($shippingFee ?? '') ?>"
                                class="w-full bg-[#0B0B0B] border border-white/20 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500"
                                placeholder="e.g. 2500"
                                min="0"
                            >
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs mb-1">Processing time (optional)</label>
                        <input
                            type="text"
                            name="processing_time"
                            value="<?= htmlspecialchars($processing ?? '') ?>"
                            class="w-full sm:w-64 bg-[#0B0B0B] border border-white/20 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500"
                            placeholder="e.g. 2–4 business days"
                        >
                    </div>
                </div>
            </section>

            <!-- RIGHT: Images, variants, actions -->
            <aside class="space-y-5">
                <!-- Images -->
                <div class="bg-[#111111] border border-white/10 rounded-2xl p-4 sm:p-5 text-sm">
                    <h2 class="text-sm font-semibold mb-2">Product images</h2>
                    <p class="text-[11px] text-gray-400 mb-3">
                        Upload at least one clear photo. Add more angles if possible.
                    </p>

                    <!-- Main image preview -->
                    <div class="flex flex-col gap-3">
                        <div id="mainImagePreview"
                             class="w-full aspect-[4/3] rounded-2xl bg-[#0B0B0B] border border-dashed border-white/20 flex items-center justify-center text-[11px] text-gray-400 overflow-hidden">
                            Main image preview
                        </div>
                        <input
                            type="file"
                            id="mainImageInput"
                            name="main_image"
                            accept="image/*"
                            class="block w-full text-xs text-gray-300 file:mr-3 file:px-3 file:py-1.5 file:rounded-full file:border-0 file:text-xs file:font-medium file:bg-orange-500 file:text-black hover:file:bg-orange-400"
                        >
                    </div>

                    <!-- Gallery images -->
                    <div class="mt-4">
                        <label class="block text-xs mb-1">Additional images (optional)</label>
                        <input
                            type="file"
                            id="galleryInput"
                            name="gallery[]"
                            accept="image/*"
                            multiple
                            class="block w-full text-xs text-gray-300 file:mr-3 file:px-3 file:py-1.5 file:rounded-full file:border-0 file:text-xs file:font-medium file:bg-orange-500 file:text-black hover:file:bg-orange-400"
                        >
                        <p class="mt-1 text-[10px] text-gray-500">
                            You can upload up to 8 additional images. Click the × icon to remove.
                        </p>
                        <div id="galleryPreview" class="mt-2 grid grid-cols-4 gap-2"></div>
                    </div>
                </div>

                <!-- Variants (simple free text) -->
                <div class="bg-[#111111] border border-white/10 rounded-2xl p-4 sm:p-5 text-sm">
                    <h2 class="text-sm font-semibold mb-2">Variants (optional)</h2>
                    <p class="text-[11px] text-gray-400 mb-3">
                        Use this field to note sizes, colours, etc. (For now, this is just text; later you can turn this into structured variants.)
                    </p>
                    <textarea
                        name="variants_text"
                        rows="3"
                        class="w-full bg-[#0B0B0B] border border-white/20 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500"
                        placeholder="e.g. Sizes: S, M, L, XL · Colours: Black, White, Orange"
                    ><?= htmlspecialchars($variantsText ?? '') ?></textarea>
                </div>

                <!-- Submit / actions -->
                <div class="bg-[#111111] border border-white/10 rounded-2xl p-4 sm:p-5 text-sm">
                    <h2 class="text-sm font-semibold mb-2">Save product</h2>
                    <p class="text-[11px] text-gray-400 mb-3">
                        You can always edit this product later from your dashboard.
                    </p>

                    <div class="flex flex-col gap-2">
                        <button
                            type="submit"
                            class="w-full px-4 py-2.5 rounded-full text-sm font-semibold"
                            style="background-color: var(--lt-orange);"
                        >
                            Save product
                        </button>
                        <button
                            type="submit"
                            name="save_as_draft"
                            value="1"
                            class="w-full px-4 py-2.5 rounded-full text-xs border border-white/20 bg-[#0B0B0B] hover:border-orange-400"
                        >
                            Save as draft
                        </button>
                    </div>

                    <p class="mt-3 text-[10px] text-gray-500">
                        Products marked as <span class="text-gray-300 font-semibold">Draft</span> will not be visible in the marketplace.
                    </p>
                </div>
            </aside>
        </form>
    </div>
</main>

<script>
// Main image live preview
const mainInput = document.getElementById('mainImageInput');
const mainPreview = document.getElementById('mainImagePreview');

if (mainInput && mainPreview) {
    mainInput.addEventListener('change', function () {
        const file = this.files[0];
        if (!file) return;

        const reader = new FileReader();
        reader.onload = function (e) {
            mainPreview.innerHTML = '';
            mainPreview.style.backgroundImage = `url('${e.target.result}')`;
            mainPreview.style.backgroundSize = 'cover';
            mainPreview.style.backgroundPosition = 'center';
            mainPreview.style.borderStyle = 'solid';
        };
        reader.readAsDataURL(file);
    });
}

// Gallery thumbnails with remove (max 8)
const galleryInput   = document.getElementById('galleryInput');
const galleryPreview = document.getElementById('galleryPreview');
const maxGalleryImages = 8;
let galleryFiles = [];

function syncGalleryInput() {
    // Rebuild FileList using DataTransfer
    const dt = new DataTransfer();
    galleryFiles.forEach(file => dt.items.add(file));
    galleryInput.files = dt.files;
}

function renderGallery() {
    galleryPreview.innerHTML = '';

    galleryFiles.forEach((file, index) => {
        const reader = new FileReader();
        reader.onload = function (e) {
            const wrapper = document.createElement('div');
            wrapper.className = 'relative w-full rounded-xl overflow-hidden bg-[#0B0B0B] border border-white/10';

            const img = document.createElement('img');
            img.src = e.target.result;
            img.alt = 'Additional image preview';
            img.className = 'w-full h-full object-cover';

            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'absolute top-1 right-1 w-5 h-5 rounded-full bg-black/70 text-[10px] flex items-center justify-center border border-white/40 hover:bg-red-600';
            btn.textContent = '×';
            btn.addEventListener('click', () => {
                // Remove this file
                galleryFiles.splice(index, 1);
                syncGalleryInput();
                renderGallery();
            });

            wrapper.appendChild(img);
            wrapper.appendChild(btn);
            galleryPreview.appendChild(wrapper);
        };
        reader.readAsDataURL(file);
    });
}

if (galleryInput && galleryPreview) {
    galleryInput.addEventListener('change', function () {
        const newFiles = Array.from(this.files || []);

        if (!newFiles.length) return;

        newFiles.forEach(file => {
            if (galleryFiles.length < maxGalleryImages) {
                galleryFiles.push(file);
            }
        });

        if (galleryFiles.length >= maxGalleryImages && newFiles.length > 0) {
            // If adding these pushed us to the max, let user know extra are ignored
            if (galleryFiles.length > maxGalleryImages) {
                galleryFiles = galleryFiles.slice(0, maxGalleryImages);
            }
            alert('You can upload a maximum of 8 additional images. Extra files have been ignored.');
        }

        syncGalleryInput();
        renderGallery();
    });
}

// Category select - remove brackets from selected option
const categorySelect = document.getElementById('categorySelect');

if (categorySelect) {
    // Function to update display text
    function updateSelectedDisplay() {
        // Get all options
        const options = categorySelect.options;
        
        // Reset all options to show full text
        for (let i = 0; i < options.length; i++) {
            // Store original full text if not already stored
            if (!options[i].dataset.originalText) {
                options[i].dataset.originalText = options[i].textContent;
            }
            
            // Restore original text for all options
            if (options[i].dataset.originalText) {
                options[i].textContent = options[i].dataset.originalText;
            }
        }
        
        // For the selected option, show only the clean text (value)
        const selectedOption = options[categorySelect.selectedIndex];
        if (selectedOption && selectedOption.value) {
            // Show just the value (clean category name)
            selectedOption.textContent = selectedOption.value;
        }
    }
    
    // Initial update
    updateSelectedDisplay();
    
    // Update on change
    categorySelect.addEventListener('change', updateSelectedDisplay);
    
    // Also update when the select loses focus (for better UX)
    categorySelect.addEventListener('blur', updateSelectedDisplay);
}
</script>

</body>
</html>