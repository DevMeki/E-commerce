<?php
// --- SIMPLE PLACEHOLDER BACKEND HANDLING ---
// In production, you’d:
// - Check if user is logged in (seller)
// - Validate all fields deeply
// - Save to database
// - Save logo file to /uploads and store its path
// - Redirect to seller dashboard or store page

$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $brandName      = trim($_POST['brand_name'] ?? '');
    $brandSlug      = trim($_POST['brand_slug'] ?? '');
    $brandCategory  = trim($_POST['brand_category'] ?? '');
    $brandLocation  = trim($_POST['brand_location'] ?? '');
    $brandTagline   = trim($_POST['brand_tagline'] ?? '');
    $brandBio       = trim($_POST['brand_bio'] ?? '');
    $shippingPolicy = trim($_POST['shipping_policy'] ?? '');
    $returnPolicy   = trim($_POST['return_policy'] ?? '');
    $contactEmail   = trim($_POST['contact_email'] ?? '');
    $whatsapp       = trim($_POST['whatsapp'] ?? '');
    $instagram      = trim($_POST['instagram'] ?? '');

    // Very basic validation (expand later)
    if ($brandName === '')      $errors[] = 'Brand name is required.';
    if ($brandCategory === '')  $errors[] = 'Please select a primary category.';
    if ($brandLocation === '')  $errors[] = 'Brand location is required.';
    if ($brandBio === '')       $errors[] = 'Brand description is required.';
    if ($shippingPolicy === '') $errors[] = 'Shipping policy is required.';
    if ($returnPolicy === '')   $errors[] = 'Return policy is required.';
    if ($contactEmail === '')   $errors[] = 'Contact email is required.';

    // Logo check (optional but recommended)
    if (!isset($_FILES['brand_logo']) || $_FILES['brand_logo']['error'] !== UPLOAD_ERR_OK) {
        // You can decide if logo is required:
        // $errors[] = 'Please upload a brand logo.';
    } else {
        // Example: do basic file validation (MIME, size) here
        // $tmpName = $_FILES['brand_logo']['tmp_name'];
        // $fileName = $_FILES['brand_logo']['name'];
        // move_uploaded_file($tmpName, 'uploads/brands/' . $fileName);
    }

    if (empty($errors)) {
        // TODO: Save everything to DB
        $success = 'Your brand profile has been submitted! (Demo only – connect to DB + file storage.)';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Seller Onboarding | LocalTrade</title>
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
        <a href="dashboard" class="text-xs text-gray-300 hover:text-orange-400">
            Skip for now →
        </a>
    </div>
</header>

<!-- MAIN -->
<main class="flex-1 px-4 py-8 flex justify-center">
    <div class="w-full max-w-3xl">
        <div class="bg-[#111111] border border-white/10 rounded-2xl px-5 py-6 sm:px-7 sm:py-8 shadow-xl shadow-black/40">

            <!-- Title -->
            <div class="mb-6">
                <p class="text-xs uppercase tracking-[0.25em] text-orange-400 mb-2">
                    Seller onboarding
                </p>
                <h1 class="text-xl sm:text-2xl font-semibold">Set up your LocalTrade brand</h1>
                <p class="text-xs sm:text-sm text-gray-400 mt-1">
                    Complete these quick steps so buyers can discover and trust your brand.
                </p>
            </div>

            <!-- Step indicator -->
            <div class="flex items-center gap-3 mb-6 text-xs">
                <div class="flex-1 flex items-center gap-2">
                    <div class="step-dot w-6 h-6 rounded-full flex items-center justify-center text-[11px] font-semibold bg-orange-500 text-black" data-step="1">1</div>
                    <span class="hidden sm:inline text-gray-200">Brand basics</span>
                </div>
                <div class="h-px flex-1 bg-white/15"></div>
                <div class="flex-1 flex items-center gap-2">
                    <div class="step-dot w-6 h-6 rounded-full flex items-center justify-center text-[11px] font-semibold bg-white/10" data-step="2">2</div>
                    <span class="hidden sm:inline text-gray-200">Logo & profile</span>
                </div>
                <div class="h-px flex-1 bg-white/15"></div>
                <div class="flex-1 flex items-center gap-2">
                    <div class="step-dot w-6 h-6 rounded-full flex items-center justify-center text-[11px] font-semibold bg-white/10" data-step="3">3</div>
                    <span class="hidden sm:inline text-gray-200">Policies & review</span>
                </div>
            </div>

            <!-- Errors / success -->
            <?php if (!empty($errors)): ?>
                <div class="mb-4 bg-red-500/10 border border-red-500/30 text-red-200 px-3 py-2 rounded-xl text-xs">
                    <ul class="list-disc list-inside space-y-1">
                        <?php foreach ($errors as $e): ?>
                            <li><?= htmlspecialchars($e) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="mb-4 bg-green-500/10 border border-green-500/30 text-green-200 px-3 py-2 rounded-xl text-xs">
                    <?= htmlspecialchars($success) ?>
                </div>
            <?php endif; ?>

            <!-- FORM -->
            <form method="post" enctype="multipart/form-data" id="onboardingForm">
                <!-- STEP 1: BRAND BASICS -->
                <section class="wizard-step" data-step="1">
                    <h2 class="text-sm font-semibold mb-3">Step 1 · Brand basics</h2>
                    <p class="text-[11px] text-gray-400 mb-4">
                        This information appears on your storefront and across the marketplace.
                    </p>

                    <div class="space-y-4 text-sm">
                        <div>
                            <label class="block text-xs mb-1">Brand / Store name</label>
                            <input
                                type="text"
                                name="brand_name"
                                value="<?= htmlspecialchars($brandName ?? '') ?>"
                                class="w-full bg-[#0B0B0B] border border-white/20 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500"
                                placeholder="e.g. Lagos Streetwear Co."
                            >
                        </div>

                        <div>
                            <label class="block text-xs mb-1">Store URL handle (optional)</label>
                            <div class="flex items-center gap-2 text-xs">
                                <span class="bg-[#0B0B0B] border border-white/20 rounded-xl px-2 py-2 text-gray-500">
                                    localtrade.ng/store/
                                </span>
                                <input
                                    type="text"
                                    name="brand_slug"
                                    value="<?= htmlspecialchars($brandSlug ?? '') ?>"
                                    class="flex-1 bg-[#0B0B0B] border border-white/20 rounded-xl px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-orange-500"
                                    placeholder="lagos-streetwear"
                                >
                            </div>
                            <p class="mt-1 text-[10px] text-gray-500">
                                Use only letters, numbers and hyphens. Leave blank to auto-generate.
                            </p>
                        </div>

                        <div class="grid sm:grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs mb-1">Primary category</label>
                                <select
                                    name="brand_category"
                                    class="w-full bg-[#0B0B0B] border border-white/20 rounded-xl px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-orange-500"
                                >
                                    <option value="">Select a category</option>
                                    <?php
                                    $cats = ['Fashion', 'Beauty', 'Electronics', 'Home & Living', 'Food & Drinks', 'Art & Craft', 'Other'];
                                    foreach ($cats as $cat):
                                        $sel = (($brandCategory ?? '') === $cat) ? 'selected' : '';
                                        echo "<option $sel>$cat</option>";
                                    endforeach;
                                    ?>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs mb-1">City / State</label>
                                <input
                                    type="text"
                                    name="brand_location"
                                    value="<?= htmlspecialchars($brandLocation ?? '') ?>"
                                    class="w-full bg-[#0B0B0B] border border-white/20 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500"
                                    placeholder="e.g. Lagos, Nigeria"
                                >
                            </div>
                        </div>
                    </div>
                </section>

                <!-- STEP 2: LOGO & PROFILE -->
                <section class="wizard-step hidden" data-step="2">
                    <h2 class="text-sm font-semibold mb-3">Step 2 · Logo & brand profile</h2>
                    <p class="text-[11px] text-gray-400 mb-4">
                        Add your logo and tell buyers what makes your brand unique.
                    </p>

                    <div class="space-y-5 text-sm">
                        <!-- Logo upload + dynamic preview -->
                        <div>
                            <label class="block text-xs mb-2">Brand logo</label>
                            <div class="flex flex-col sm:flex-row gap-3 sm:items-center">
                                <!-- Dynamic logo preview -->
                                <div id="logoPreview"
                                     class="w-20 h-20 rounded-2xl bg-[#0B0B0B] border border-dashed border-white/20 overflow-hidden flex items-center justify-center text-[11px] text-gray-400">
                                    Logo
                                </div>

                                <div class="flex-1 space-y-2 text-[11px] text-gray-400">
                                    <input
                                        type="file"
                                        id="brandLogoInput"
                                        name="brand_logo"
                                        accept="image/*"
                                        class="block w-full text-xs text-gray-300 file:mr-3 file:px-3 file:py-1.5 file:rounded-full file:border-0 file:text-xs file:font-medium file:bg-orange-500 file:text-black hover:file:bg-orange-400"
                                    >
                                    <p>
                                        Upload a square logo (PNG / JPG, min 400x400). This will show on your store and product pages.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Tagline -->
                        <div>
                            <label class="block text-xs mb-1">Short tagline (optional)</label>
                            <input
                                type="text"
                                name="brand_tagline"
                                value="<?= htmlspecialchars($brandTagline ?? '') ?>"
                                class="w-full bg-[#0B0B0B] border border-white/20 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500"
                                placeholder="e.g. Urban fashion made in Lagos"
                            >
                        </div>

                        <!-- Brand bio -->
                        <div>
                            <label class="block text-xs mb-1">Brand story / description</label>
                            <textarea
                                name="brand_bio"
                                rows="4"
                                class="w-full bg-[#0B0B0B] border border-white/20 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500"
                                placeholder="Tell buyers who you are, what you make, and why you started..."
                            ><?= htmlspecialchars($brandBio ?? '') ?></textarea>
                            <p class="mt-1 text-[10px] text-gray-500">
                                This appears on your store page. Aim for 2–4 short paragraphs.
                            </p>
                        </div>

                        <!-- Socials -->
                        <div class="grid sm:grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs mb-1">WhatsApp (optional)</label>
                                <input
                                    type="text"
                                    name="whatsapp"
                                    value="<?= htmlspecialchars($whatsapp ?? '') ?>"
                                    class="w-full bg-[#0B0B0B] border border-white/20 rounded-xl px-3 py-2 text-sm"
                                    placeholder="+234..."
                                >
                            </div>
                            <div>
                                <label class="block text-xs mb-1">Instagram (optional)</label>
                                <input
                                    type="text"
                                    name="instagram"
                                    value="<?= htmlspecialchars($instagram ?? '') ?>"
                                    class="w-full bg-[#0B0B0B] border border-white/20 rounded-xl px-3 py-2 text-sm"
                                    placeholder="@yourbrand"
                                >
                            </div>
                        </div>
                    </div>
                </section>

                <!-- STEP 3: POLICIES -->
                <section class="wizard-step hidden" data-step="3">
                    <h2 class="text-sm font-semibold mb-3">Step 3 · Store policies & contact</h2>
                    <p class="text-[11px] text-gray-400 mb-4">
                        Clear policies help buyers trust your brand and avoid disputes.
                    </p>

                    <div class="space-y-4 text-sm">
                        <!-- Shipping -->
                        <div>
                            <label class="block text-xs mb-1">Shipping / delivery policy</label>
                            <textarea
                                name="shipping_policy"
                                rows="4"
                                class="w-full bg-[#0B0B0B] border border-white/20 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500"
                                placeholder="e.g. We ship across Nigeria within 2–5 business days. Delivery fees vary by location..."
                            ><?= htmlspecialchars($shippingPolicy ?? '') ?></textarea>
                        </div>

                        <!-- Returns -->
                        <div>
                            <label class="block text-xs mb-1">Return / exchange policy</label>
                            <textarea
                                name="return_policy"
                                rows="4"
                                class="w-full bg-[#0B0B0B] border border-white/20 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500"
                                placeholder="e.g. Returns accepted within 7 days of delivery for unused items with tags..."
                            ><?= htmlspecialchars($returnPolicy ?? '') ?></textarea>
                        </div>

                        <!-- Contact -->
                        <div>
                            <label class="block text-xs mb-1">Support contact email</label>
                            <input
                                type="email"
                                name="contact_email"
                                value="<?= htmlspecialchars($contactEmail ?? '') ?>"
                                class="w-full bg-[#0B0B0B] border border-white/20 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500"
                                placeholder="support@yourbrand.com"
                            >
                            <p class="mt-1 text-[10px] text-gray-500">
                                Customers may contact you via LocalTrade messaging, but this email is used for important updates.
                            </p>
                        </div>

                        <div class="mt-4 text-[11px] text-gray-400">
                            Once you submit, you’ll be able to review your store and start adding products.
                        </div>
                    </div>
                </section>

                <!-- NAV BUTTONS -->
                <div class="mt-6 flex items-center justify-between text-xs">
                    <button
                        type="button"
                        id="prevStep"
                        class="px-4 py-2 rounded-full border border-white/20 text-gray-200 disabled:opacity-40 disabled:cursor-not-allowed"
                    >
                        ← Back
                    </button>

                    <button
                        type="button"
                        id="nextStep"
                        class="px-4 py-2 rounded-full bg-orange-500 text-black font-semibold"
                    >
                        Next →
                    </button>

                    <button
                        type="submit"
                        id="submitWizard"
                        class="hidden px-4 py-2 rounded-full bg-orange-500 text-black font-semibold"
                    >
                        Submit brand profile
                    </button>
                </div>
            </form>
        </div>
    </div>
</main>

<script>
let currentStep = 1;
const totalSteps = 3;

const stepSections = document.querySelectorAll('.wizard-step');
const stepDots = document.querySelectorAll('.step-dot');
const prevBtn = document.getElementById('prevStep');
const nextBtn = document.getElementById('nextStep');
const submitBtn = document.getElementById('submitWizard');

function updateWizard() {
    stepSections.forEach(sec => {
        const step = parseInt(sec.dataset.step, 10);
        sec.classList.toggle('hidden', step !== currentStep);
    });

    stepDots.forEach(dot => {
        const step = parseInt(dot.dataset.step, 10);
        if (step === currentStep) {
            dot.classList.add('bg-orange-500', 'text-black');
            dot.classList.remove('bg-white/10', 'text-white');
        } else if (step < currentStep) {
            dot.classList.add('bg-orange-500/70', 'text-black');
            dot.classList.remove('bg-white/10', 'text-white');
        } else {
            dot.classList.add('bg-white/10', 'text-white');
            dot.classList.remove('bg-orange-500', 'text-black', 'bg-orange-500/70');
        }
    });

    prevBtn.disabled = currentStep === 1;

    if (currentStep === totalSteps) {
        nextBtn.classList.add('hidden');
        submitBtn.classList.remove('hidden');
    } else {
        nextBtn.classList.remove('hidden');
        submitBtn.classList.add('hidden');
    }
}

prevBtn.addEventListener('click', () => {
    if (currentStep > 1) {
        currentStep--;
        updateWizard();
    }
});

nextBtn.addEventListener('click', () => {
    if (currentStep < totalSteps) {
        currentStep++;
        updateWizard();
    }
});

updateWizard();

// LIVE LOGO PREVIEW
const logoInput = document.getElementById('brandLogoInput');
const logoPreview = document.getElementById('logoPreview');

if (logoInput && logoPreview) {
    logoInput.addEventListener('change', function() {
        const file = this.files[0];
        if (!file) return;

        const reader = new FileReader();
        reader.onload = function(e) {
            logoPreview.innerHTML = '';
            logoPreview.style.backgroundImage = `url('${e.target.result}')`;
            logoPreview.style.backgroundSize = 'cover';
            logoPreview.style.backgroundPosition = 'center';
            logoPreview.style.borderStyle = 'solid';
        };
        reader.readAsDataURL(file);
    });
}
</script>

</body>
</html>
