document.addEventListener('DOMContentLoaded', function () {
    const mobileMenuButton = document.getElementById('mobile-menu-button');
    const mobileMenu = document.getElementById('mobile-menu');
    const mobileMenuIcon = document.getElementById('mobile-menu-icon');

    if (mobileMenuButton && mobileMenu) {
        mobileMenuButton.addEventListener('click', function () {
            const isHidden = mobileMenu.classList.contains('hidden');
            mobileMenu.classList.toggle('hidden');
            this.setAttribute('aria-expanded', isHidden);

            if (mobileMenuIcon) {
                mobileMenuIcon.className = isHidden 
                    ? 'fa-solid fa-xmark text-xl' 
                    : 'fa-solid fa-bars text-xl';
            }
        });
    }
});
