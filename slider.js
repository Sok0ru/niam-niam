function initSlider() {
    const slider = document.querySelector('.slider');
    if (!slider) return;

    const slides = slider.querySelector('.slides');
    const slideItems = slides.querySelectorAll('.slide');
    const prevBtn = slider.querySelector('.prev');
    const nextBtn = slider.querySelector('.next');
    
    let currentIndex = 0;
    const slideCount = slideItems.length;
    let slideInterval;

    function updateSlider() {
        slides.style.transform = `translateX(${-currentIndex * 100}%)`;
    }

    function goToSlide(index) {
        currentIndex = (index + slideCount) % slideCount;
        updateSlider();
    }

    function startAutoSlide() {
        slideInterval = setInterval(() => {
            goToSlide(currentIndex + 1);
        }, 5000);
    }

    prevBtn.addEventListener('click', () => {
        clearInterval(slideInterval);
        goToSlide(currentIndex - 1);
        startAutoSlide();
    });

    nextBtn.addEventListener('click', () => {
        clearInterval(slideInterval);
        goToSlide(currentIndex + 1);
        startAutoSlide();
    });

    slider.addEventListener('mouseenter', () => clearInterval(slideInterval));
    slider.addEventListener('mouseleave', startAutoSlide);

    updateSlider();
    startAutoSlide();
}