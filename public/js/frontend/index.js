// Swiper instance for Category section
  const swiperbtn1 = new Swiper('.swiper-container', {
    slidesPerView: 1, // Default to showing 1 card
    spaceBetween: 20, // Spacing between slides
    freeMode: true,
    grabCursor: true,
    breakpoints: {
        380: { slidesPerView: 2 },
        640: { slidesPerView: 3 },
        768: { slidesPerView: 4 },
        1024: { slidesPerView: 5 },
        1280: { slidesPerView: 6 },
    },
  });

  // Swiper instance for New Arrival section
  const swiperbtn2 = new Swiper('.swiper-container2', {
    slidesPerView: 1,
    spaceBetween: 20,
    freeMode: true,
    grabCursor: true,
    breakpoints: {
        380: { slidesPerView: 1 },
        430: { slidesPerView: 2 },
        768: { slidesPerView: 3 },
        1024: { slidesPerView: 4 },
    },
  });

  // Swiper instance for another section
  const swiperbtn3 = new Swiper('.swiper-container3', {
    slidesPerView: 1,
    spaceBetween: 20,
    freeMode: true,
    grabCursor: true,
    breakpoints: {
        320: { slidesPerView: 1, spaceBetween: 10 },
        480: { slidesPerView: 2, spaceBetween: 15 },
        768: { slidesPerView: 3, spaceBetween: 25 },
        1024: { slidesPerView: 4, spaceBetween: 30 },
        1280: { slidesPerView: 5, spaceBetween: 35 },
    },
  });

  // Function to add event listeners safely
  function addEventListenerIfExists(elementId, event, handler) {
    const element = document.getElementById(elementId);
    if (element) {
        element.addEventListener(event, handler);
    }
  }

  // Button controls for Category section
  addEventListenerIfExists('prevBtn', 'click', () => swiperbtn1.slidePrev());
  addEventListenerIfExists('nextBtn', 'click', () => swiperbtn1.slideNext());

  // Button controls for New Arrival section
  addEventListenerIfExists('prevBtn2', 'click', () => swiperbtn2.slidePrev());
  addEventListenerIfExists('nextBtn2', 'click', () => swiperbtn2.slideNext());

  // Button controls for another section
  addEventListenerIfExists('prevBtn3', 'click', () => swiperbtn3.slidePrev());
  addEventListenerIfExists('nextBtn3', 'click', () => swiperbtn3.slideNext());

  // Tab category switching function
  function showProductTab(tab) {
    // Hide all content
    document.querySelectorAll('.tab-content').forEach((content) => {
        content.classList.add('hidden');
    });

    // Remove active styles from all tabs
    document.querySelectorAll('button[role="tab"]').forEach((tabElement) => {
        tabElement.classList.remove('text-pink-300', 'border-pink-300');
        tabElement.classList.add('text-gray-500', 'border-transparent');
    });

    // Show selected tab content and apply active styles
    const contentElement = document.getElementById('content-' + tab);
    const activeTab = document.getElementById('tab-' + tab);

    if (contentElement && activeTab) {
        contentElement.classList.remove('hidden');
        activeTab.classList.add('text-pink-300', 'border-pink-300');
        activeTab.classList.remove('text-gray-500', 'border-transparent');
    }
  }

  // Set default tab to 'wallet' after DOM is loaded
  document.addEventListener('DOMContentLoaded', function () {
    showProductTab('wallet');
  });
