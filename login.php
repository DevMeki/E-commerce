<?php ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login | LocalTrade</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        :root {
            --lt-orange: #F36A1D;
            --lt-black: #0D0D0D;
        }
    </style>
</head>
<body class="min-h-screen bg-[#0D0D0D] text-white flex flex-col">

<!-- Optional minimal header -->
<header class="border-b border-white/10 bg-black/60 backdrop-blur">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 h-14 flex items-center justify-between">
        <a href="index" class="flex items-center gap-2">
            <div class="w-8 h-8 rounded-full flex items-center justify-center"
                 style="background-color: var(--lt-orange);">
                <div class="w-4 h-3 border-2 border-white border-b-0 rounded-sm relative">
                    <span class="w-1 h-1 bg-white rounded-full absolute -bottom-1 left-0.5"></span>
                    <span class="w-1 h-1 bg-white rounded-full absolute -bottom-1 right-0.5"></span>
                </div>
            </div>
            <span class="font-semibold tracking-tight text-lg">LocalTrade</span>
        </a>
        <a href="index" class="text-xs sm:text-sm text-gray-300 hover:text-orange-400">
            ← Back home
        </a>
    </div>
</header>

<main class="flex-1 flex items-center justify-center px-4 py-8">
    <div class="w-full max-w-md">
        <!-- Card -->
        <div class="bg-[#111111] border border-white/10 rounded-2xl px-5 py-6 sm:px-7 sm:py-8 shadow-xl shadow-black/40">
            <div class="mb-5 text-center">
                <p class="text-xs uppercase tracking-[0.25em] text-orange-400 mb-2">
                    Welcome back
                </p>
                <h1 class="text-xl sm:text-2xl font-semibold">Log in to LocalTrade</h1>
                <p class="text-xs sm:text-sm text-gray-400 mt-1">
                    Access your account to track orders, manage products and more.
                </p>
            </div>

            <div id="loginMessages"></div>

            <form method="post" class="space-y-4" id="loginForm">
                <!-- Email -->
                <div>
                    <label for="email" class="block text-xs font-medium text-gray-200 mb-1">
                        Email address
                    </label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        required
                        value=""
                        class="w-full rounded-xl bg-[#0B0B0B] border border-white/15 px-3 py-2 text-sm text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                        placeholder="you@example.com"
                    >
                </div>

                <!-- Password -->
                <div>
                    <div class="flex items-center justify-between mb-1">
                        <label for="password" class="block text-xs font-medium text-gray-200">
                            Password
                        </label>
                        <a href="#" class="text-[11px] text-orange-400 hover:underline">
                            Forgot password?
                        </a>
                    </div>
                    <div class="relative">
                        <input
                            type="password"
                            id="password"
                            name="password"
                            required
                            class="w-full rounded-xl bg-[#0B0B0B] border border-white/15 px-3 py-2 text-sm text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                            placeholder="Enter your password"
                        >
                        <button type="button" class="absolute right-2 top-2 text-gray-300 toggle-password-login" aria-label="Toggle password visibility">
                            <svg id="loginPassEye" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Remember me -->
                <div class="flex items-center justify-between text-xs text-gray-300">
                    <label class="inline-flex items-center gap-2 cursor-pointer select-none">
                        <input
                            type="checkbox"
                            name="remember"
                            class="w-3.5 h-3.5 rounded border border-white/30 bg-[#0B0B0B] text-orange-500 focus:ring-orange-500"
                        >
                        <span>Remember me</span>
                    </label>
                </div>

                <!-- Submit -->
                <input type="hidden" name="redirect_to" id="redirectToInput" value="">
                <button
                    type="submit"
                    class="w-full mt-1 px-4 py-2.5 rounded-full text-sm font-semibold flex items-center justify-center gap-2"
                    style="background-color: var(--lt-orange);"
                >
                    Log in
                </button>
            </form>

            <!-- Divider -->
            <div class="flex items-center gap-3 my-4">
                <span class="flex-1 h-px bg-white/10"></span>
                <span class="text-[11px] text-gray-500">or</span>
                <span class="flex-1 h-px bg-white/10"></span>
            </div>

            <!-- Social / alt login (optional placeholder) -->
            <button
                type="button"
                class="w-full px-4 py-2.5 rounded-full text-xs sm:text-sm font-medium border border-white/15 bg-[#0B0B0B] hover:border-orange-400 flex items-center justify-center gap-2"
            >
                <span>Continue with Google</span>
            </button>

            <!-- Sign up link -->
            <p class="mt-4 text-[11px] sm:text-xs text-center text-gray-400">
                Don’t have an account?
                <a href="signup" class="text-orange-400 hover:underline">
                    Create a seller or buyer account
                </a>
            </p>
        </div>

        <!-- Small trust text -->
        <p class="mt-4 text-[11px] text-center text-gray-500 max-w-sm mx-auto">
            By logging in, you agree to LocalTrade’s
            <a href="#" class="text-orange-400 hover:underline">Terms</a> and
            <a href="#" class="text-orange-400 hover:underline">Privacy Policy</a>.
        </p>
    </div>
</main>

<script>
    // AJAX login
    const loginForm = document.getElementById('loginForm');
    const loginMessages = document.getElementById('loginMessages');
    const redirectInput = document.getElementById('redirectToInput');

    // set redirect_to to document.referrer so we can return user to previous page
    try { redirectInput.value = document.referrer || '/'; } catch(e) { redirectInput.value = '/'; }

    loginForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        loginMessages.innerHTML = '';
        const btn = loginForm.querySelector('button[type="submit"]');
        btn.disabled = true;

        const fd = new FormData(loginForm);
        try {
            const res = await fetch('process/process-login', { method: 'POST', body: fd, credentials: 'same-origin' });
            const json = await res.json();
            if (json.success) {
                // redirect to provided URL
                window.location.href = json.redirect || '/';
            } else {
                const errs = json.errors || ['Login failed.'];
                loginMessages.innerHTML = `
                    <div class="mb-4 rounded-xl border border-red-500/40 bg-red-500/10 px-3 py-2 text-xs text-red-200">
                        <ul class="list-disc list-inside">${errs.map(e => `<li>${e}</li>`).join('')}</ul>
                    </div>`;
            }
        } catch (err) {
            loginMessages.innerHTML = `
                <div class="mb-4 rounded-xl border border-red-500/40 bg-red-500/10 px-3 py-2 text-xs text-red-200">
                    Network or server error. Please try again.
                </div>`;
        } finally {
            btn.disabled = false;
        }
    });

    // Password visibility toggle for login
    const passwordInput = document.getElementById('password');
    const toggleLoginBtn = document.querySelector('.toggle-password-login');
    if (toggleLoginBtn && passwordInput) {
        toggleLoginBtn.addEventListener('click', () => {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            toggleLoginBtn.classList.toggle('text-orange-400');
        });
    }
</script>

</body>
</html>
