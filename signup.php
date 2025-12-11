<?php
// --- FORM PROCESSING (placeholder logic) ---
$errors = [];
$successMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accountType = $_POST['account_type'] ?? 'buyer';
    $fullName = trim($_POST['full_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $confirm = trim($_POST['confirm_password'] ?? '');

    // Brand fields
    $brandName = trim($_POST['brand_name'] ?? '');
    $brandSlug = trim($_POST['brand_slug'] ?? '');
    $brandCategory = trim($_POST['brand_category'] ?? '');
    $brandLocation = trim($_POST['brand_location'] ?? '');

    // Validation
    if ($fullName === '')
        $errors[] = 'Full name is required.';
    if ($email === '')
        $errors[] = 'Email is required.';
    if ($password === '' || $confirm === '') {
        $errors[] = 'Password and confirm password are required.';
    } elseif ($password !== $confirm) {
        $errors[] = 'Passwords do not match.';
    }

    if ($accountType === 'brand') {
        if ($brandName === '')
            $errors[] = 'Brand name is required for Brand accounts.';
        if ($brandCategory === '')
            $errors[] = 'Please select a primary brand category.';
    }

    if (empty($errors)) {
        // TODO: Replace with DB insertion
        $successMessage = "Account created successfully (demo only).";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Sign up | LocalTrade</title>
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

<body class="min-h-screen bg-[#0D0D0D] text-white flex flex-col">

    <!-- HEADER -->
    <header class="border-b border-white/10 bg-black/60 backdrop-blur">
        <div class="max-w-5xl mx-auto px-4 h-14 flex items-center justify-between">
            <a href="index.php" class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-full flex items-center justify-center"
                    style="background-color: var(--lt-orange);">
                    <div class="w-4 h-3 border-2 border-white border-b-0 rounded-sm relative">
                        <span class="w-1 h-1 bg-white rounded-full absolute -bottom-1 left-0.5"></span>
                        <span class="w-1 h-1 bg-white rounded-full absolute -bottom-1 right-0.5"></span>
                    </div>
                </div>
                <span class="font-semibold text-lg">LocalTrade</span>
            </a>

            <a href="login.php" class="text-xs text-gray-300 hover:text-orange-400">
                Already have an account? Log in
            </a>
        </div>
    </header>

    <!-- MAIN SIGNUP CARD -->
    <main class="flex-1 flex items-center justify-center px-4 py-10">
        <div class="w-full max-w-xl">

            <div class="bg-[#111111] border border-white/10 rounded-2xl px-6 py-8 shadow-xl shadow-black/40">

                <!-- Header -->
                <div class="mb-6 text-center">
                    <p class="text-xs uppercase tracking-[0.25em] text-orange-400 mb-2">
                        Create Account
                    </p>
                    <h1 class="text-xl sm:text-2xl font-semibold">Sign up to LocalTrade</h1>
                    <p class="text-xs sm:text-sm text-gray-400 mt-1">
                        Join as a buyer or a brand selling products in Nigeria.
                    </p>
                </div>

                <!-- Errors -->
                <?php if (!empty($errors)): ?>
                    <div class="mb-4 bg-red-500/10 border border-red-500/30 text-red-200 px-3 py-2 rounded-xl text-xs">
                        <ul class="list-disc list-inside">
                            <?php foreach ($errors as $e): ?>
                                <li><?= htmlspecialchars($e) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <!-- Success -->
                <?php if ($successMessage): ?>
                    <div
                        class="mb-4 bg-green-500/10 border border-green-500/30 text-green-200 px-3 py-2 rounded-xl text-xs">
                        <?= htmlspecialchars($successMessage) ?>
                    </div>
                <?php endif; ?>

                <!-- FORM START -->
                <form method="post" class="space-y-6" id="signupForm">
                    <?php $selectedType = $_POST['account_type'] ?? 'buyer'; ?>

                    <!-- ACCOUNT TYPE TOGGLE -->
                    <div>
                        <p class="text-xs text-gray-300 mb-2">I am signing up as</p>
                        <div class="grid grid-cols-2 gap-2">
                            <button type="button" data-type="buyer"
                                class="accountType px-3 py-2 rounded-xl border text-xs <?= $selectedType === 'buyer' ? 'border-orange-500 bg-orange-500/10' : 'border-white/20' ?>">
                                Buyer
                            </button>

                            <button type="button" data-type="brand"
                                class="accountType px-3 py-2 rounded-xl border text-xs <?= $selectedType === 'brand' ? 'border-orange-500 bg-orange-500/10' : 'border-white/20' ?>">
                                Brand / Seller
                            </button>
                        </div>

                        <input type="hidden" name="account_type" id="accountTypeInput"
                            value="<?= htmlspecialchars($selectedType) ?>">
                    </div>

                    <!-- BUYER GOOGLE SIGNUP -->
                    <div id="googleSignup" class="<?= $selectedType === 'buyer' ? '' : 'hidden' ?>">
                        <button type="button" onclick="window.location.href='google_oauth_start.php';" class="w-full mt-2 px-4 py-2.5 rounded-full text-xs sm:text-sm font-semibold 
                                   border border-white/15 bg-[#0B0B0B] hover:border-orange-400
                                   flex items-center justify-center gap-2">
                            <img src="https://www.svgrepo.com/show/475656/google-color.svg" class="w-4 h-4"
                                alt="Google icon">
                            <span>Sign up with Google</span>
                        </button>

                        <div class="flex items-center gap-3 my-4">
                            <span class="flex-1 h-px bg-white/10"></span>
                            <span class="text-[11px] text-gray-500">or sign up with email</span>
                            <span class="flex-1 h-px bg-white/10"></span>
                        </div>
                    </div>

                    <!-- FULL NAME -->
                    <div>
                        <label class="block text-xs mb-1">Full name</label>
                        <input type="text" name="full_name" value="<?= htmlspecialchars($fullName ?? '') ?>"
                            class="w-full bg-[#0B0B0B] border border-white/20 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500"
                            placeholder="Your name">
                    </div>

                    <!-- EMAIL -->
                    <div>
                        <label class="block text-xs mb-1">Email address</label>
                        <input type="email" name="email" value="<?= htmlspecialchars($email ?? '') ?>"
                            class="w-full bg-[#0B0B0B] border border-white/20 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500"
                            placeholder="you@example.com">
                    </div>

                    <!-- PASSWORD + CONFIRM -->
                    <div class="grid sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs mb-1">Password</label>
                            <input type="password" name="password"
                                class="w-full bg-[#0B0B0B] border border-white/20 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500"
                                placeholder="Create a password">
                        </div>

                        <div>
                            <label class="block text-xs mb-1">Confirm password</label>
                            <input type="password" name="confirm_password"
                                class="w-full bg-[#0B0B0B] border border-white/20 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500"
                                placeholder="Repeat password">
                        </div>
                    </div>

                    <!-- BRAND FIELDS -->
                    <?php $isBrand = $selectedType === 'brand'; ?>

                    <div id="brandFields"
                        class="<?= $isBrand ? '' : 'hidden' ?> space-y-4 border-t border-white/10 pt-4">
                        <p class="text-xs text-gray-300">Brand details</p>

                        <!-- BRAND NAME -->
                        <div>
                            <label class="block text-xs mb-1">Brand / Store name</label>
                            <input type="text" name="brand_name" value="<?= htmlspecialchars($brandName ?? '') ?>"
                                class="w-full bg-[#0B0B0B] border border-white/20 rounded-xl px-3 py-2 text-sm">
                        </div>

                        <!-- BRAND SLUG -->
                        <div>
                            <label class="block text-xs mb-1">Store URL (optional)</label>
                            <div class="flex items-center gap-2">
                                <span
                                    class="text-gray-500 text-xs bg-[#0B0B0B] border border-white/20 px-2 py-2 rounded-xl">
                                    localtrade.ng/store/
                                </span>
                                <input type="text" name="brand_slug" value="<?= htmlspecialchars($brandSlug ?? '') ?>"
                                    class="flex-1 bg-[#0B0B0B] border border-white/20 rounded-xl px-3 py-2 text-xs"
                                    placeholder="lagos-streetwear">
                            </div>
                        </div>

                        <!-- CATEGORY + LOCATION -->
                        <div class="grid sm:grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs mb-1">Primary category</label>
                                <select name="brand_category"
                                    class="w-full bg-[#0B0B0B] border border-white/20 rounded-xl px-3 py-2 text-xs">
                                    <option value="">Select a category</option>
                                    <?php
                                    $brandCategories = [
                                        'Fashion',
                                        'Beauty',
                                        'Electronics',
                                        'Home & Living',
                                        'Food & Drinks',
                                        'Art & Craft',
                                        'Other'
                                    ];
                                    foreach ($brandCategories as $cat):
                                        $sel = ($brandCategory ?? '') === $cat ? 'selected' : '';
                                        echo "<option $sel>$cat</option>";
                                    endforeach;
                                    ?>
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs mb-1">City / State</label>
                                <input type="text" name="brand_location"
                                    value="<?= htmlspecialchars($brandLocation ?? '') ?>"
                                    class="w-full bg-[#0B0B0B] border border-white/20 rounded-xl px-3 py-2 text-sm"
                                    placeholder="Lagos, Nigeria">
                            </div>
                        </div>
                    </div>

                    <!-- TERMS -->
                    <label class="flex items-start gap-2 text-[11px] text-gray-400">
                        <input type="checkbox" required
                            class="mt-1 w-4 h-4 bg-[#0B0B0B] border border-white/20 rounded">
                        <span>I agree to LocalTrade’s <a href="#" class="text-orange-400">Terms</a> and <a href="#"
                                class="text-orange-400">Privacy Policy</a>.</span>
                    </label>

                    <!-- SUBMIT -->
                    <button type="submit" class="w-full mt-1 px-4 py-2.5 rounded-full text-sm font-semibold"
                        style="background-color: var(--lt-orange);">
                        Create Account
                    </button>
                </form>

                <!-- Login link -->
                <p class="mt-4 text-center text-[11px] text-gray-400">
                    Already have an account?
                    <a href="login.php" class="text-orange-400">Log in</a>
                </p>

            </div>

        </div>
    </main>

    <script>
        // Buyer / Brand Toggle
        const accountTypeButtons = document.querySelectorAll('.accountType');
        const accountTypeInput = document.getElementById('accountTypeInput');
        const brandFields = document.getElementById('brandFields');
        const googleSignup = document.getElementById('googleSignup');

        accountTypeButtons.forEach(button => {
            button.addEventListener('click', () => {
                const type = button.dataset.type;
                accountTypeInput.value = type;

                // Toggle active styling
                accountTypeButtons.forEach(btn => {
                    btn.classList.remove('border-orange-500', 'bg-orange-500/10');
                    btn.classList.add('border-white/20');
                });
                button.classList.add('border-orange-500', 'bg-orange-500/10');
                button.classList.remove('border-white/20');

                // Show/hide fields
                if (type === 'brand') {
                    brandFields.classList.remove('hidden');
                    googleSignup.classList.add('hidden');
                } else {
                    brandFields.classList.add('hidden');
                    googleSignup.classList.remove('hidden');
                }
            });
        });
    </script>

</body>

</html>