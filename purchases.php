<?php
$currentPage = 'purchases';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Redirect guests to login
if (empty($_SESSION['user'])) {
    header('Location: login');
    exit;
}

$user = $_SESSION['user'];

// Include config if valid
if (file_exists('config.php')) {
    require_once 'config.php';
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Purchase History | LocalTrade</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        :root{--lt-orange:#F36A1D;--lt-black:#0D0D0D}
    </style>
</head>

<body class="bg-[#0D0D0D] text-white min-h-screen flex flex-col">
    <div class="flex-1">
        <?php include 'header.php'; ?>

        <main id="main" class="max-w-4xl mx-auto px-4 py-12" role="main" aria-label="Purchase History">
            <div class="mb-8 flex items-center justify-between">
                <h1 class="text-2xl font-semibold">Purchase History</h1>
                <a href="account" class="text-sm text-gray-400 hover:text-white transition-colors">&larr; Back to Account</a>
            </div>

            <div class="bg-[#111111] border border-white/10 rounded-2xl px-6 py-8 shadow-xl shadow-black/40">
                <?php
                $purchasedProducts = [];
                if (isset($conn) && $conn instanceof mysqli && isset($user['id'])) {
                    $pStmt = $conn->prepare("
                        SELECT p.id, p.name, p.slug, p.main_image, oi.unit_price, o.created_at, o.order_number
                        FROM `order` o
                        JOIN `orderitem` oi ON o.id = oi.order_id
                        JOIN `product` p ON oi.product_id = p.id
                        WHERE o.buyer_id = ?
                        ORDER BY o.created_at DESC
                    ");
                    if ($pStmt) {
                        $pStmt->bind_param("i", $user['id']);
                        $pStmt->execute();
                        $pRes = $pStmt->get_result();
                        while ($pRow = $pRes->fetch_assoc()) {
                            $purchasedProducts[] = $pRow;
                        }
                        $pStmt->close();
                    }
                }
                ?>

                <?php if (!empty($purchasedProducts)): ?>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        <?php foreach ($purchasedProducts as $product): ?>
                            <a href="product?slug=<?= htmlspecialchars($product['slug']) ?>" 
                                class="flex flex-col gap-3 p-4 rounded-xl bg-white/5 hover:bg-white/10 border border-white/5 transition group">
                                <div class="w-full aspect-square rounded-lg bg-[#0B0B0B] border border-white/10 overflow-hidden relative">
                                    <?php if (!empty($product['main_image'])): ?>
                                        <img src="<?= htmlspecialchars($product['main_image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" class="w-full h-full object-cover">
                                    <?php else: ?>
                                        <div class="w-full h-full flex items-center justify-center text-gray-600 text-xs">No img</div>
                                    <?php endif; ?>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-white truncate group-hover:text-orange-400 transition-colors">
                                        <?= htmlspecialchars($product['name']) ?>
                                    </p>
                                    <p class="text-xs text-gray-500 mt-1">
                                        Purchased on <?= date('M d, Y', strtotime($product['created_at'])) ?>
                                    </p>
                                    <div class="flex items-center justify-between mt-2">
                                        <p class="text-sm font-semibold text-orange-500">
                                            ₦<?= number_format($product['unit_price'], 2) ?>
                                        </p>
                                        <span class="text-[10px] uppercase tracking-wider text-gray-600 bg-black/40 px-2 py-1 rounded">
                                            #<?= htmlspecialchars(substr($product['order_number'] ?? '---', 0, 8)) ?>
                                        </span>
                                    </div>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="text-center py-12">
                        <div class="w-16 h-16 bg-white/5 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                            </svg>
                        </div>
                        <p class="text-gray-400 font-medium">No purchase history found</p>
                        <p class="text-gray-600 text-sm mt-1">Items you buy will appear here.</p>
                        <a href="marketplace" class="inline-block mt-6 px-6 py-2 rounded-full border border-orange-500/30 text-orange-400 text-sm hover:bg-orange-500 hover:text-white transition-all">Start shopping</a>
                    </div>
                <?php endif; ?>
            </div>
        </main>
    </div>

    <footer class="py-6 text-center text-xs text-gray-500">
        &copy; <?= date('Y') ?> LocalTrade
    </footer>
</body>
</html>
