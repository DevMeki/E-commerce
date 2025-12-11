<script defer>
const mobileMenuButton = document.getElementById('mobileMenuButton');
const mobileMenu       = document.getElementById('mobileMenu');
const iconOpen         = document.getElementById('mobileMenuIconOpen');
const iconClose        = document.getElementById('mobileMenuIconClose');

if (mobileMenuButton && mobileMenu && iconOpen && iconClose) {
    mobileMenuButton.addEventListener('click', () => {
        const isOpen = !mobileMenu.classList.contains('hidden');

        if (isOpen) {
            mobileMenu.classList.add('hidden');
            iconOpen.classList.remove('hidden');
            iconClose.classList.add('hidden');
        } else {
            mobileMenu.classList.remove('hidden');
            iconOpen.classList.add('hidden');
            iconClose.classList.remove('hidden');
        }
    });
}
</script>
