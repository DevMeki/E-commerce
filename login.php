<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Login | LocalTrade</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Tailwind CSS CDN -->
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

        .spinner {
            width: 16px;
            height: 16px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: white;
            animation: spin 0.8s linear infinite;
            display: inline-block;
            vertical-align: middle;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }
    </style>
</head>

<body class="min-h-screen bg-brand-parchment text-brand-ink flex flex-col font-sans">

    <!-- Header -->
    <header class="bg-brand-forest shadow-sm">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <a href="index.php" class="flex items-center gap-2 group">
                <div
                    class="w-8 h-8 rounded-lg bg-brand-orange flex items-center justify-center shadow-lg shadow-brand-orange/20">
                    <span class="text-white font-bold text-xl">L</span>
                </div>
                <span class="font-bold text-xl tracking-tight text-white">LocalTrade</span>
            </a>
            <a href="index.php" class="text-sm font-medium text-white/70 hover:text-white transition-colors">
                Back to home
            </a>
        </div>
    </header>

    <main class="flex-1 flex items-center justify-center px-4 py-8">
        <div class="w-full max-w-md">
            <!-- Card -->
            <div class="bg-green-50 border border-brand-forest/5 rounded-2xl px-6 sm:px-8 py-8 sm:py-10 shadow-sm">
                <div class="mb-6 sm:mb-8 text-center sm:text-left">
                    <h1 class="text-xl sm:text-2xl font-semibold text-brand-forest">Login to LocalTrade</h1>
                    <p class="text-xs sm:text-sm text-brand-ink/50 mt-1">
                        Welcome back! Please enter your details.
                    </p>
                </div>

                <div id="loginMessages"></div>

                <form method="post" class="space-y-4" id="loginForm">
                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-xs font-medium text-brand-ink/70 mb-1.5">
                            Email address
                        </label>
                        <input type="email" id="email" name="email" required value=""
                            class="w-full rounded-xl bg-brand-parchment border border-brand-forest/10 px-4 py-2.5 text-sm text-brand-ink placeholder-brand-ink/40 focus:outline-none focus:border-brand-orange transition-all"
                            placeholder="you@example.com">
                    </div>

                    <!-- Password -->
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label for="password" class="block text-xs font-medium text-brand-ink/70">
                                Password
                            </label>
                            <a href="#" class="text-xs text-brand-orange hover:underline font-medium">
                                Forgot password?
                            </a>
                        </div>
                        <div class="relative">
                            <input type="password" id="password" name="password" required
                                class="w-full rounded-xl bg-brand-parchment border border-brand-forest/10 px-4 py-2.5 text-sm text-brand-ink placeholder-brand-ink/40 focus:outline-none focus:border-brand-orange transition-all"
                                placeholder="••••••••">
                            <button type="button"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-brand-ink/30 hover:text-brand-ink transition-colors toggle-password-login"
                                aria-label="Toggle password visibility">
                                <svg id="loginPassEye" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Remember me -->
                    <div class="flex items-center py-1">
                        <label class="flex items-center gap-2 cursor-pointer select-none">
                            <input type="checkbox" name="remember"
                                class="w-4 h-4 rounded border-brand-forest/20 text-brand-orange focus:ring-brand-orange">
                            <span class="text-xs text-brand-ink/50">Remember me</span>
                        </label>
                    </div>

                    <!-- Submit -->
                    <input type="hidden" name="redirect_to" id="redirectToInput" value="">
                    <button type="submit" id="loginButton"
                        class="w-full mt-2 py-3 rounded-xl bg-brand-orange text-white font-semibold text-sm hover:bg-orange-600 transition-colors flex items-center justify-center gap-2">
                        <span id="loginButtonText">Login</span>
                        <div id="loginSpinner" class="spinner hidden"></div>
                    </button>
                </form>

                <!-- Sign up link -->
                <p class="text-center mt-6 text-sm">
                    <span class="text-brand-ink/50">New to LocalTrade?</span>
                    <a href="signup.php" class="text-brand-orange hover:underline font-medium">Join now</a>
                </p>
            </div>

            <!-- Small trust text -->
            <p class="mt-4 text-[11px] text-center text-brand-ink/50 max-w-sm mx-auto">
                By logging in, you agree to LocalTrade's
                <a href="#" class="text-brand-orange hover:underline">Terms</a> and
                <a href="#" class="text-brand-orange hover:underline">Privacy Policy</a>.
            </p>
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const loginForm = document.getElementById('loginForm');
            const loginMessages = document.getElementById('loginMessages');
            const redirectInput = document.getElementById('redirectToInput');
            const loginButton = document.getElementById('loginButton');
            const loginButtonText = document.getElementById('loginButtonText');
            const loginSpinner = document.getElementById('loginSpinner');

            // Set redirect URL - send the previous page URL to backend
            try {
                redirectInput.value = document.referrer || 'index.php';
            } catch (e) {
                redirectInput.value = 'index.php';
            }

            // Handle form submission
            loginForm.addEventListener('submit', async (e) => {
                e.preventDefault();

                // Clear previous messages
                loginMessages.innerHTML = '';

                // Show loading state
                loginButton.disabled = true;
                loginButtonText.textContent = 'Logging in...';
                loginSpinner.classList.remove('hidden');

                const formData = new FormData(loginForm);

                try {
                    const response = await fetch('process/process-login.php', {
                        method: 'POST',
                        body: formData,
                        credentials: 'same-origin'
                    });

                    const responseText = await response.text();

                    try {
                        const jsonData = JSON.parse(responseText);

                        if (jsonData.success) {
                            // Login successful
                            const userType = jsonData.user_type === 'brand' ? 'Brand' : 'Buyer';
                            showSuccess(`✅ Login successful! Redirecting ${userType} account...`);

                            // Redirect after delay
                            setTimeout(() => {
                                window.location.href = jsonData.redirect || 'index.php';
                            }, 1000);

                        } else {
                            // Login failed
                            const errorMessage = jsonData.errors?.[0] || 'Login failed';
                            showError(errorMessage);
                            resetButton();
                        }

                    } catch (jsonError) {
                        console.error('Invalid JSON response:', responseText);
                        showError('Server error. Please try again.');
                        resetButton();
                    }

                } catch (error) {
                    console.error('Network error:', error);
                    showError('Network error. Please check your connection.');
                    resetButton();
                }
            });

            // Helper functions
            function showError(message) {
                loginMessages.innerHTML = `
            <div class="mb-8 rounded-2xl border border-red-500/10 bg-red-50 px-5 py-4 text-xs text-red-600 font-bold shadow-sm flex items-center gap-3 animate-shake">
                <span class="text-lg">⚠️</span>
                <span>${message}</span>
            </div>`;
            }

            function showSuccess(message) {
                loginMessages.innerHTML = `
            <div class="mb-8 rounded-2xl bg-brand-forest px-5 py-4 text-xs text-white font-bold shadow-lg shadow-brand-forest/10 flex items-center gap-3 animate-bounce-in">
                <span class="text-lg">✓</span>
                <span>${message}</span>
            </div>`;
            }

            function resetButton() {
                loginButton.disabled = false;
                loginButtonText.textContent = 'Login';
                loginSpinner.classList.add('hidden');
            }

            // Password visibility toggle
            const passwordInput = document.getElementById('password');
            const toggleLoginBtn = document.querySelector('.toggle-password-login');

            if (toggleLoginBtn && passwordInput) {
                toggleLoginBtn.addEventListener('click', () => {
                    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                    passwordInput.setAttribute('type', type);
                    toggleLoginBtn.classList.toggle('text-orange-400');
                });
            }
        });
    </script>

</body>

</html>