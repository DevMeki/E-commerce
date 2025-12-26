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

// Fetch followed brands early to avoid undefined variable errors
$followedBrands = [];
if (file_exists('config.php')) {
    require_once 'config.php';
}
if (isset($conn) && $conn instanceof mysqli && isset($user['id'])) {
    $fStmt = $conn->prepare("
        SELECT b.id, b.brand_name, b.slug, b.category, b.logo 
        FROM Brand b
        JOIN BrandFollower bf ON b.id = bf.brand_id
        WHERE bf.buyer_id = ?
        ORDER BY bf.followed_at DESC
    ");
    $fStmt->bind_param("i", $user['id']);
    $fStmt->execute();
    $fRes = $fStmt->get_result();
    while ($frow = $fRes->fetch_assoc()) {
        $followedBrands[] = $frow;
    }
    $fStmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Your account | LocalTrade</title>
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

        <main id="main" class="max-w-4xl mx-auto px-4 py-12" role="main" aria-labelledby="accountHeading">
            <div class="bg-green-50 border border-brand-forest/5 rounded-3xl px-6 py-8 shadow-sm max-w-2xl mx-auto">
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <h1 id="accountHeading" class="text-2xl font-bold text-brand-forest">Account Settings</h1>
                        <span class="block h-1 w-10 bg-brand-orange mt-2 rounded-full"></span>
                    </div>
                    <a href="purchases.php"
                        class="text-xs font-bold text-brand-orange hover:underline uppercase tracking-wider flex items-center gap-2 px-4 py-2 bg-brand-parchment rounded-full border border-brand-forest/5">
                        <span>Order History</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </a>
                </div>

                <div id="updateMessages"></div>

                <form id="accountForm" enctype="multipart/form-data" class="space-y-6">
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-8 items-start">
                        <div class="sm:col-span-1">
                            <label
                                class="block text-[11px] font-bold text-brand-ink/40 uppercase tracking-widest mb-3">Profile
                                Photo</label>
                            <div
                                class="w-24 h-24 rounded-3xl bg-brand-parchment border border-brand-forest/5 flex items-center justify-center overflow-hidden mb-4 shadow-inner">
                                <img id="avatarPreview" src="<?= htmlspecialchars($user['avatar'] ?? '') ?>"
                                    alt="User avatar" class="w-full h-full object-cover"
                                    style="display: <?= !empty($user['avatar']) ? 'block' : 'none' ?>" />
                                <svg id="avatarPlaceholder" xmlns="http://www.w3.org/2000/svg"
                                    class="w-10 h-10 text-brand-forest/20" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" aria-hidden="true"
                                    style="display: <?= empty($user['avatar']) ? 'block' : 'none' ?>">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M5.121 17.804A13.937 13.937 0 0112 15c2.5 0 4.847.607 6.879 1.804M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </div>
                            <input type="file" id="avatar" name="avatar" accept="image/*"
                                class="w-full text-[10px] text-brand-ink/40 file:mr-4 file:py-1.5 file:px-3 file:rounded-full file:border-0 file:text-[10px] file:font-bold file:bg-brand-forest/5 file:text-brand-forest hover:file:bg-brand-forest/10 cursor-pointer">
                        </div>

                        <div class="sm:col-span-2 space-y-5">
                            <div>
                                <label for="fullname"
                                    class="block text-[11px] font-bold text-brand-ink/40 uppercase tracking-widest mb-2">Full
                                    name</label>
                                <input type="text" id="fullname" name="fullname"
                                    value="<?= htmlspecialchars($user['fullname'] ?? '') ?>"
                                    class="w-full rounded-2xl bg-brand-parchment border border-brand-forest/10 px-4 py-3 text-sm text-brand-forest font-medium placeholder-brand-ink/30 focus:outline-none focus:ring-2 focus:ring-brand-orange/20 focus:border-brand-orange transition-all">
                            </div>
                            <div>
                                <label for="email"
                                    class="block text-[11px] font-bold text-brand-ink/40 uppercase tracking-widest mb-2">Email
                                    Address</label>
                                <input type="email" id="email" name="email"
                                    value="<?= htmlspecialchars($user['email']) ?>" readonly
                                    class="w-full rounded-2xl bg-brand-parchment/50 border border-brand-forest/5 px-4 py-3 text-sm text-brand-ink/30 cursor-not-allowed">
                            </div>
                            <?php if (($user['type'] ?? 'buyer') === 'buyer'): ?>
                                <div>
                                    <label for="phone"
                                        class="block text-[11px] font-bold text-brand-ink/40 uppercase tracking-widest mb-2">Phone
                                        Number</label>
                                    <input type="tel" id="phone" name="phone"
                                        value="<?= htmlspecialchars($user['phone'] ?? '') ?>"
                                        class="w-full rounded-2xl bg-brand-parchment border border-brand-forest/10 px-4 py-3 text-sm text-brand-forest font-medium placeholder-brand-ink/30 focus:outline-none focus:ring-2 focus:ring-brand-orange/20 focus:border-brand-orange transition-all"
                                        placeholder="e.g. 08012345678">
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="pt-8 border-t border-brand-forest/5 flex flex-wrap gap-4">
                        <button type="submit"
                            class="px-8 py-3 rounded-full text-sm font-bold bg-brand-orange text-white hover:scale-[1.02] active:scale-[0.98] transition-all shadow-lg shadow-brand-orange/20">
                            Save Changes
                        </button>
                        <form action="process/logout.php" method="post" class="inline-block" aria-label="Logout form">
                            <button type="submit"
                                class="inline-flex items-center gap-2 px-8 py-3 rounded-full text-sm font-bold border border-red-100 text-red-500 hover:bg-red-50 transition-all"
                                aria-describedby="logoutDesc">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a2 2 0 01-2 2H6a2 2 0 01-2-2V7a2 2 0 012-2h5a2 2 0 012 2v1" />
                                </svg>
                                <span>Sign Out</span>
                            </button>
                            <p id="logoutDesc" class="sr-only">This will sign you out of your account and return you to
                                the homepage.</p>
                        </form>
                    </div>
                </form>
            </div>

            <!-- Followed Brands Section -->
            <div
                class="mt-8 bg-green-50 border border-brand-forest/5 rounded-3xl px-6 py-8 shadow-sm max-w-2xl mx-auto">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-lg font-bold text-brand-forest">Brands you follow</h2>
                    <span
                        class="text-[10px] font-bold text-brand-ink/40 uppercase tracking-widest bg-brand-forest/5 px-3 py-1 rounded-full">
                        <?php echo count($followedBrands); ?> saved
                    </span>
                </div>

                <?php
                // Followed brands logic moved to top of file
                ?>

                <?php if (!empty($followedBrands)): ?>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <?php foreach ($followedBrands as $fb): ?>
                            <a href="store.php?slug=<?= htmlspecialchars($fb['slug']) ?>"
                                class="flex items-center gap-4 p-4 rounded-2xl bg-brand-parchment hover:bg-white border border-brand-forest/30 hover:border-brand-orange/30 hover:shadow-lg transition-all group">
                                <div
                                    class="w-12 h-12 rounded-2xl bg-white flex items-center justify-center text-brand-forest font-bold border border-brand-forest/5 overflow-hidden shadow-sm group-hover:scale-105 transition-transform">
                                    <?php if (!empty($fb['logo'])): ?>
                                        <img src="<?= htmlspecialchars($fb['logo']) ?>"
                                            alt="<?= htmlspecialchars($fb['brand_name']) ?>" class="w-full h-full object-cover">
                                    <?php else: ?>
                                        <span class="text-lg"><?= strtoupper(substr($fb['brand_name'], 0, 1)) ?></span>
                                    <?php endif; ?>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p
                                        class="text-sm font-bold text-brand-forest truncate group-hover:text-brand-orange transition-colors">
                                        <?= htmlspecialchars($fb['brand_name']) ?>
                                    </p>
                                    <p class="text-[11px] text-brand-ink/40 truncate">
                                        <?= htmlspecialchars($fb['category']) ?>
                                    </p>
                                </div>
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="w-4 h-4 text-brand-forest/20 group-hover:text-brand-orange transition-colors"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                                </svg>
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div
                        class="text-center py-10 bg-brand-parchment/30 rounded-2xl border border-dashed border-brand-forest/10">
                        <p class="text-brand-ink/40 text-sm italic">You are not following any brands yet.</p>
                        <a href="marketplace.php"
                            class="text-xs font-bold text-brand-orange hover:underline mt-4 inline-block uppercase tracking-wider">Explore
                            marketplace</a>
                    </div>
                <?php endif; ?>
            </div>


        </main>
    </div>

    <!-- FOOTER -->
    <footer class="border-t border-brand-forest/10 bg-brand-cream/30 mt-12 py-8">
        <div
            class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 text-xs text-brand-ink/50 flex flex-col sm:flex-row gap-4 sm:items-center sm:justify-between">
            <p>© <span id="year_footer"></span> LocalTrade. All rights reserved.</p>
            <div class="flex gap-6 font-medium">
                <a href="#" class="hover:text-brand-orange transition-colors">Privacy</a>
                <a href="#" class="hover:text-brand-orange transition-colors">Terms</a>
                <a href="#" class="hover:text-brand-orange transition-colors">Support</a>
            </div>
        </div>
    </footer>

    <script>
        document.getElementById('year_footer').textContent = new Date().getFullYear();
    </script>

    <script>
        const accountForm = document.getElementById('accountForm');
        const updateMessages = document.getElementById('updateMessages');
        const avatarInput = document.getElementById('avatar');
        const avatarPreview = document.getElementById('avatarPreview');
        const avatarPlaceholder = document.getElementById('avatarPlaceholder');

        // Avatar preview
        avatarInput.addEventListener('change', (e) => {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    avatarPreview.src = e.target.result;
                    avatarPreview.style.display = 'block';
                    avatarPlaceholder.style.display = 'none';
                };
                reader.readAsDataURL(file);
            }
        });

        // Form submission
        accountForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            updateMessages.innerHTML = '';
            const btn = accountForm.querySelector('button[type="submit"]');
            btn.disabled = true;
            btn.textContent = 'Updating...';

            const fd = new FormData(accountForm);
            try {
                const res = await fetch('process/process-update-account.php', { method: 'POST', body: fd, credentials: 'same-origin' });
                const responseText = await res.text();
                console.log('Response text:', responseText);
                let json;
                try {
                    json = JSON.parse(responseText);
                } catch (parseErr) {
                    updateMessages.innerHTML = `
                        <div class="mb-4 rounded-xl border border-red-500/40 bg-red-500/10 px-3 py-2 text-xs text-red-200">
                            JSON parse error: ${parseErr.message}. Response: ${responseText.substring(0, 200)}
                        </div>`;
                    return;
                }
                if (json.success) {
                    updateMessages.innerHTML = `
                        <div class="mb-6 bg-brand-forest text-white px-4 py-3 rounded-2xl text-sm font-bold shadow-lg shadow-brand-forest/10 flex items-center gap-3">
                            <span class="text-lg">✓</span>
                            <span>${json.message || 'Account updated successfully.'}</span>
                        </div>`;
                    // Update session-like display if needed, but since page reloads session, maybe reload
                    setTimeout(() => location.reload(), 1500);
                } else {
                    const errs = json.errors || ['Update failed.'];
                    updateMessages.innerHTML = `
                        <div class="mb-6 rounded-2xl border border-red-500/20 bg-red-50 px-4 py-3 text-sm text-red-600 font-medium">
                            <p class="font-bold mb-1">Please fix the following:</p>
                            <ul class="list-disc list-inside space-y-1">${errs.map(e => `<li>${e}</li>`).join('')}</ul>
                        </div>`;
                }
            } catch (err) {
                updateMessages.innerHTML = `
                    <div class="mb-4 rounded-xl border border-red-500/40 bg-red-500/10 px-3 py-2 text-xs text-red-200">
                        Network or server error: ${err.message}. Please try again.
                    </div>`;
            } finally {
                btn.disabled = false;
                btn.textContent = 'Update Account';
            }
        });
    </script>
</body>

</html>