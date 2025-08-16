// Кнопка "Наверх"
function initBackToTop() {
    const backToTopBtn = document.querySelector('.back-to-top');
    if (!backToTopBtn) return;

    function toggleButton() {
        backToTopBtn.style.display = window.pageYOffset > 300 ? 'flex' : 'none';
    }

    function scrollToTop() {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    }

    window.addEventListener('scroll', toggleButton);
    backToTopBtn.addEventListener('click', scrollToTop);
    toggleButton(); // Инициализация при загрузке
}

// Плавные переходы между страницами
function initPageTransitions() {
    if (!CSS.supports('animation', 'fadeInDark 0.4s')) return;

    const overlay = document.createElement('div');
    overlay.className = 'transition-overlay';
    document.body.appendChild(overlay);

    function handleLinkClick(e) {
        const link = e.target.closest('a[href]');
        if (!link) return;
        
        if (link.href.includes('#') || 
            !link.href.includes(window.location.hostname) ||
            link.href.startsWith('mailto:') || 
            link.href.startsWith('tel:') ||
            e.ctrlKey || e.metaKey) return;
        
        e.preventDefault();
        
        document.body.classList.add('transitioning');
        
        setTimeout(() => {
            document.body.classList.add('fade-out');
            setTimeout(() => window.location.href = link.href, 400);
        }, 50);
    }

    document.body.addEventListener('click', handleLinkClick);

    setTimeout(() => {
        overlay.style.opacity = 0;
        setTimeout(() => overlay.remove(), 400);
    }, 100);
}

// Инициализация всех общих компонентов
document.addEventListener('DOMContentLoaded', function() {
    initBackToTop();
    initPageTransitions();
});