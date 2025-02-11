document.addEventListener("DOMContentLoaded", function () {
  // Toggle Sidebar
  const toggleBtn = document.getElementById("toggleSidebarBtn");
  const sidebar = document.getElementById("filterSidebar");
  const backdrop = document.getElementById("backdrop");
  const body = document.body;

  toggleBtn.addEventListener("click", function () {
      const isHidden = sidebar.classList.toggle("hidden");
      backdrop.classList.toggle("hidden");

      if (!isHidden) {
          body.classList.add("overflow-hidden");
      } else {
          body.classList.remove("overflow-hidden");
      }
  });

  backdrop.addEventListener("click", function () {
      sidebar.classList.add("hidden");
      backdrop.classList.add("hidden");
      body.classList.remove("overflow-hidden");
  });

  // Accordion
  function setupAccordion(buttonId, contentId, iconId) {
      const button = document.getElementById(buttonId);
      const content = document.getElementById(contentId);
      const icon = document.getElementById(iconId);

      button.addEventListener("click", function () {
          const isExpanded = content.style.display === "block";

          if (isExpanded) {
              content.style.display = "none";
              icon.textContent = "+";
          } else {
              content.style.display = "block";
              icon.textContent = "-";
          }
      });
  }

  setupAccordion("accordion-btn-tipe-produk", "accordion-content-tipe-produk", "icon-tipe-produk");
  setupAccordion("accordion-btn-harga", "accordion-content-harga", "icon-harga");
  // Referensi elemen slider dan display
// Referensi elemen slider dan display
// Referensi elemen slider dan display
const sliderMin = document.getElementById("slider-min");
const sliderMax = document.getElementById("slider-max");
const sliderRange = document.getElementById("slider-range");
const minPriceDisplay = document.getElementById("min-price");
const maxPriceDisplay = document.getElementById("max-price");
const rangeResultMin = document.getElementById("rangeResultMin");
const rangeResultMax = document.getElementById("rangeResultMax");

// Nilai harga awal
const minPrice = 10000; // Harga minimum
const maxPrice = 500000; // Harga maksimum

// Fungsi konversi posisi ke harga
const positionToPrice = (position) => {
    const sliderWidth = sliderRange.offsetWidth;
    return Math.round((position / sliderWidth) * (maxPrice - minPrice) + minPrice);
};

// Fungsi konversi harga ke posisi
const priceToPosition = (price) => {
    const sliderWidth = sliderRange.offsetWidth;
    return Math.round(((price - minPrice) / (maxPrice - minPrice)) * sliderWidth);
};

// Fungsi untuk memperbarui tampilan harga berdasarkan posisi slider
const updateSlider = () => {
    const minLeft = parseInt(sliderMin.style.left || "0", 10);
    const maxLeft = parseInt(sliderMax.style.left || `${sliderRange.offsetWidth}px`, 10);

    const minValue = positionToPrice(minLeft);
    const maxValue = positionToPrice(maxLeft);

    minPriceDisplay.textContent = minValue.toLocaleString("id-ID");
    maxPriceDisplay.textContent = maxValue.toLocaleString("id-ID");

    rangeResultMin.textContent = minValue.toLocaleString("id-ID");
    rangeResultMax.textContent = maxValue.toLocaleString("id-ID");
};

// Fungsi untuk inisialisasi posisi slider
// const initializeSlider = () => {
//     const sliderWidth = sliderRange.offsetWidth;

//     // Pastikan slider memiliki lebar yang valid
//     if (sliderWidth === 0) {
//         setTimeout(initializeSlider, 100); // Ulangi inisialisasi jika slider belum memiliki lebar valid
//         return;
//     }

//     // Hitung posisi berdasarkan harga minimum dan maksimum
//     const minLeft = priceToPosition(minPrice); // Posisi slider kiri
//     const maxLeft = sliderWidth; // Posisi slider kanan (ujung slider)

//     // Set posisi awal slider
//     sliderMin.style.left = `${minLeft}px`;
//     sliderMax.style.left = `${maxLeft}px`;

//     // Perbarui tampilan harga
//     updateSlider();
// };

const initializeSlider = () => {
    const sliderWidth = sliderRange.offsetWidth;

    // Pastikan slider memiliki lebar valid
    if (sliderWidth === 0) {
        setTimeout(initializeSlider, 100); // Ulangi inisialisasi jika slider belum memiliki lebar valid
        return;
    }

    // Ambil nilai min_price dan max_price dari URL
    const urlParams = new URLSearchParams(window.location.search);
    const currentMinPrice = parseInt(urlParams.get("min_price")) || minPrice; // Default ke minPrice jika tidak ada
    const currentMaxPrice = parseInt(urlParams.get("max_price")) || maxPrice; // Default ke maxPrice jika tidak ada

    // Hitung posisi berdasarkan harga
    const minLeft = priceToPosition(currentMinPrice);
    const maxLeft = priceToPosition(currentMaxPrice);

    // Set posisi slider
    sliderMin.style.left = `${minLeft}px`;
    sliderMax.style.left = `${maxLeft}px`;

    // Perbarui tampilan harga
    updateSlider();
};

const onDrag = (e, element) => {
    const rangeLeft = sliderRange.getBoundingClientRect().left;
    const rangeWidth = sliderRange.offsetWidth;

    let x = e.clientX - rangeLeft;

    if (x < 0) x = 0;
    if (x > rangeWidth) x = rangeWidth;

    if (element === sliderMin) {
        const maxLeft = parseInt(sliderMax.style.left || `${rangeWidth}px`, 10);
        if (x > maxLeft) x = maxLeft;
    } else if (element === sliderMax) {
        const minLeft = parseInt(sliderMin.style.left || "0", 10);
        if (x < minLeft) x = minLeft;
    }

    element.style.left = `${x}px`;
    updateSlider();
};

// Fungsi untuk pencarian otomatis
const autoSearch = () => {
    const sliderMinPosition = parseInt(sliderMin.style.left || "0", 10);
    const sliderMaxPosition = parseInt(sliderMax.style.left || `${sliderRange.offsetWidth}px`, 10);

    const minPrice = positionToPrice(sliderMinPosition);
    const maxPrice = positionToPrice(sliderMaxPosition);

    const urlParams = new URLSearchParams(window.location.search);
    urlParams.set("min_price", minPrice);
    urlParams.set("max_price", maxPrice);

    // Redirect dengan query parameter yang diperbarui
    window.location.search = urlParams.toString();
};

// Event listener untuk mendeteksi akhir geseran slider
[sliderMin, sliderMax].forEach((slider) => {
    slider.addEventListener("mousedown", () => {
        const onMouseMove = (event) => onDrag(event, slider);

        // Tambahkan event mousemove untuk geseran slider
        document.addEventListener("mousemove", onMouseMove);

        // Deteksi akhir geseran (mouseup)
        document.addEventListener(
            "mouseup",
            () => {
                document.removeEventListener("mousemove", onMouseMove);
                autoSearch(); // Jalankan pencarian otomatis
            },
            { once: true }
        );
    });

    // Dukungan untuk perangkat layar sentuh (touch events)
    slider.addEventListener("touchstart", () => {
        const onTouchMove = (event) => onDrag(event.touches[0], slider);

        document.addEventListener("touchmove", onTouchMove);

        document.addEventListener(
            "touchend",
            () => {
                document.removeEventListener("touchmove", onTouchMove);
                autoSearch(); // Jalankan pencarian otomatis
            },
            { once: true }
        );
    });
});

// const onDrag = (e, element) => {
//     const rangeLeft = sliderRange.getBoundingClientRect().left;
//     const rangeWidth = sliderRange.offsetWidth;

//     let x = e.clientX - rangeLeft;

//     if (x < 0) x = 0;
//     if (x > rangeWidth) x = rangeWidth;

//     if (element === sliderMin) {
//         const maxLeft = parseInt(sliderMax.style.left || `${rangeWidth}px`, 10);
//         if (x > maxLeft) x = maxLeft;
//     } else if (element === sliderMax) {
//         const minLeft = parseInt(sliderMin.style.left || "0", 10);
//         if (x < minLeft) x = minLeft;
//     }

//     element.style.left = `${x}px`;
//     updateSlider();
// };

// // Event listener untuk drag slider
// [sliderMin, sliderMax].forEach((slider) => {
//     slider.addEventListener("mousedown", () => {
//         const onMouseMove = (event) => onDrag(event, slider);
//         document.addEventListener("mousemove", onMouseMove);

//         document.addEventListener(
//             "mouseup",
//             () => {
//                 document.removeEventListener("mousemove", onMouseMove);
//             },
//             { once: true }
//         );
//     });
// });

// Inisialisasi slider setelah semua elemen selesai dirender
    window.addEventListener("load", () => {
        // Tunda inisialisasi untuk memastikan slider memiliki ukuran yang benar
        setTimeout(() => {
            initializeSlider();
        }, 100);
    });

// Pantau perubahan ukuran slider menggunakan ResizeObserver
    const resizeObserver = new ResizeObserver(() => {
        initializeSlider();
    });
    resizeObserver.observe(sliderRange);


});

document.addEventListener('DOMContentLoaded', () => {
    const sliderMin = document.getElementById('slider-min');
    const sliderMax = document.getElementById('slider-max');
    const rangeResultMin = document.getElementById('rangeResultMin');
    const rangeResultMax = document.getElementById('rangeResultMax');
    // const applyFilterButton = document.getElementById('apply-price-filter');

    // Validasi elemen ada di DOM
    if (!sliderMin || !sliderMax || !rangeResultMin || !rangeResultMax /*|| !applyFilterButton*/) {
        console.error("Slider or filter elements not found in DOM.");
        return;
    }

    // Fungsi untuk memperbarui tampilan harga
    const updatePriceDisplay = () => {
        const minPrice = parseInt(sliderMin.style.left || "0", 10); // Nilai slider min
        const maxPrice = parseInt(sliderMax.style.left || "0", 10); // Nilai slider max

        rangeResultMin.textContent = minPrice.toLocaleString("id-ID");
        rangeResultMax.textContent = maxPrice.toLocaleString("id-ID");
    };

    // Fungsi untuk menerapkan filter
    // const applyFilter = () => {
    //     const minPrice = parseInt(rangeResultMin.textContent.replace(/\D/g, ""), 10) || 10000;
    //     const maxPrice = parseInt(rangeResultMax.textContent.replace(/\D/g, ""), 10) || 1000000;

    //     const urlParams = new URLSearchParams(window.location.search);
    //     urlParams.set('min_price', minPrice);
    //     urlParams.set('max_price', maxPrice);

    //     window.location.search = urlParams.toString(); // Redirect dengan query parameter
    // };


    // Tambahkan event listener untuk slider dan tombol filter
    sliderMin.addEventListener('input', updatePriceDisplay);
    sliderMax.addEventListener('input', updatePriceDisplay);
    // applyFilterButton.addEventListener('click', applyFilter);

    // Perbarui tampilan awal
    updatePriceDisplay();
});


