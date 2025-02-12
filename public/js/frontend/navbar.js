document.addEventListener('DOMContentLoaded', () => {
    const modal = document.getElementById('myModal');
    modal.classList.add('flex'); // Menyembunyikan modal saat halaman dimuat
  });
  
// Dropdown toggle dan close ketika klik di luar
const dropdownButton = document.getElementById('dropdownButton');
const dropdownMenu = document.getElementById('dropdownMenu');

document.addEventListener('click', (event) => {
  const isButtonClicked = dropdownButton.contains(event.target);
  const isMenuClicked = dropdownMenu.contains(event.target);

  // Jika tombol diklik, toggle dropdown
  if (isButtonClicked) {
    dropdownMenu.classList.toggle('hidden');
  } 
  // Jika klik di luar dropdown, sembunyikan menu
  else if (!isMenuClicked) {
    dropdownMenu.classList.add('hidden');
  }
});

// Modal open dan close
const openModalButton = document.getElementById('openModalButton');
const modal = document.getElementById('myModal');
const modalOverlay = document.getElementById('modalOverlay');

const closeModalButton = document.getElementById('closeModal');

openModalButton.addEventListener('click', () => {
  modal.classList.remove('hidden');
  document.body.style.overflow = 'hidden'; // Nonaktifkan scroll pada body

  // Fokuskan input pencarian
  const searchInput = document.getElementById('searchInput');
  searchInput.focus();
});

modalOverlay.addEventListener('click', (event) => {
  if (!event.target.closest('.bg-white')) { // Hanya tutup modal jika mengklik di luar konten modal
    closeModal();
  }
});

function closeModal() {
  modal.classList.add('hidden');
  document.body.style.overflow = 'auto'; // Mengembalikan scroll pada body
}

// Menambahkan event listener pada tombol close
closeModalButton.addEventListener('click', closeModal);

// Menambahkan event listener pada overlay
modalOverlay.addEventListener('click', closeModal);

// (Opsional) Menutup modal dengan tombol Escape
document.addEventListener('keydown', (event) => {
  if (event.key === 'Escape') {
    closeModal();
  }
});


// fungsi search pada local storage
document.addEventListener('DOMContentLoaded', () => {
  const searchInput = document.getElementById('searchInput');
  const recentSearchesList = document.getElementById('recentSearchesList');
  const maxRecentSearches = 10;

  // Fungsi: Muat pencarian terakhir dari localStorage
  const loadRecentSearches = () => {
    const recentSearches = JSON.parse(localStorage.getItem('recentSearches')) || [];
    recentSearchesList.innerHTML = recentSearches.map(search => `
      <li class="flex items-center p-3 bg-white dark:bg-gray-800 rounded-lg shadow-sm hover:bg-gray-100 dark:hover:bg-gray-700 transition cursor-pointer" onclick="redirectToSearch('${search}')">
        <div class="flex-1">
          <p class="text-sm font-medium text-gray-800 dark:text-gray-200">${search}</p>
        </div>
        <button onclick="removeRecentSearch('${search}', event)" class="ml-auto w-4 h-4 text-gray-400 dark:text-gray-500">
  <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
  </svg>
</button>
      </li>
    `).join('');
  };
  
  // Fungsi untuk mengarahkan ke halaman pencarian
  window.redirectToSearch = (searchTerm) => {
    window.location.href = `/product?query=${encodeURIComponent(searchTerm)}`;
  };

  // Fungsi: Simpan pencarian terbaru ke localStorage
  const saveRecentSearch = (searchTerm) => {
    if (!searchTerm) return;
    let recentSearches = JSON.parse(localStorage.getItem('recentSearches')) || [];
    recentSearches = [searchTerm, ...recentSearches.filter(term => term !== searchTerm)].slice(0, maxRecentSearches);
    localStorage.setItem('recentSearches', JSON.stringify(recentSearches));
    loadRecentSearches();
  };

  // Fungsi: Hapus pencarian tertentu dari recent searches
  window.removeRecentSearch = (searchTerm, event) => {
  event.stopPropagation(); // Mencegah event bubbling
  let recentSearches = JSON.parse(localStorage.getItem('recentSearches')) || [];
  localStorage.setItem('recentSearches', JSON.stringify(recentSearches.filter(term => term !== searchTerm)));
  loadRecentSearches();
};

  // Event: Simpan pencarian saat Enter ditekan
  searchInput.addEventListener('keypress', function (event) {
    if (event.key === 'Enter' && this.value.trim().length > 0) {
      const searchTerm = this.value.trim();
      saveRecentSearch(searchTerm);
      window.location.href = `/product?query=${searchTerm}`;
    }
  });

  // Event: Simpan pencarian saat rekomendasi produk diklik
  searchResults.addEventListener('click', (event) => {
    const target = event.target.closest('a');
    if (target) {
      const searchTerm = target.querySelector('p.font-semibold')?.innerText || '';
      if (searchTerm) {
        saveRecentSearch(searchTerm);
      }
    }
  });

  loadRecentSearches(); // Muat pencarian terakhir saat halaman dimuat
});


let debounceTimeout;
let previousQuery = ''; // Menyimpan query sebelumnya

const searchInput = document.getElementById('searchInput');
const searchResults = document.getElementById('searchResults');

function formatRupiah(number) {
  return new Intl.NumberFormat('id-ID', {
      style: 'currency',
      currency: 'IDR',
      minimumFractionDigits: 0,
  }).format(number);
}

// Fungsi untuk memperbarui hasil pencarian

function updateSearchResults(data, query) {
  searchResults.innerHTML = ''; // Kosongkan hasil sebelumnya

  if (data.status === 'success' && data.data.length > 0) {
      data.data.forEach(product => {
          const highlightedName = product.name.replace(
              new RegExp(query, 'gi'),
              match => `<strong class="text-pink-600">${match}</strong>`
          );

          const productHTML = `
              <li class="flex justify-between items-center p-2 hover:bg-gray-100 rounded">
                  <a href="/product-detail/${product.slug}" class="block w-full">
                      <div>
                          <p class="font-semibold text-gray-800">${highlightedName}</p>
                          <p class="text-sm text-gray-500 line-through">${formatRupiah(product.base_price)}</p>
                          <p class="text-sm text-pink-600">${formatRupiah(product.final_price)}</p>
                      </div>
                  </a>
              </li>
          `;
          searchResults.innerHTML += productHTML;
      });
  } else {
      searchResults.innerHTML = '<p class="text-gray-500">Produk tidak ditemukan</p>';
  }
}

// Fungsi untuk memanggil API dan memperbarui hasil pencarian
function handleSearch(query) {
    if (query.trim().length > 0) {
        fetch(`/search-products?query=${query}`)
            .then(response => response.json())
            .then(data => {
                updateSearchResults(data, query);
            })
            .catch(error => {
                console.error('Error:', error);
                searchResults.innerHTML = '<p class="text-red-500">Terjadi kesalahan saat mencari produk.</p>';
            });
    } else {
        searchResults.innerHTML = '<p class="text-gray-500">Mulai mengetik untuk mencari produk...</p>';
    }
}

// Event listener untuk input pencarian
searchInput.addEventListener('input', function (event) {
    const query = this.value.trim(); // Ambil nilai input tanpa spasi berlebih

    // Jika tombol spasi ditekan, pencarian langsung dilakukan
    if (event.inputType === 'insertText' && event.data === ' ') {
        clearTimeout(debounceTimeout); // Hentikan debounce
        handleSearch(query); // Lakukan pencarian segera
    } else {
        // Untuk input biasa, gunakan debounce
        clearTimeout(debounceTimeout);
        debounceTimeout = setTimeout(() => {
            if (query !== previousQuery) { // Hanya pencarian jika query berubah
                previousQuery = query;
                handleSearch(query);
            }
        }, 300); // Waktu tunggu 300 ms
    }
});
