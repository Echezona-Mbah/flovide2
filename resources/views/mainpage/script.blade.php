  <script>
   

    const mobileMenuButton = document.getElementById("mobileMenuButton");
    const mobileMenuContent = document.getElementById("mobileMenuContent");
    const mobileMenuIcon = document.getElementById("mobileMenuIcon");

    mobileMenuButton.addEventListener("click", () => {
      mobileMenuContent.classList.toggle("hidden");
      mobileMenuIcon.classList.toggle("rotate-180");
    });

    // accordions function
    const accordions = document.querySelectorAll(".accordion");
    accordions.forEach((accordion) => {
      const header = accordion.querySelector("header");
      const content = accordion.querySelector(".accordion-content");
      const plusIcon = accordion.querySelector(".plus-icon");
      const closeIcon = accordion.querySelector(".close-icon");

      header.addEventListener("click", () => {
        const isOpen = !content.classList.contains("hidden");

        // Close all other accordions
        accordions.forEach((item) => {
          item.querySelector(".accordion-content").classList.add("hidden");
          item.querySelector(".plus-icon").classList.remove("hidden");
          item.querySelector(".close-icon").classList.add("hidden");
        });

        // Toggle current accordion
        if (!isOpen) {
          content.classList.remove("hidden");
          plusIcon.classList.add("hidden");
          closeIcon.classList.remove("hidden");
        }
      });
    });

    // testmonies sliders fuction
    const track = document.getElementById("slider-track");
    const prevBtn = document.getElementById("prev-btn");
    const nextBtn = document.getElementById("next-btn");
    const cardTemplate = document.getElementById("testimonial-card");
    const totalCards = 6;
    const visibleCards = 3;
    let currentIndex = visibleCards;

    function buildSlider() {
      for (let i = 0; i < totalCards + visibleCards * 2; i++) {
        const clone = cardTemplate.content.cloneNode(true);
        track.appendChild(clone);
      }
      updateSlider();
    }

    function updateSlider() {
      track.style.transform = `translateX(-${(100 / visibleCards) * currentIndex
        }%)`;
    }

    nextBtn.addEventListener("click", () => {
      currentIndex++;
      track.style.transition = "transform 0.5s ease-in-out";
      updateSlider();
      if (currentIndex === totalCards + visibleCards) {
        setTimeout(() => {
          track.style.transition = "none";
          currentIndex = visibleCards;
          updateSlider();
        }, 500);
      }
    });

    prevBtn.addEventListener("click", () => {
      currentIndex--;
      track.style.transition = "transform 0.5s ease-in-out";
      updateSlider();
      if (currentIndex === 0) {
        setTimeout(() => {
          track.style.transition = "none";
          currentIndex = totalCards;
          updateSlider();
        }, 500);
      }
    });

    buildSlider();
  </script>