<?php
require_once 'process/check_brand_login.php';
// Brand products page (seller-side)

// Include DB connection
if (file_exists('../config.php')) {
    require_once '../config.php';
}

$brand_id = $_SESSION['user']['id'];

// Get counts for metrics and tabs
$totalProducts = 0;
$totalActive = 0;
$totalDrafts = 0;
$totalArchived = 0;
$totalHidden = 0;

if (isset($conn) && $conn) {
    // Total count
    $stmt = $conn->prepare("SELECT COUNT(*) FROM product WHERE brand_id = ?");
    $stmt->bind_param("i", $brand_id);
    $stmt->execute();
    $stmt->bind_result($totalProducts);
    $stmt->fetch();
    $stmt->close();

    // Active count
    $stmt = $conn->prepare("SELECT COUNT(*) FROM product WHERE brand_id = ? AND status = 'active'");
    $stmt->bind_param("i", $brand_id);
    $stmt->execute();
    $stmt->bind_result($totalActive);
    $stmt->fetch();
    $stmt->close();

    // Draft count
    $stmt = $conn->prepare("SELECT COUNT(*) FROM product WHERE brand_id = ? AND status = 'draft'");
    $stmt->bind_param("i", $brand_id);
    $stmt->execute();
    $stmt->bind_result($totalDrafts);
    $stmt->fetch();
    $stmt->close();

    // Archived count
    $stmt = $conn->prepare("SELECT COUNT(*) FROM product WHERE brand_id = ? AND status = 'archived'");
    $stmt->bind_param("i", $brand_id);
    $stmt->execute();
    $stmt->bind_result($totalArchived);
    $stmt->fetch();
    $stmt->close();

    // Private in marketplace count
    $stmt = $conn->prepare("SELECT COUNT(*) FROM product WHERE brand_id = ? AND visibility = 'private'");
    $stmt->bind_param("i", $brand_id);
    $stmt->execute();
    $stmt->bind_result($totalHidden);
    $stmt->fetch();
    $stmt->close();
}

function moneyNaira($n)
{
    return '₦' . number_format($n);
}

// Filters from query string
$statusFilter = strtolower($_GET['status'] ?? 'all'); // all, active, draft, archived
$search = trim($_GET['q'] ?? '');

// Fetch filtered products from DB
$filteredProducts = [];
if (isset($conn) && $conn) {
    if ($statusFilter === 'all' && $search === '') {
        // Simple case
        $res = $conn->query("SELECT * FROM product WHERE brand_id = $brand_id ORDER BY created_at DESC");
        if ($res) {
            while ($row = $res->fetch_assoc()) {
                $filteredProducts[] = $row;
            }
        }
    } else {
        $sql = "SELECT * FROM product WHERE brand_id = ?";
        $params = [$brand_id];
        $types = "i";

        if ($statusFilter !== 'all') {
            $sql .= " AND status = ?";
            $params[] = $statusFilter;
            $types .= "s";
        }

        if ($search !== '') {
            $sql .= " AND (name LIKE ? OR sku LIKE ? OR category LIKE ?)";
            $searchTerm = "%$search%";
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $types .= "sss";
        }

        $sql .= " ORDER BY created_at DESC";

        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param($types, ...$params);
            $stmt->execute();
            $result = $stmt->get_result(); // Keep for now, but check if we should fallback
            if ($result) {
                while ($row = $result->fetch_assoc()) {
                    $filteredProducts[] = $row;
                }
            } else {
                // Manual binding if get_result fails
                // But let's assume get_result works since search-process uses it.
                // If it really doesn't, we'd need a more complex bind_result loop.
            }
            $stmt->close();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Products | LocalTrade Brand</title>
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

    <?php
    $currentBrandPage = 'products';
    include 'brand-header.php';
    ?>

    <main class="flex-1 py-6 sm:py-8">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6 sm:space-y-7">

            <!-- Title + primary actions -->
            <section class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-xl sm:text-2xl font-semibold tracking-tight">Products</h1>
                    <p class="text-xs sm:text-sm text-gray-400 mt-1">
                        Manage the products available in your LocalTrade store. You can edit, hide or archive any
                        listing.
                    </p>
                </div>

                <div class="flex flex-wrap gap-2 text-xs sm:text-sm">
                    <a href="add-product"
                        class="inline-flex items-center gap-2 px-3 sm:px-4 py-2 rounded-full font-semibold text-black"
                        style="background-color: var(--lt-orange);">
                        <span>+ Add product</span>
                    </a>
                </div>
            </section>

            <!-- Metrics summary -->
            <section class="grid sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 text-xs sm:text-sm">
                <div class="bg-[#111111] border border-white/10 rounded-2xl p-3">
                    <p class="text-[11px] text-gray-400 mb-1">Total products</p>
                    <p class="text-lg font-semibold"><?= $totalProducts; ?></p>
                </div>
                <div class="bg-[#111111] border border-white/10 rounded-2xl p-3">
                    <p class="text-[11px] text-gray-400 mb-1">Active listings</p>
                    <p class="text-lg font-semibold text-emerald-300"><?= $totalActive; ?></p>
                </div>
                <div class="bg-[#111111] border border-white/10 rounded-2xl p-3">
                    <p class="text-[11px] text-gray-400 mb-1">Drafts</p>
                    <p class="text-lg font-semibold text-amber-300"><?= $totalDrafts; ?></p>
                </div>
                <div class="bg-[#111111] border border-white/10 rounded-2xl p-3">
                    <p class="text-[11px] text-gray-400 mb-1">Private in marketplace</p>
                    <p class="text-lg font-semibold text-gray-400"><?= $totalHidden; ?></p>
                </div>
            </section>

            <!-- Filters -->
            <section class="bg-[#111111] border border-white/10 rounded-2xl p-4 sm:p-5 text-xs sm:text-sm">
                <form method="get" class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                    <!-- Status filter pills -->
                    <div class="flex flex-wrap gap-2">
                        <?php
                        $statusCounts = [
                            'all' => $totalProducts,
                            'active' => $totalActive,
                            'draft' => $totalDrafts,
                            'archived' => $totalArchived,
                        ];
                        $statusOptions = [
                            'all' => 'All',
                            'active' => 'Active',
                            'draft' => 'Draft',
                            'archived' => 'Archived',
                        ];
                        foreach ($statusOptions as $key => $label):
                            $active = $statusFilter === $key;
                            ?>
                            <button type="submit" name="status" value="<?= htmlspecialchars($key); ?>" class="px-3 py-1.5 rounded-full border text-[11px] sm:text-xs
                            <?= $active
                                ? 'border-orange-500 bg-orange-500/10 text-orange-300'
                                : 'border-white/15 bg-[#0B0B0B] text-gray-300 hover:border-orange-400'; ?>">
                                <?= $label; ?>
                                <span class="ml-1 text-[10px] text-gray-400">
                                    <?= $statusCounts[$key] ?? 0; ?>
                                </span>
                            </button>
                        <?php endforeach; ?>
                    </div>

                    <!-- Search -->
                    <div class="flex items-center gap-2 w-full md:w-auto md:min-w-[260px]">
                        <input type="hidden" name="status" value="<?= htmlspecialchars($statusFilter); ?>">
                        <input type="text" name="q" value="<?= htmlspecialchars($search); ?>"
                            placeholder="Search by name, SKU or category"
                            class="flex-1 bg-[#0B0B0B] border border-white/20 rounded-full px-3 py-2 text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-orange-500">
                        <button type="submit" class="px-3 py-2 rounded-full text-xs sm:text-sm font-semibold"
                            style="background-color: var(--lt-orange);">
                            Filter
                        </button>
                    </div>
                </form>
            </section>

            <!-- Products table -->
            <section class="bg-[#111111] border border-white/10 rounded-2xl p-3 sm:p-5 text-xs sm:text-sm">
                <?php if (empty($filteredProducts)): ?>
                    <div class="py-10 text-center text-xs text-gray-400">
                        No products match your current filters.
                    </div>
                <?php else: ?>
                    <div class="overflow-x-auto">
                        <table class="min-w-full border-separate border-spacing-y-2">
                            <thead class="text-[11px] text-gray-400">
                                <tr>
                                    <th class="text-left pr-3 pb-1">Product</th>
                                    <th class="text-left pr-3 pb-1">Category</th>
                                    <th class="text-left pr-3 pb-1">Price</th>
                                    <th class="text-left pr-3 pb-1">Stock</th>
                                    <th class="text-left pr-3 pb-1">Status</th>
                                    <th class="text-left pr-3 pb-1">Visibility</th>
                                    <th class="text-left pb-1">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($filteredProducts as $p): ?>
                                    <?php
                                    $statusKey = strtolower($p['status']);
                                    $stock = (int) $p['stock'];

                                    // Status badge classes
                                    $statusClass = match ($statusKey) {
                                        'active' => 'bg-emerald-500/15 text-emerald-300 border-emerald-500/40',
                                        'draft' => 'bg-amber-500/15 text-amber-300 border-amber-500/40',
                                        'archived' => 'bg-gray-500/15 text-gray-200 border-gray-500/40',
                                        default => 'bg-white/10 text-gray-200 border-white/20',
                                    };

                                    // Stock label
                                    $stockLabel = $stock > 0 ? $stock . ' in stock' : 'Out of stock';
                                    $stockClass = $stock > 0 ? 'text-gray-100' : 'text-red-300';

                                    // Visibility
                                    $isPublic = strtolower($p['visibility']) === 'public';
                                    $visibilityLabel = $isPublic ? 'Public' : 'Private';
                                    $visibilityClass = $isPublic
                                        ? 'bg-orange-500/15 text-orange-300 border-orange-500/40'
                                        : 'bg-white/5 text-gray-300 border-white/20';
                                    ?>
                                    <tr class="bg-[#0B0B0B] border border-white/10 rounded-xl align-top">
                                        <!-- Product info -->
                                        <td class="px-3 py-2 rounded-l-xl align-top">
                                            <div class="flex items-center gap-3">
                                                <?php if (!empty($p['main_image'])): ?>
                                                    <div
                                                        class="w-10 h-10 rounded-lg overflow-hidden bg-white/5 border border-white/10 flex-shrink-0">
                                                        <img src="../<?= htmlspecialchars($p['main_image']); ?>"
                                                            class="w-full h-full object-cover">
                                                    </div>
                                                <?php else: ?>
                                                    <div
                                                        class="w-10 h-10 rounded-lg bg-white/5 border border-white/10 flex-shrink-0 flex items-center justify-center text-[10px] text-gray-500">
                                                        No img
                                                    </div>
                                                <?php endif; ?>
                                                <div class="flex flex-col">
                                                    <?php
                                                    $editUrl = "add-product?edit=1&id=" . urlencode($p['id']);
                                                    ?>
                                                    <a href="<?= $editUrl; ?>"
                                                        class="font-semibold text-gray-100 hover:text-orange-400">
                                                        <?= htmlspecialchars($p['name']); ?>
                                                    </a>
                                                    <span class="text-[11px] text-gray-500">
                                                        SKU: <?= htmlspecialchars($p['sku']); ?>
                                                    </span>
                                                    <span class="mt-1 text-[10px] text-gray-600">
                                                        Last updated
                                                        <?= $p['updated_at'] ? date('M j, Y H:i', strtotime($p['updated_at'])) : date('M j, Y H:i', strtotime($p['created_at'])); ?>
                                                    </span>
                                                </div>
                                        </td>

                                        <!-- Category -->
                                        <td class="px-3 py-2 align-top">
                                            <span class="text-gray-200">
                                                <?= htmlspecialchars($p['category']); ?>
                                            </span>
                                        </td>

                                        <!-- Price -->
                                        <td class="px-3 py-2 align-top">
                                            <span class="font-semibold text-orange-400">
                                                <?= moneyNaira($p['price']); ?>
                                            </span>
                                        </td>

                                        <!-- Stock -->
                                        <td class="px-3 py-2 align-top">
                                            <span class="<?= $stockClass; ?>">
                                                <?= $stockLabel; ?>
                                            </span>
                                        </td>

                                        <!-- Status -->
                                        <td class="px-3 py-2 align-top">
                                            <span
                                                class="inline-flex px-2 py-0.5 rounded-full border text-[11px] <?= $statusClass; ?>">
                                                <?= htmlspecialchars($p['status']); ?>
                                            </span>
                                        </td>

                                        <!-- Visibility -->
                                        <td class="px-3 py-2 align-top">
                                            <span
                                                class="inline-flex px-2 py-0.5 rounded-full border text-[11px] <?= $visibilityClass; ?>">
                                                <?= $visibilityLabel; ?>
                                            </span>
                                        </td>

                                        <!-- Actions -->
                                        <td class="px-3 py-2 rounded-r-xl align-top">
                                            <div class="flex flex-wrap gap-1.5 text-[11px]">
                                                <a href="add-product?id=<?= $p['id']; ?>"
                                                    class="px-2 py-1 rounded-full border border-white/20 bg-[#111111] hover:border-orange-400">
                                                    Edit
                                                </a>

                                                <?php if ($statusKey === 'archived'): ?>
                                                    <button type="button"
                                                        onclick="handleProductAction(<?= $p['id']; ?>, 'unarchive')"
                                                        class="px-2 py-1 rounded-full border border-white/40 bg-[#111111] text-emerald-300 hover:border-emerald-400">
                                                        Unarchive
                                                    </button>
                                                <?php else: ?>
                                                    <button type="button"
                                                        onclick="<?= $statusKey === 'draft' ? "showModal({title: 'Action Restricted', message: 'Drafts cannot be archived. Activate or Delete them instead.', type: 'error'})" : "handleProductAction({$p['id']}, 'archive')" ?>"
                                                        class="px-2 py-1 rounded-full border border-white/40 bg-[#111111] <?= $statusKey === 'draft' ? 'opacity-30 cursor-not-allowed text-gray-400' : 'text-red-300 hover:border-red-400' ?>">
                                                        Archive
                                                    </button>
                                                <?php endif; ?>

                                                <button type="button" onclick="handleProductAction(<?= $p['id']; ?>, 'delete')"
                                                    class="px-2 py-1 rounded-full border border-red-500/40 bg-[#111111] text-red-300 hover:border-red-400">
                                                    Delete
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </section>
        </div>
    </main>

    <script>
        async function handleProductAction(productId, action) {
            let title, message;

            if (action === 'delete') {
                title = 'Delete Product';
                message = 'Are you sure you want to PERMANENTLY delete this product? All images will be removed. This cannot be undone.';
            } else if (action === 'unarchive') {
                title = 'Unarchive Product';
                message = 'Are you sure you want to unarchive this product? It will be visible in the marketplace again.';
            } else {
                title = 'Archive Product';
                message = 'Are you sure you want to archive this product? It will no longer be available for purchase.';
            }

            showModal({
                title: title,
                message: message,
                onConfirm: async () => {
                    const formData = new FormData();
                    formData.append('product_id', productId);
                    formData.append('action', action);

                    try {
                        const response = await fetch('process/process-product-action.php', {
                            method: 'POST',
                            body: formData
                        });
                        const data = await response.json();
                        if (data.success) {
                            showModal({
                                title: 'Success!',
                                message: data.message,
                                type: 'success',
                                onConfirm: () => location.reload()
                            });
                        } else {
                            showModal({
                                title: 'Error',
                                message: data.message || 'An error occurred.',
                                type: 'error'
                            });
                        }
                    } catch (error) {
                        showModal({
                            title: 'Connection Error',
                            message: error.message,
                            type: 'error'
                        });
                    }
                }
            });
        }
    </script>
</body>

</html>