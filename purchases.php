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
    <style>
        :root {
            --lt-forest: #1E3932;
            --lt-orange: #F36A1D;
            --lt-parchment: #FCFBF7;
            --lt-ink: #1A1A1A;
            --lt-cream: #F3F0E6;
        }
    </style>
</head>

<body class="bg-brand-parchment text-brand-ink min-h-screen flex flex-col font-sans">
    <div class="flex-1">
        <?php include 'header.php'; ?>

        <main id="main" class="max-w-4xl mx-auto px-4 py-12" role="main" aria-label="Purchase History">
            <div class="mb-8 flex items-center justify-between">
                <h1 class="text-2xl font-semibold text-brand-forest">Purchase History</h1>
                <a href="account.php" class="text-sm text-brand-ink/40 hover:text-brand-orange transition-colors">&larr;
                    Back to Account</a>
            </div>

            <div class="bg-green-50 border border-brand-forest/5 rounded-2xl px-6 py-8 shadow-sm">
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
                            <a href="product.php?slug=<?= htmlspecialchars($product['slug']) ?>"
                                class="flex flex-col gap-3 p-4 rounded-xl bg-green-50 hover:bg-brand-parchment/50 border border-brand-forest/5 transition group">
                                <div
                                    class="w-full aspect-square rounded-lg bg-brand-parchment border border-brand-forest/5 overflow-hidden relative">
                                    <?php if (!empty($product['main_image'])): ?>
                                        <img src="<?= htmlspecialchars($product['main_image']) ?>"
                                            alt="<?= htmlspecialchars($product['name']) ?>" class="w-full h-full object-cover">
                                    <?php else: ?>
                                        <div class="w-full h-full flex items-center justify-center text-brand-forest/20 text-xs">No
                                            img</div>
                                    <?php endif; ?>
                                </div>
                                <div>
                                    <p
                                        class="text-sm font-medium text-brand-forest truncate group-hover:text-brand-orange transition-colors">
                                        <?= htmlspecialchars($product['name']) ?>
                                    </p>
                                    <p class="text-xs text-brand-ink/40 mt-1">
                                        Purchased on <?= date('M d, Y', strtotime($product['created_at'])) ?>
                                    </p>
                                    <div class="flex items-center justify-between mt-2">
                                        <p class="text-sm font-semibold text-brand-orange">
                                            ₦<?= number_format($product['unit_price'], 2) ?>
                                        </p>
                                        <span
                                            class="text-[10px] uppercase tracking-wider text-brand-ink/30 bg-brand-forest/5 px-2 py-1 rounded">
                                            #<?= htmlspecialchars(substr($product['order_number'] ?? '---', 0, 8)) ?>
                                        </span>
                                    </div>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="text-center py-12">
                        <div class="w-16 h-16 bg-brand-forest/5 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-brand-forest/30" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                            </svg>
                        </div>
                        <p class="text-brand-ink/50 font-medium">No purchase history found</p>
                        <p class="text-brand-ink/30 text-sm mt-1">Items you buy will appear here.</p>
                        <a href="marketplace.php"
                            class="inline-block mt-6 px-6 py-2 rounded-full border border-brand-orange/30 text-brand-orange text-sm hover:bg-brand-orange hover:text-white transition-all">Start
                            shopping</a>
                    </div>
                <?php endif; ?>
            </div>
        </main>
    </div>

    <!-- FOOTER -->
    <footer class="border-t border-brand-forest/10 bg-brand-cream/30 mt-12 py-8">
        <div
            class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 text-xs text-brand-ink/50 flex flex-col sm:flex-row gap-4 sm:items-center sm:justify-between">
            <p>© <span id="year"></span> LocalTrade. All rights reserved.</p>
            <div class="flex gap-6 font-medium">
                <a href="#" class="hover:text-brand-orange transition-colors">Privacy</a>
                <a href="#" class="hover:text-brand-orange transition-colors">Terms</a>
                <a href="#" class="hover:text-brand-orange transition-colors">Support</a>
            </div>
        </div>
    </footer>

    <script>
        document.getElementById('year').textContent = new Date().getFullYear();
    </script>
</body>

</html>