<script>
  document.addEventListener('DOMContentLoaded', function () {

    // ---------- Mobile nav toggle (hamburger) ----------
    const menuBtn = document.getElementById('openSidebarBtn');
    const menuContent = document.getElementById('mobileMenuContent');

    if (menuBtn && menuContent) {
      menuBtn.addEventListener('click', function (e) {
        e.stopPropagation();
        menuContent.classList.toggle('hidden');
      });

      document.addEventListener('click', function (e) {
        if (!menuContent.contains(e.target) && !menuBtn.contains(e.target)) {
          menuContent.classList.add('hidden');
        }
      });
    }

    // ---------- Accordions ----------
    const accordions = document.querySelectorAll('.accordion');
    if (accordions.length) {
      accordions.forEach((accordion) => {
        const header = accordion.querySelector('header');
        const content = accordion.querySelector('.accordion-content');
        const plusIcon = accordion.querySelector('.plus-icon');
        const closeIcon = accordion.querySelector('.close-icon');
        if (!header || !content) return;

        header.addEventListener('click', () => {
          const isOpen = !content.classList.contains('hidden');

          accordions.forEach((item) => {
            item.querySelector('.accordion-content')?.classList.add('hidden');
            item.querySelector('.plus-icon')?.classList.remove('hidden');
            item.querySelector('.close-icon')?.classList.add('hidden');
          });

          if (!isOpen) {
            content.classList.remove('hidden');
            plusIcon?.classList.add('hidden');
            closeIcon?.classList.remove('hidden');
          }
        });
      });
    }

    // ---------- Testimonial slider ----------
    const track = document.getElementById('slider-track');
    const prevBtn = document.getElementById('prev-btn');
    const nextBtn = document.getElementById('next-btn');
    const cardTemplate = document.getElementById('testimonial-card');

    if (track && prevBtn && nextBtn && cardTemplate) {
      const totalCards = 6;
      const visibleCards = 3;
      let currentIndex = visibleCards;

      function buildSlider() {
        for (let i = 0; i < totalCards + visibleCards * 2; i++) {
          track.appendChild(cardTemplate.content.cloneNode(true));
        }
        updateSlider();
      }

      function updateSlider() {
        track.style.transform = `translateX(-${(100 / visibleCards) * currentIndex}%)`;
      }

      nextBtn.addEventListener('click', () => {
        currentIndex++;
        track.style.transition = 'transform 0.5s ease-in-out';
        updateSlider();
        if (currentIndex === totalCards + visibleCards) {
          setTimeout(() => {
            track.style.transition = 'none';
            currentIndex = visibleCards;
            updateSlider();
          }, 500);
        }
      });

      prevBtn.addEventListener('click', () => {
        currentIndex--;
        track.style.transition = 'transform 0.5s ease-in-out';
        updateSlider();
        if (currentIndex === 0) {
          setTimeout(() => {
            track.style.transition = 'none';
            currentIndex = totalCards;
            updateSlider();
          }, 500);
        }
      });

      buildSlider();
    }

  });
</script>