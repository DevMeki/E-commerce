// Main image preview
const mainInput = document.getElementById('mainImageInput');
const mainPreview = document.getElementById('mainImagePreview');

mainInput?.addEventListener('change', function () {
    const file = this.files[0];
    if (!file) return;

    const reader = new FileReader();
    reader.onload = (e) => {
        mainPreview.innerHTML = '';
        mainPreview.style.backgroundImage = `url('${e.target.result}')`;
        mainPreview.style.backgroundSize = 'cover';
        mainPreview.style.backgroundPosition = 'center';
        mainPreview.style.borderStyle = 'solid';
    };
    reader.readAsDataURL(file);
});

// Category select - remove bracket text after selection
const categorySelect = document.getElementById('categorySelect');
categorySelect?.addEventListener('change', function() {
    if (this.value) {
        const selectedOption = this.options[this.selectedIndex];
        const categoryValue = this.value;
        // Update the displayed text to just the category name
        selectedOption.textContent = categoryValue;
    }
});

// Gallery management
const galleryInput = document.getElementById('galleryInput');
const galleryPreview = document.getElementById('galleryPreview');
const MAX_GALLERY = 8;
let galleryFiles = [];

function updateGalleryInput() {
    const dt = new DataTransfer();
    galleryFiles.forEach(file => dt.items.add(file));
    galleryInput.files = dt.files;
}

function renderGallery() {
    galleryPreview.innerHTML = '';
    galleryFiles.forEach((file, index) => {
        const reader = new FileReader();
        reader.onload = (e) => {
            const wrapper = document.createElement('div');
            wrapper.className = 'relative w-full rounded-xl overflow-hidden bg-[#0B0B0B] border border-white/10';

            const img = document.createElement('img');
            img.src = e.target.result;
            img.className = 'w-full h-full object-cover';

            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'absolute top-1 right-1 w-6 h-6 rounded-full bg-black/80 text-white text-sm flex items-center justify-center border border-white/40 hover:bg-red-600 hover:border-red-400 transition-colors';
            btn.innerHTML = '×';
            btn.onclick = () => {
                galleryFiles.splice(index, 1);
                updateGalleryInput();
                renderGallery();
            };

            wrapper.appendChild(img);
            wrapper.appendChild(btn);
            galleryPreview.appendChild(wrapper);
        };
        reader.readAsDataURL(file);
    });
}

galleryInput?.addEventListener('change', function () {
    const newFiles = Array.from(this.files || []);
    if (!newFiles.length) return;

    newFiles.forEach(file => {
        if (galleryFiles.length < MAX_GALLERY) {
            galleryFiles.push(file);
        }
    });

    if (galleryFiles.length >= MAX_GALLERY && newFiles.length > 0) {
        galleryFiles = galleryFiles.slice(0, MAX_GALLERY);
        showModal({
            title: 'Limit Reached',
            message: `Maximum ${MAX_GALLERY} additional images allowed.`,
            type: 'error'
        });
    }

    updateGalleryInput();
    renderGallery();
});

// Form submission
const form = document.getElementById('productForm');
const messageContainer = document.getElementById('messageContainer');
const submitBtn = document.getElementById('submitBtn');
const draftBtn = document.getElementById('draftBtn');

async function submitForm(isDraft = false) {
    messageContainer.innerHTML = '';

    const formData = new FormData(form);
    if (isDraft) {
        formData.set('status', 'draft');
    }

    const btn = isDraft ? draftBtn : submitBtn;
    const originalText = btn.textContent;
    btn.disabled = true;
    btn.textContent = 'Saving...';

    try {
        const response = await fetch('process/process-add-product.php', {
            method: 'POST',
            body: formData
        });

        const data = await response.json();

        if (data.success) {
            showModal({
                title: 'Success!',
                message: data.message,
                type: 'success',
                onConfirm: () => {
                    form.reset();
                    mainPreview.innerHTML = 'Main image preview';
                    mainPreview.style.backgroundImage = '';
                    mainPreview.style.borderStyle = 'dashed';
                    galleryFiles = [];
                    galleryPreview.innerHTML = '';
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                }
            });
        } else {
            const errorList = data.errors.map(err => `<li>${err}</li>`).join('');
            showModal({
                title: 'Validation Error',
                message: 'Please check the error messages on the form.',
                type: 'error'
            });
            messageContainer.innerHTML = `
                <div class="mb-4 bg-red-500/10 border border-red-500/40 text-red-200 px-3 py-3 rounded-xl text-sm">
                    <ul class="list-disc list-inside space-y-1">${errorList}</ul>
                </div>
            `;
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
    } catch (error) {
        messageContainer.innerHTML = `
            <div class="mb-4 bg-red-500/10 border border-red-500/40 text-red-200 px-3 py-3 rounded-xl text-sm">
                Error: ${error.message}
            </div>
        `;
        window.scrollTo({ top: 0, behavior: 'smooth' });
    } finally {
        btn.disabled = false;
        btn.textContent = originalText;
    }
}

form?.addEventListener('submit', (e) => {
    e.preventDefault();
    submitForm(false);
});

draftBtn?.addEventListener('click', () => {
    submitForm(true);
});
