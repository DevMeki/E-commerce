<?php
// Brand products page (seller-side)

// Mock data for now – replace with DB query later
$products = [
    [
        'id' => 1,
        'name' => 'Ankara Panel Hoodie',
        'sku' => 'ANK-HOOD-001',
        'category' => 'Fashion',
        'price' => 18500,
        'stock' => 12,
        'status' => 'Active',     // Active, Draft, Archived
        'visibility' => 'Public',     // Public, Hidden
        'updated_at' => '2025-02-10 09:21',
    ],
    [
        'id' => 2,
        'name' => 'Naija Drip Tee',
        'sku' => 'TEE-NG-002',
        'category' => 'Fashion',
        'price' => 7500,
        'stock' => 0,
        'status' => 'Active',
        'visibility' => 'Public',
        'updated_at' => '2025-02-09 14:05',
    ],
    [
        'id' => 3,
        'name' => 'Shea Butter Glow Oil',
        'sku' => 'BEAUTY-OIL-01',
        'category' => 'Beauty',
        'price' => 6200,
        'stock' => 34,
        'status' => 'Draft',
        'visibility' => 'Hidden',
        'updated_at' => '2025-02-08 17:45',
    ],
    [
        'id' => 4,
        'name' => 'Handmade Clay Mug',
        'sku' => 'HOME-MUG-01',
        'category' => 'Home & Living',
        'price' => 4500,
        'stock' => 5,
        'status' => 'Active',
        'visibility' => 'Public',
        'updated_at' => '2025-02-07 10:12',
    ],
    [
        'id' => 5,
        'name' => 'Limited Edition Hoodie',
        'sku' => 'ANK-HOOD-999',
        'category' => 'Fashion',
        'price' => 25000,
        'stock' => 0,
        'status' => 'Archived',
        'visibility' => 'Hidden',
        'updated_at' => '2025-01-12 09:12',
    ],
];

function moneyNaira($n)
{
    return '₦' . number_format($n);
}

// Filters from query string
$statusFilter = strtolower($_GET['status'] ?? 'all'); // all, active, draft, archived
$search = trim($_GET['q'] ?? '');

// Filter products
$filteredProducts = array_filter($products, function ($p) use ($statusFilter, $search) {
    if ($statusFilter !== 'all') {
        if (strtolower($p['status']) !== $statusFilter) {
            return false;
        }
    }

    if ($search !== '') {
        $haystack = strtolower($p['name'] . ' ' . $p['sku'] . ' ' . $p['category']);
        if (strpos($haystack, strtolower($search)) === false) {
            return false;
        }
    }

    return true;
});

// Simple metrics
$totalProducts = count($products);
$totalActive = count(array_filter($products, fn($p) => strtolower($p['status']) === 'active'));
$totalDrafts = count(array_filter($products, fn($p) => strtolower($p['status']) === 'draft'));
$totalArchived = count(array_filter($products, fn($p) => strtolower($p['status']) === 'archived'));
$totalVisible = count(array_filter($products, fn($p) => strtolower($p['visibility']) === 'public'));
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
                    <a href="add-product.php"
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
                    <p class="text-[11px] text-gray-400 mb-1">Visible in marketplace</p>
                    <p class="text-lg font-semibold text-orange-300"><?= $totalVisible; ?></p>
                </div>
            </section>

            <!-- Filters -->
            <section class="bg-[#111111] border border-white/10 rounded-2xl p-4 sm:p-5 text-xs sm:text-sm">
                <form method="get" class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                    <!-- Status filter pills -->
                    <div class="flex flex-wrap gap-2">
                        <?php
                        $statusCounts = [
                            'all' => count($products),
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
                                    $visibilityClass = $isPublic
                                        ? 'bg-orange-500/15 text-orange-300 border-orange-500/40'
                                        : 'bg-white/5 text-gray-300 border-white/20';
                                    ?>
                                    <tr class="bg-[#0B0B0B] border border-white/10 rounded-xl align-top">
                                        <!-- Product info -->
                                        <td class="px-3 py-2 rounded-l-xl align-top">
                                            <div class="flex flex-col">
                                                <a href="edit-product.php?id=<?= urlencode($p['id']); ?>"
                                                    class="font-semibold text-gray-100 hover:text-orange-400">
                                                    <?= htmlspecialchars($p['name']); ?>
                                                </a>
                                                <span class="text-[11px] text-gray-500">
                                                    SKU: <?= htmlspecialchars($p['sku']); ?>
                                                </span>
                                                <span class="mt-1 text-[10px] text-gray-600">
                                                    Last updated <?= htmlspecialchars($p['updated_at']); ?>
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
                                                <?= htmlspecialchars($p['visibility']); ?>
                                            </span>
                                        </td>

                                        <!-- Actions -->
                                        <td class="px-3 py-2 rounded-r-xl align-top">
                                            <div class="flex flex-wrap gap-1.5 text-[11px]">
                                                <a href="edit-product.php?id=<?= urlencode($p['id']); ?>"
                                                    class="px-2 py-1 rounded-full border border-white/20 bg-[#111111] hover:border-orange-400">
                                                    Edit
                                                </a>
                                                <button type="button"
                                                    class="px-2 py-1 rounded-full border border-white/15 bg-[#111111] hover:border-emerald-400">
                                                    Duplicate
                                                </button>
                                                <button type="button"
                                                    class="px-2 py-1 rounded-full border border-red-500/40 bg-[#111111] text-red-300 hover:border-red-400">
                                                    Archive
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

</body>

</html>