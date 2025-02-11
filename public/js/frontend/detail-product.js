// Function to change main image on thumbnail click
function changeImage(src, element) {
    const mainImage = document.getElementById('main-image');
    mainImage.src = src;

    document.querySelectorAll('.thumbnail').forEach((thumbnail) => {
        thumbnail.classList.remove('border-pink-500');
    });

    element.classList.add('border-pink-500');
}

// Initialize Swiper for Thumbnail Carousel
document.addEventListener('DOMContentLoaded', () => {
    new Swiper('.swiper-container', {
        slidesPerView: 4,
        spaceBetween: 10,

        breakpoints: {
            640: { slidesPerView: 4, spaceBetween: 15 },
            768: { slidesPerView: 5, spaceBetween: 15 },
            1024: { slidesPerView: 5, spaceBetween: 20 },
        },
    });
});

// Function to show detail tab
function showDetailTab(tab) {
    // Hide all content and remove active classes
    document.querySelectorAll('.tab-content').forEach((content) => {
        content.classList.add('hidden');
    });

    document.querySelectorAll('.tab-btn').forEach((button) => {
        button.classList.remove('bg-pink-600');
    });

    // Show selected content and add active class to selected tab
    if (tab === 'detail') {
        document.getElementById('contentDetail').classList.remove('hidden');
        document.getElementById('tabDetail').classList.add('bg-pink-600');
    } else if (tab === 'ulasan') {
        document.getElementById('contentUlasan').classList.remove('hidden');
        document.getElementById('tabUlasan').classList.add('bg-pink-600');
    }
}

// Initialize with Detail Produk tab as active
showDetailTab('ulasan');

// Function to open ulasan tab and scroll to review section
function openUlasan() {
    showDetailTab('ulasan');

    setTimeout(() => {
        document.getElementById('produkReview').scrollIntoView({
            behavior: 'smooth'
        });
    }, 100);
}

// Function to show variant images and update stock
function showVariant(images, stock, buttonElement) {
    const variantImages = JSON.parse(images);

    if (variantImages.length > 0) {
        const mainImage = document.getElementById('main-image');
        mainImage.src = `/storage/${variantImages[0]}`;

        const thumbnailsWrapper = document.getElementById('thumbnails-wrapper');
        thumbnailsWrapper.innerHTML = '';

        variantImages.forEach(image => {
            const slideDiv = document.createElement('div');
            slideDiv.className = 'swiper-slide flex justify-center max-w-20 min-w-20';

            const thumbnailDiv = document.createElement('div');
            thumbnailDiv.className = 'thumbnail border-2 border-gray-300 hover:border-pink-400 focus:border-pink-400 rounded-lg p-2 cursor-pointer';
            thumbnailDiv.setAttribute('onclick', `changeImage('/storage/${image}', this)`);

            const imgElement = document.createElement('img');
            imgElement.className = 'w-16 h-16 object-cover';
            imgElement.src = `/storage/${image}`;
            imgElement.alt = 'Thumbnail';

            thumbnailDiv.appendChild(imgElement);
            slideDiv.appendChild(thumbnailDiv);
            thumbnailsWrapper.appendChild(slideDiv);
        });
    }

    const stockElement = document.querySelector('.text-green-600, .text-red-600');
    if (stock === 0) {
        stockElement.classList.remove('text-green-600', 'bg-green-100');
        stockElement.classList.add('text-red-600', 'bg-red-100');
        stockElement.textContent = 'Out of stock';
    } else {
        stockElement.classList.remove('text-red-600', 'bg-red-100');
        stockElement.classList.add('text-green-600', 'bg-green-100');
        stockElement.textContent = `Stok: ${stock}`;
    }

    const variantButtons = document.querySelectorAll('.variant-button');
    variantButtons.forEach(button => button.classList.remove('bg-gray-300'));
    buttonElement.classList.add('bg-gray-300');
}
  
//fungsi ngezoom gambar
document.addEventListener("DOMContentLoaded", function () {
    const image = document.getElementById("main-image");

    image.addEventListener("mousemove", function (event) {
        let rect = image.getBoundingClientRect();
        let x = (event.clientX - rect.left) / rect.width * 100;
        let y = (event.clientY - rect.top) / rect.height * 100;

        image.style.transformOrigin = `${x}% ${y}%`;
        image.style.transform = "scale(2)";
    });

    image.addEventListener("mouseleave", function () {
        image.style.transform = "scale(1)";
    });
});



                  // Fungsi untuk mengganti gambar utama dan thumbnail
                //   function showVariant(images, stock, buttonElement) {
                //       // Pastikan input images adalah array
                //       const variantImages = JSON.parse(images);

                //       if (variantImages.length > 0) {
                //           // Ganti gambar utama dengan gambar pertama dari varian
                //           const mainImage = document.getElementById('main-image');
                //           mainImage.src = `/storage/${variantImages[0]}`;

                //           // Perbarui carousel thumbnail
                //           const thumbnailsWrapper = document.getElementById('thumbnails-wrapper');
                //           thumbnailsWrapper.innerHTML = ''; // Kosongkan isi thumbnail sebelumnya

                //           // Tambahkan thumbnail berdasarkan gambar varian
                //           variantImages.forEach(image => {
                //               const slideDiv = document.createElement('div');
                //               slideDiv.className = 'swiper-slide flex justify-center max-w-20 min-w-20';

                //               const thumbnailDiv = document.createElement('div');
                //               thumbnailDiv.className = 'thumbnail border-2 border-gray-300 hover:border-pink-400 focus:border-pink-400 rounded-lg p-2 cursor-pointer';
                //               thumbnailDiv.setAttribute('onclick', `changeImage('/storage/${image}')`);

                //               const imgElement = document.createElement('img');
                //               imgElement.className = 'w-16 h-16 object-cover';
                //               imgElement.src = `/storage/${image}`;
                //               imgElement.alt = 'Thumbnail';

                //               thumbnailDiv.appendChild(imgElement);
                //               slideDiv.appendChild(thumbnailDiv);
                //               thumbnailsWrapper.appendChild(slideDiv);
                //           });
                //       }

                //       // Update stok
                //       const stockElement = document.querySelector('.text-green-600, .text-red-600');
                //       if (stock === 0) {
                //           stockElement.classList.remove('text-green-600', 'bg-green-100');
                //           stockElement.classList.add('text-red-600', 'bg-red-100');
                //           stockElement.textContent = 'Out of stock';
                //       } else {
                //           stockElement.classList.remove('text-red-600', 'bg-red-100');
                //           stockElement.classList.add('text-green-600', 'bg-green-100');
                //           stockElement.textContent = 'In stock';
                //       }

                //       // Tambahkan kelas aktif pada tombol yang diklik
                //       const variantButtons = document.querySelectorAll('.variant-button');
                //       variantButtons.forEach(button => button.classList.remove('bg-gray-300'));
                //       buttonElement.classList.add('bg-gray-300');
                //   }

                //   // Fungsi untuk mengganti gambar utama saat thumbnail diklik
                //   function changeImage(imageSrc) {
                //       const mainImage = document.getElementById('main-image');
                //       mainImage.src = imageSrc;
                //   }