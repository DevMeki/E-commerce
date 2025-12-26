<?php
$config = file_exists(__DIR__ . '/config.php') ? include __DIR__ . '/config.php' : [];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Sign up | LocalTrade</title>
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

<body class="min-h-screen bg-brand-parchment text-brand-ink flex flex-col font-sans">

    <!-- Header -->
    <header class="bg-brand-forest shadow-sm">
        <div class="max-w-6xl mx-auto px-4 h-16 flex items-center justify-between">
            <a href="index.php" class="flex items-center gap-2 group">
                <div
                    class="w-8 h-8 rounded-lg bg-brand-orange flex items-center justify-center shadow-lg shadow-brand-orange/20">
                    <span class="text-white font-bold text-xl">L</span>
                </div>
                <span class="font-bold text-xl tracking-tight text-white">LocalTrade</span>
            </a>
            <a href="login.php" class="text-sm font-medium text-white/70 hover:text-white transition-colors">
                Already registered? Log in
            </a>
        </div>
    </header>

    <!-- Main Card -->
    <main class="flex-1 flex items-center justify-center px-4 py-12">
        <div class="w-full max-w-xl">
            <div class="bg-green-50 border border-brand-forest/5 rounded-2xl px-6 sm:px-8 py-8 sm:py-10 shadow-sm">
                <!-- Header -->
                <div class="mb-6 text-center sm:text-left">
                    <h1 class="text-xl sm:text-2xl font-semibold text-brand-forest">Create your account</h1>
                    <p class="text-xs sm:text-sm text-brand-ink/50 mt-1">
                        Select account type and enter your details below.
                    </p>
                </div>
                <!-- Messages (injected by JS) -->
                <div id="formMessages"></div>

                <!-- FORM START -->
                <form method="post" class="space-y-6" id="signupForm">

                    <!-- ACCOUNT TYPE TOGGLE -->
                    <div class="mb-6">
                        <div class="flex items-center gap-4">
                            <button type="button" data-type="buyer"
                                class="accountType flex-1 py-2.5 px-4 rounded-xl text-sm font-medium border border-brand-orange bg-brand-orange text-white transition-all">
                                Buyer
                            </button>
                            <button type="button" data-type="brand"
                                class="accountType flex-1 py-2.5 px-4 rounded-xl text-sm font-medium border border-brand-forest/10 text-brand-ink/50 hover:bg-brand-parchment transition-all">
                                Brand / Seller
                            </button>
                        </div>
                        <input type="hidden" name="account_type" id="accountTypeInput" value="buyer">
                    </div>
                    <!-- BUYER GOOGLE SIGNUP -->
                    <div id="googleSignup" class="mb-6">
                        <button type="button" onclick="window.location.href='process/google_oauth_start';"
                            class="w-full flex items-center justify-center gap-3 py-2.5 px-4 rounded-xl border border-brand-forest/10 text-sm font-medium hover:bg-brand-parchment transition-all">
                            <img src="https://www.svgrepo.com/show/475656/google-color.svg" class="w-5 h-5"
                                alt="Google">
                            <span>Continue with Google</span>
                        </button>
                        <div class="relative flex items-center justify-center my-6">
                            <div class="absolute inset-0 flex items-center">
                                <div class="w-full border-t border-brand-forest/10"></div>
                            </div>
                            <span
                                class="relative px-4 bg-green-50 text-xs text-brand-ink/30 uppercase tracking-widest">Or
                                sign up with email</span>
                        </div>
                    </div>

                    <!-- NAME -->
                    <div id="nameField">
                        <label id="nameLabel" class="block text-xs font-medium text-brand-ink/70 mb-1.5">Full
                            name</label>
                        <input id="nameInput" type="text" name="full_name"
                            class="w-full rounded-xl bg-brand-parchment border border-brand-forest/10 px-4 py-2.5 text-sm text-brand-ink placeholder-brand-ink/40 focus:outline-none focus:border-brand-orange transition-all"
                            placeholder="e.g. John Doe">
                    </div>

                    <!-- EMAIL -->
                    <div>
                        <label class="block text-xs font-medium text-brand-ink/70 mb-1.5">Email address</label>
                        <input type="email" name="email"
                            class="w-full rounded-xl bg-brand-parchment border border-brand-forest/10 px-4 py-2.5 text-sm text-brand-ink placeholder-brand-ink/40 focus:outline-none focus:border-brand-orange transition-all"
                            placeholder="name@example.com">
                    </div>
                    <!-- PASSWORD + CONFIRM -->
                    <div class="grid sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-brand-ink/70 mb-1.5">Password</label>
                            <div class="relative">
                                <input id="passwordInput" type="password" name="password"
                                    class="w-full rounded-xl bg-brand-parchment border border-brand-forest/10 px-4 py-2.5 text-sm text-brand-ink placeholder-brand-ink/40 focus:outline-none focus:border-brand-orange transition-all"
                                    placeholder="••••••••">
                                <button type="button"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-brand-ink/30 hover:text-brand-ink transition-colors toggle-password">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-brand-ink/70 mb-1.5">Repeat password</label>
                            <div class="relative">
                                <input id="confirmPasswordInput" type="password" name="confirm_password"
                                    class="w-full rounded-xl bg-brand-parchment border border-brand-forest/10 px-4 py-2.5 text-sm text-brand-ink placeholder-brand-ink/40 focus:outline-none focus:border-brand-orange transition-all"
                                    placeholder="••••••••">
                                <button type="button"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-brand-ink/30 hover:text-brand-ink transition-colors toggle-confirm">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- BRAND FIELDS -->
                    <div id="brandFields" class="hidden space-y-4 border-t border-brand-forest/5 pt-6">
                        <p class="text-[11px] font-bold text-brand-orange uppercase tracking-widest">Brand details</p>
                        <!-- BRAND NAME -->
                        <div>
                            <label class="block text-xs font-medium text-brand-ink/70 mb-1.5">Brand / Store name</label>
                            <input type="text" name="brand_name"
                                class="w-full rounded-xl bg-brand-parchment border border-brand-forest/10 px-4 py-2.5 text-sm text-brand-ink placeholder-brand-ink/40 focus:outline-none focus:border-brand-orange transition-all"
                                placeholder="e.g. Heritage Crafts">
                        </div>
                        <!-- BRAND SLUG -->
                        <div>
                            <label class="block text-xs font-medium text-brand-ink/70 mb-1.5">Store URL</label>
                            <div class="flex items-center">
                                <span
                                    class="bg-brand-parchment border border-brand-forest/10 border-r-0 rounded-l-xl px-3 py-2.5 text-xs text-brand-ink/40">localtrade.ng/</span>
                                <input type="text" name="brand_slug"
                                    class="flex-1 rounded-r-xl bg-brand-parchment border border-brand-forest/10 px-4 py-2.5 text-sm text-brand-ink placeholder-brand-ink/40 focus:outline-none focus:border-brand-orange transition-all"
                                    placeholder="your-brand-name">
                            </div>
                        </div>

                        <!-- CATEGORY + LOCATION -->
                        <div class="grid sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-medium text-brand-ink/70 mb-1.5">Category</label>
                                <select name="brand_category"
                                    class="w-full rounded-xl bg-brand-parchment border border-brand-forest/10 px-4 py-2.5 text-sm text-brand-ink focus:outline-none focus:border-brand-orange transition-all appearance-none">
                                    <option value="">Select Category</option>
                                    <option>Fashion</option>
                                    <option>Beauty</option>
                                    <option>Electronics</option>
                                    <option>Home & Living</option>
                                    <option>Food & Drinks</option>
                                    <option>Art & Craft</option>
                                    <option>Textiles</option>
                                    <option>Fashion Accessories</option>
                                    <option>Prints</option>
                                    <option>Footwear</option>
                                    <option>Toiletries</option>
                                    <option>Cosmetics</option>
                                    <option>Education</option>
                                    <option>Other</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-brand-ink/70 mb-1.5">City / State</label>
                                <input type="text" name="brand_location"
                                    class="w-full rounded-xl bg-brand-parchment border border-brand-forest/10 px-4 py-2.5 text-sm text-brand-ink placeholder-brand-ink/40 focus:outline-none focus:border-brand-orange transition-all"
                                    placeholder="e.g. Lagos">
                            </div>
                        </div>
                    </div>
                    <!-- TERMS -->
                    <label class="flex items-start gap-2 text-xs text-brand-ink/50 cursor-pointer">
                        <input type="checkbox" required
                            class="mt-0.5 rounded text-brand-orange focus:ring-brand-orange">
                        <span>I agree to LocalTrade’s <a href="#" class="text-brand-orange hover:underline">Terms</a>
                            and <a href="#" class="text-brand-orange hover:underline">Privacy Policy</a>.</span>
                    </label>

                    <!-- SUBMIT -->
                    <button type="submit"
                        class="w-full mt-2 py-3 rounded-xl bg-brand-orange text-white font-semibold text-sm hover:bg-orange-600 transition-colors">
                        Create Account
                    </button>
                </form>

                <!-- Login link -->
                <p class="text-center mt-6 text-sm">
                    <span class="text-brand-ink/50">Already on LocalTrade?</span>
                    <a href="login.php" class="text-brand-orange hover:underline font-medium">Log in</a>
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
        const nameField = document.getElementById('nameField');
        const nameLabel = document.getElementById('nameLabel');
        const nameInput = document.getElementById('nameInput');

        accountTypeButtons.forEach(button => {
            button.addEventListener('click', () => {
                const type = button.dataset.type;
                accountTypeInput.value = type;

                // Toggle active styling
                accountTypeButtons.forEach(btn => {
                    btn.classList.add('border-brand-forest/10', 'text-brand-ink/50');
                    btn.classList.remove('border-brand-orange', 'bg-brand-orange', 'text-white');
                });
                button.classList.add('border-brand-orange', 'bg-brand-orange', 'text-white');
                button.classList.remove('border-brand-forest/10', 'text-brand-ink/50', 'hover:bg-brand-parchment');

                // Update name field
                if (type === 'brand') {
                    nameLabel.textContent = 'Owner name';
                    nameInput.name = 'owner_name';
                    brandFields.classList.remove('hidden');
                    googleSignup.classList.add('hidden');
                } else {
                    nameLabel.textContent = 'Full name';
                    nameInput.name = 'full_name';
                    brandFields.classList.add('hidden');
                    googleSignup.classList.remove('hidden');
                }
            });
        });

        // AJAX form submit
        const signupForm = document.getElementById('signupForm');
        const formMessages = document.getElementById('formMessages');

        // Password visibility toggles
        const passwordInput = document.getElementById('passwordInput');
        const confirmPasswordInput = document.getElementById('confirmPasswordInput');
        const togglePasswordBtn = document.querySelector('.toggle-password');
        const toggleConfirmBtn = document.querySelector('.toggle-confirm');

        function toggleVisibility(input, btn) {
            if (!input) return;
            const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
            input.setAttribute('type', type);
            // optional: change icon appearance
            if (btn) btn.classList.toggle('text-brand-orange');
        }

        if (togglePasswordBtn) togglePasswordBtn.addEventListener('click', () => toggleVisibility(passwordInput, togglePasswordBtn));
        if (toggleConfirmBtn) toggleConfirmBtn.addEventListener('click', () => toggleVisibility(confirmPasswordInput, toggleConfirmBtn));

        signupForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            formMessages.innerHTML = '';

            const submitBtn = signupForm.querySelector('button[type="submit"]');
            submitBtn.disabled = true;

            const fd = new FormData(signupForm);

            let url = accountTypeInput.value === 'buyer' ? 'process/process-user-signup' : 'process/process-brand-signup';

            try {
                const res = await fetch(url, {
                    method: 'POST',
                    body: fd,
                    credentials: 'same-origin'
                });

                const json = await res.json();

                if (json.success) {
                    formMessages.innerHTML = `
                        <div class="mb-6 rounded-xl bg-brand-forest p-4 text-xs text-white font-medium shadow-lg animate-bounce-in">
                            ${json.message || 'Account created successfully.'}
                        </div>`;

                    if (json.redirect) {
                        setTimeout(() => window.location.href = json.redirect, 1500);
                    } else {
                        signupForm.reset();
                        // Reset UI to Buyer
                        document.querySelector('.accountType[data-type="buyer"]').click();
                    }
                } else {
                    const errs = json.errors || ['An unknown error occurred.'];
                    formMessages.innerHTML = `
                        <div class="mb-6 rounded-xl border border-red-500/10 bg-red-50 p-4 text-xs text-red-600 font-medium animate-shake">
                            <ul class="list-disc list-inside space-y-1">${errs.map(e => `<li>${e}</li>`).join('')}</ul>
                        </div>`;
                }
            } catch (err) {
                formMessages.innerHTML = `
                    <div class="mb-6 rounded-xl border border-red-500/10 bg-red-50 p-4 text-xs text-red-600 font-medium">
                        Network or server error. Please try again.
                    </div>`;
            } finally {
                submitBtn.disabled = false;
            }
        });

        // Check URL params to activate brand
        if (new URLSearchParams(window.location.search).get('type') === 'brand') {
            document.querySelector('.accountType[data-type="brand"]').click();
        }
    </script>

</body>

</html>