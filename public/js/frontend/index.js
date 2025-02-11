// Swiper instance for Category section
const swiperbtn1 = new Swiper('.swiper-container', {
    slidesPerView: 1, // Default to showing 1 card
    spaceBetween: 20, // Spacing between slides
    freeMode: true,
    grabCursor: true,
    breakpoints: {
      380: {
        slidesPerView: 2, // 2 slide untuk layar di atas 430px (mobile)
      },
      640: {
        slidesPerView: 3, // 2 slide untuk layar kecil (mobile di atas 640px)
      },
      768: {
        slidesPerView: 4, // 2 slide untuk tablet atau layar medium
      },
  
      1024: {
        slidesPerView: 5, // 4 slide untuk layar besar (desktop)
      },
      1280: {
        slidesPerView: 6, // 6 slide untuk layar yang sangat besar
      },
    },
  });
  
  // Swiper instance for New Arrival section
  const swiperbtn2 = new Swiper('.swiper-container2', {
    slidesPerView: 1, // Default to showing 1 card
    spaceBetween: 20, // Spacing between slides
    freeMode: true,
    grabCursor: true,
    breakpoints: {
      380: {
        slidesPerView: 1, // 1 slide untuk layar di atas 380px (mobile)
      },
      430: {
        slidesPerView: 2, // 2 slide untuk layar di atas 430px (mobile)
      },
      768: {
        slidesPerView: 3, // 3 slide untuk layar tablet atau layar sedang
      },
      1024: {
        slidesPerView: 4, // 4 slide untuk layar besar (desktop)
      },
    },
  });
  // Swiper instance for New Arrival section
  const swiperbtn3 = new Swiper('.swiper-container3', {
    slidesPerView: 1, // Default to showing 1 card
    spaceBetween: 20, // Spacing between slides
    freeMode: true,
    grabCursor: true,
    breakpoints: {
      320: {
        slidesPerView: 1, // Untuk layar kecil (mobile)
        spaceBetween: 10, // Jarak antar slide
      },
      480: {
        slidesPerView: 2, // Untuk layar lebih besar dari 480px
        spaceBetween: 15,
      },
      
      768: {
        slidesPerView: 3, // Untuk layar tablet
        spaceBetween: 25,
      },
      1024: {
        slidesPerView: 4, // Untuk layar besar (desktop sedang)
        spaceBetween: 30,
      },
      1280: {
        slidesPerView: 5, // Untuk layar besar (desktop lebar)
        spaceBetween: 35,
      },
    },
  });
  
  // Button controls for Category section
  document.getElementById('prevBtn').addEventListener('click', () => {
    swiperbtn1.slidePrev();
  });
  document.getElementById('nextBtn').addEventListener('click', () => {
    swiperbtn1.slideNext();
  });
  
  // Button controls for New Arrival section
  document.getElementById('prevBtn2').addEventListener('click', () => {
    swiperbtn2.slidePrev();
  });
  document.getElementById('nextBtn2').addEventListener('click', () => {
    swiperbtn2.slideNext();
  });
  
  // Button controls for New Arrival section
  document.getElementById('prevBtn3').addEventListener('click', () => {
    swiperbtn3.slidePrev();
  });
  document.getElementById('nextBtn3').addEventListener('click', () => {
    swiperbtn3.slideNext();
  });
  
  // tab kategori teratas
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
    document.getElementById('content-' + tab).classList.remove('hidden');
    const activeTab = document.getElementById('tab-' + tab);
    activeTab.classList.add('text-pink-300', 'border-pink-300');
    activeTab.classList.remove('text-gray-500', 'border-transparent');
  }
  
  // Set default tab to 'wallet'
  document.addEventListener('DOMContentLoaded', function () {
    showProductTab('wallet');
  });
  
  