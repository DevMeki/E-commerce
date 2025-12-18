<?php
$config = file_exists(__DIR__ . '/config.php') ? include __DIR__ . '/config.php' : [];
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
            <a href="index" class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-full flex items-center justify-center"
                    style="background-color: var(--lt-orange);">
                    <div class="w-4 h-3 border-2 border-white border-b-0 rounded-sm relative">
                        <span class="w-1 h-1 bg-white rounded-full absolute -bottom-1 left-0.5"></span>
                        <span class="w-1 h-1 bg-white rounded-full absolute -bottom-1 right-0.5"></span>
                    </div>
                </div>
                <span class="font-semibold text-lg">LocalTrade</span>
            </a>

            <a href="login" class="text-xs text-gray-300 hover:text-orange-400">
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

                <!-- Messages (injected by JS) -->
                <div id="formMessages"></div>

                <!-- FORM START -->
                <form method="post" class="space-y-6" id="signupForm">

                    <!-- ACCOUNT TYPE TOGGLE -->
                    <div>
                        <p class="text-xs text-gray-300 mb-2">I am signing up as</p>
                        <div class="grid grid-cols-2 gap-2">
                            <button type="button" data-type="buyer"
                                class="accountType px-3 py-2 rounded-xl border text-xs border-orange-500 bg-orange-500/10">
                                Buyer
                            </button>

                            <button type="button" data-type="brand"
                                class="accountType px-3 py-2 rounded-xl border text-xs border-white/20">
                                Brand / Seller
                            </button>
                        </div>

                        <input type="hidden" name="account_type" id="accountTypeInput" value="buyer">
                    </div>

                    <!-- BUYER GOOGLE SIGNUP -->
                    <div id="googleSignup" class="">
                        <button type="button" onclick="window.location.href='process/google_oauth_start';" class="w-full mt-2 px-4 py-2.5 rounded-full text-xs sm:text-sm font-semibold 
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

                    <!-- NAME -->
                    <div id="nameField">
                        <label id="nameLabel" class="block text-xs mb-1">Full name</label>
                        <input id="nameInput" type="text" name="full_name" value=""
                            class="w-full bg-[#0B0B0B] border border-white/20 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500"
                            placeholder="Your name">
                    </div>

                    <!-- EMAIL -->
                    <div>
                        <label class="block text-xs mb-1">Email address</label>
                        <input type="email" name="email" value=""
                            class="w-full bg-[#0B0B0B] border border-white/20 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500"
                            placeholder="you@example.com">
                    </div>

                    <!-- PASSWORD + CONFIRM -->
                        <div class="grid sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs mb-1">Password</label>
                            <div class="relative">
                                <input id="passwordInput" type="password" name="password"
                                    class="w-full bg-[#0B0B0B] border border-white/20 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500"
                                    placeholder="Create a password">
                                <button type="button" class="absolute right-2 top-2 text-gray-300 toggle-password" aria-label="Toggle password visibility">
                                    <svg id="passEye" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs mb-1">Confirm password</label>
                            <div class="relative">
                                <input id="confirmPasswordInput" type="password" name="confirm_password"
                                    class="w-full bg-[#0B0B0B] border border-white/20 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500"
                                    placeholder="Repeat password">
                                <button type="button" class="absolute right-2 top-2 text-gray-300 toggle-confirm" aria-label="Toggle confirm password visibility">
                                    <svg id="confirmEye" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- BRAND FIELDS -->
                    <div id="brandFields" class="hidden space-y-4 border-t border-white/10 pt-4">
                        <p class="text-xs text-gray-300">Brand details</p>

                        <!-- BRAND NAME -->
                        <div>
                            <label class="block text-xs mb-1">Brand / Store name</label>
                            <input type="text" name="brand_name" value=""
                                class="w-full bg-[#0B0B0B] border border-white/20 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500">
                        </div>

                        <!-- BRAND SLUG -->
                        <div>
                            <label class="block text-xs mb-1">Store URL (optional)</label>
                            <div class="flex items-center gap-2">
                                <span
                                    class="text-gray-500 text-xs bg-[#0B0B0B] border border-white/20 px-2 py-2 rounded-xl">
                                    localtrade.ng/store/
                                </span>
                                <input type="text" name="brand_slug" value=""
                                    class="flex-1 bg-[#0B0B0B] border border-white/20 rounded-xl px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-orange-500"
                                    placeholder="lagos-streetwear">
                            </div>
                        </div>

                        <!-- CATEGORY + LOCATION -->
                        <div class="grid sm:grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs mb-1">Primary category</label>
                                <select name="brand_category"
                                    class="w-full bg-[#0B0B0B] border border-white/20 rounded-xl px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-orange-500">
                                    <option value="">Select a category</option>
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
                                    <option>Other</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs mb-1">City / State</label>
                                <input type="text" name="brand_location" value=""
                                    class="w-full bg-[#0B0B0B] border border-white/20 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500"
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
                    <a href="login" class="text-orange-400">Log in</a>
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
                    btn.classList.remove('border-orange-500', 'bg-orange-500/10');
                    btn.classList.add('border-white/20');
                });
                button.classList.add('border-orange-500', 'bg-orange-500/10');
                button.classList.remove('border-white/20');

                // Update name field
                if (type === 'brand') {
                    nameLabel.textContent = 'Owner name';
                    nameInput.name = 'owner_name';
                    nameInput.placeholder = 'Your full name';
                    brandFields.classList.remove('hidden');
                    googleSignup.classList.add('hidden');
                } else {
                    nameLabel.textContent = 'Full name';
                    nameInput.name = 'full_name';
                    nameInput.placeholder = 'Your name';
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
            if (btn) btn.classList.toggle('text-orange-400');
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
                        <div class="mb-4 bg-green-500/10 border border-green-500/30 text-green-200 px-3 py-2 rounded-xl text-sm">
                            ${json.message ? json.message : 'Account created successfully.'}
                        </div>`;
                    
                    if (json.redirect) {
                        // Redirect after showing success message briefly
                        setTimeout(() => {
                            window.location.href = json.redirect;
                        }, 1500);
                    } else {
                        // Reset form for buyer signup
                        signupForm.reset();
                        // reset UI state
                        accountTypeInput.value = 'buyer';
                        nameLabel.textContent = 'Full name';
                        nameInput.name = 'full_name';
                        nameInput.placeholder = 'Your name';
                        brandFields.classList.add('hidden');
                        googleSignup.classList.remove('hidden');
                        accountTypeButtons.forEach(btn => {
                            btn.classList.remove('border-orange-500', 'bg-orange-500/10');
                            btn.classList.add('border-white/20');
                        });
                        // mark buyer active
                        const buyerBtn = document.querySelector('.accountType[data-type="buyer"]');
                        if (buyerBtn) buyerBtn.classList.add('border-orange-500', 'bg-orange-500/10');
                    }
                } else {
                    const errs = json.errors || ['An unknown error occurred.'];
                    formMessages.innerHTML = `
                        <div class="mb-4 bg-red-500/10 border border-red-500/30 text-red-200 px-3 py-2 rounded-xl text-sm">
                            <ul class="list-disc list-inside">${errs.map(e => `<li>${e}</li>`).join('')}</ul>
                        </div>`;
                }
            } catch (err) {
                formMessages.innerHTML = `
                    <div class="mb-4 bg-red-500/10 border border-red-500/30 text-red-200 px-3 py-2 rounded-xl text-sm">
                        <div>Network or server error. Please try again.</div>
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