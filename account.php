<?php
$currentPage = 'account';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Redirect guests to login
if (empty($_SESSION['user'])) {
    header('Location: login');
    exit;
}

$user = $_SESSION['user'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Your account | LocalTrade</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        :root{--lt-orange:#F36A1D;--lt-black:#0D0D0D}
    </style>
</head>

<body class="bg-[#0D0D0D] text-white min-h-screen flex flex-col">
    <div class="flex-1">
        <?php include 'header.php'; ?>

        <main id="main" class="max-w-4xl mx-auto px-4 py-12" role="main" aria-labelledby="accountHeading">
            <div class="bg-[#111111] border border-white/10 rounded-2xl px-6 py-8 shadow-xl shadow-black/40 max-w-2xl mx-auto">
                <h1 id="accountHeading" class="text-xl font-semibold mb-4">Account</h1>

                <section aria-label="Account details" class="space-y-6">
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 items-center">
                        <div class="sm:col-span-1">
                            <div class="w-20 h-20 rounded-full bg-[#0B0B0B] border border-white/10 flex items-center justify-center overflow-hidden">
                                <?php if (!empty($user['avatar'])): ?>
                                    <img src="<?= htmlspecialchars($user['avatar']) ?>" alt="<?= htmlspecialchars($user['name'] ?? 'User avatar') ?>" class="w-full h-full object-cover" />
                                <?php else: ?>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5.121 17.804A13.937 13.937 0 0112 15c2.5 0 4.847.607 6.879 1.804M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="sm:col-span-2">
                            <p class="text-xs text-gray-400">Full name</p>
                            <p class="font-medium text-lg"><?= htmlspecialchars($user['name'] ?? $user['email']) ?></p>
                            <p class="text-xs text-gray-400 mt-2">Email</p>
                            <p class="font-medium"><?= htmlspecialchars($user['email']) ?></p>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-white/10">
                        <form action="process/logout" method="post" class="inline-block" aria-label="Logout form">
                            <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 rounded-full text-sm font-semibold bg-red-600 text-white hover:bg-red-500 focus:outline-none focus:ring-2 focus:ring-red-400" aria-describedby="logoutDesc">
                                <!-- logout icon -->
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a2 2 0 01-2 2H6a2 2 0 01-2-2V7a2 2 0 012-2h5a2 2 0 012 2v1" />
                                </svg>
                                <span>Logout</span>
                            </button>
                            <p id="logoutDesc" class="sr-only">This will sign you out of your account and return you to the homepage.</p>
                        </form>
                    </div>
                </section>
            </div>
        </main>
    </div>

    <footer class="py-6 text-center text-xs text-gray-500">
        &copy; <?= date('Y') ?> LocalTrade
    </footer>
</body>

</html>
