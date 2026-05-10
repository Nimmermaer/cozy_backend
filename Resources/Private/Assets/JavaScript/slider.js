export const initSliders = () => {
  const sliderWrappers = document.querySelectorAll('.custom-slider');

  sliderWrappers.forEach(wrapper => {
    const container = wrapper.querySelector('.slides-container');
    const prevBtn = wrapper.querySelector('.slide-arrow-prev');
    const nextBtn = wrapper.querySelector('.slide-arrow-next');

    if (container && prevBtn && nextBtn) {
      nextBtn.addEventListener('click', () => {
        // Nutzt die Breite des Containers für den Scroll-Sprung
        container.scrollLeft += container.offsetWidth;
      });

      prevBtn.addEventListener('click', () => {
        container.scrollLeft -= container.offsetWidth;
      });
    }
  });
};
