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

                <div id="updateMessages"></div>

                <form id="accountForm" enctype="multipart/form-data" class="space-y-6">
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 items-start">
                        <div class="sm:col-span-1">
                            <label class="block text-xs text-gray-400 mb-2">Avatar</label>
                            <div class="w-20 h-20 rounded-full bg-[#0B0B0B] border border-white/10 flex items-center justify-center overflow-hidden mb-2">
                                <img id="avatarPreview" src="<?= htmlspecialchars($user['avatar'] ?? '') ?>" alt="User avatar" class="w-full h-full object-cover" style="display: <?= !empty($user['avatar']) ? 'block' : 'none' ?>" />
                                <svg id="avatarPlaceholder" xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true" style="display: <?= empty($user['avatar']) ? 'block' : 'none' ?>">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5.121 17.804A13.937 13.937 0 0112 15c2.5 0 4.847.607 6.879 1.804M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </div>
                            <input type="file" id="avatar" name="avatar" accept="image/*" class="w-full text-xs text-gray-300 file:mr-2 file:py-1 file:px-2 file:rounded file:border-0 file:text-xs file:font-semibold file:bg-orange-500 file:text-white hover:file:bg-orange-600">
                        </div>

                        <div class="sm:col-span-2 space-y-4">
                            <div>
                                <label for="fullname" class="block text-xs text-gray-400 mb-1">Full name</label>
                                <input type="text" id="fullname" name="fullname" value="<?= htmlspecialchars($user['fullname'] ?? '') ?>" class="w-full rounded-xl bg-[#0B0B0B] border border-white/15 px-3 py-2 text-sm text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                            </div>
                            <div>
                                <label for="email" class="block text-xs text-gray-400 mb-1">Email</label>
                                <input type="email" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" readonly class="w-full rounded-xl bg-[#0B0B0B] border border-white/15 px-3 py-2 text-sm text-gray-500 cursor-not-allowed">
                            </div>
                            <?php if (($user['type'] ?? 'buyer') === 'buyer'): ?>
                            <div>
                                <label for="phone" class="block text-xs text-gray-400 mb-1">Phone</label>
                                <input type="tel" id="phone" name="phone" value="<?= htmlspecialchars($user['phone'] ?? '') ?>" class="w-full rounded-xl bg-[#0B0B0B] border border-white/15 px-3 py-2 text-sm text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500" placeholder="Your phone number">
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-white/10 flex gap-3">
                        <button type="submit" class="px-4 py-2 rounded-full text-sm font-semibold bg-orange-500 text-white hover:bg-orange-600 focus:outline-none focus:ring-2 focus:ring-orange-400">
                            Update Account
                        </button>
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
                </form>
            </div>
        </main>
    </div>

    <footer class="py-6 text-center text-xs text-gray-500">
        &copy; <?= date('Y') ?> LocalTrade
    </footer>

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
                        <div class="mb-4 bg-green-500/10 border border-green-500/30 text-green-200 px-3 py-2 rounded-xl text-sm">
                            ${json.message || 'Account updated successfully.'}
                        </div>`;
                    // Update session-like display if needed, but since page reloads session, maybe reload
                    setTimeout(() => location.reload(), 1500);
                } else {
                    const errs = json.errors || ['Update failed.'];
                    updateMessages.innerHTML = `
                        <div class="mb-4 rounded-xl border border-red-500/40 bg-red-500/10 px-3 py-2 text-xs text-red-200">
                            <ul class="list-disc list-inside">${errs.map(e => `<li>${e}</li>`).join('')}</ul>
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
