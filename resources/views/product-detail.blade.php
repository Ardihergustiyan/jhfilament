@include('layouts.header')
@include('components.navbar')
<section class="py-8 bg-inherit dark:bg-gray-900 antialiased">
    <div class="max-w-screen-xl px-4 mx-auto 2xl:px-0">
      <div class="lg:grid lg:grid-cols-2 lg:gap-2 xl:gap-4">
        <!-- Left Section: Image Display and Thumbnails -->
        <div class="max-w-full mx-auto px-4">
          <!-- Main Display Image -->
          <div class="border border-gray-300 rounded-lg p-4 md:p-12 mb-6 bg-white relative overflow-hidden">
              <div class="relative w-full md:w-[421px] h-[200px] md:h-[355px] mx-auto">
                  <img id="main-image" class="w-full h-full object-contain transition-transform duration-300 ease-in-out cursor-pointer" 
                      src="{{ asset('storage/' . ($variants->isNotEmpty() ? $variants->first()->images[0] : $productImages[0])) }}" 
                      alt="{{ $product->name }}" />
              </div>
          </div>
          <!-- Thumbnail Images Carousel -->
          <div class="swiper-container overflow-hidden">
              <div id="thumbnails-wrapper" class="swiper-wrapper">
                  @if ($variants->isNotEmpty())
                      <!-- Thumbnails from Product Variants -->
                      @foreach ($variants as $variant)
                          @foreach ($variant->images as $image)
                              <div class="swiper-slide flex justify-center max-w-16 min-w-16">
                                  <div onclick="changeImage('{{ asset('storage/' . $image) }}')" 
                                      class="thumbnail border-2 border-gray-300 hover:border-pink-400 focus:border-pink-400 rounded-lg p-1 cursor-pointer">
                                      <img class="w-12 h-12 md:w-16 md:h-16 object-cover" src="{{ asset('storage/' . $image) }}" alt="Thumbnail" />
                                  </div>
                              </div>
                          @endforeach
                      @endforeach
                  @else
                      <!-- Thumbnails from Product Images -->
                      @foreach ($productImages as $image)
                          <div class="swiper-slide flex justify-center max-w-16 min-w-16">
                              <div onclick="changeImage('{{ asset('storage/' . $image) }}')" 
                                  class="thumbnail border-2 border-gray-300 hover:border-pink-400 focus:border-pink-400 rounded-lg p-1 cursor-pointer">
                                  <img class="w-12 h-12 md:w-16 md:h-16 object-cover" src="{{ asset('storage/' . $image) }}" alt="Thumbnail" />
                              </div>
                          </div>
                      @endforeach
                  @endif
              </div>
          </div>
      </div>
      
        <!-- Right Section: Product Information -->
        <div class="mt-6 sm:mt-8 lg:mt-0 max-w-full px-4">
          <div class="bg-inherit p-4 rounded-lg shadow-xl">
            <!-- Stock Status -->
            <span class="text-green-600 text-sm font-semibold bg-green-100 px-2 py-1 rounded justify-end">In stock</span>
            <!-- Data stok produk -->
            <div id="product-stock" data-stock="{{ $product->stock }}"></div>           
            
            <!-- Product name -->
            <h2 class="text-2xl font-semibold mt-4">{{ $product->name }}</h2>

            <!-- Rating & Reviews -->
            <div class="flex items-center mt-3">
              <div class="flex items-center">
                <svg class="w-4 h-4 text-yellow-300" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                  <path
                    d="M13.849 4.22c-.684-1.626-3.014-1.626-3.698 0L8.397 8.387l-4.552.361c-1.775.14-2.495 2.331-1.142 3.477l3.468 2.937-1.06 4.392c-.413 1.713 1.472 3.067 2.992 2.149L12 19.35l3.897 2.354c1.52.918 3.405-.436 2.992-2.15l-1.06-4.39 3.468-2.938c1.353-1.146.633-3.336-1.142-3.477l-4.552-.36-1.754-4.17Z"
                  />
                </svg>
                <span class="ml-1">({{ number_format($product->average_rating, 1)}})</span>
              </div>
              <a href="#produkReview" class="text-blue-600 hover:underline ml-2" onclick="openUlasan()">
                ({{ $product->total_reviews ?? 0 }})
              </a>
            </div>

            <!-- Price -->
            <div class="mt-2">
              <!-- Original Price with Strikethrough -->
              <div class="flex items-baseline space-x-2">
                  <span class="text-gray-500 text-lg line-through">
                      Rp{{ number_format($product->base_price, 0, ',', '.') }}
                  </span>

                  <!-- Discount Percentage Badge -->
                  <div class="flex items-center gap-2">
                      <!-- Diskon Reguler (50%) -->
                      <span class="rounded bg-pink-100 px-2.5 py-0.5 text-xs font-medium text-pink-800 dark:bg-pink-900 dark:text-pink-300">
                          50% Off
                      </span>

                      <!-- Jika Ada Diskon Spesial -->
                      @if ($product->total_discount > 0)
                          <span class="text-sm text-gray-500 dark:text-gray-400">+</span>
                          <span class="rounded bg-yellow-200 px-2.5 py-0.5 text-xs font-medium text-blue-800 dark:bg-blue-900 dark:text-blue-300">
                              🎉 {{ $product->discount_display }} Off
                          </span>
                      @endif
                  </div>
              </div>

              <!-- Final Price -->
              <div class="mt-1">
                  @auth
                      <p id="userRole" class="text-lg font-extrabold leading-tight text-gray-900 dark:text-white">
                          @if (isset($product->discounted_price) && $product->discounted_price < $product->final_price)
                              Rp {{ number_format($product->discounted_price, 0, ',', '.') }}
                          @else
                              @if (Auth::user()->hasRole('Reseller'))
                                  Rp {{ number_format($product->final_price, 0, ',', '.') }}
                              @else
                                  Rp {{ number_format($product->het_price, 0, ',', '.') }}
                              @endif
                          @endif
                      </p>
                  @else
                      <p class="text-lg font-extrabold leading-tight text-gray-900 dark:text-white">
                          @if (isset($product->discounted_price) && $product->discounted_price < $product->het_price)
                              Rp {{ number_format($product->discounted_price, 0, ',', '.') }}
                          @else
                              Rp {{ number_format($product->het_price, 0, ',', '.') }}
                          @endif
                      </p>
                  @endauth
              </div>
            </div>

            <!-- Buttons and Quantity -->

            <div class="mt-2 flex flex-col sm:flex-row items-start sm:items-center gap-4 mb-4 pb-4 border-b border-gray-300">
              <div class="flex items-center">
                <button
                    type="button"
                    id="decrement-button"
                    class="inline-flex h-5 w-5 shrink-0 items-center justify-center rounded-md border border-gray-300 bg-gray-100 hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-100 dark:border-gray-600 dark:bg-gray-700 dark:hover:bg-gray-600 dark:focus:ring-gray-700"
                  >
                    <svg class="h-2.5 w-2.5 text-gray-900 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 2">
                      <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 1h16" />
                    </svg>
                </button>
                <input
                  type="text"
                  id="counter-input"
                  class="w-10 shrink-0 border-0 bg-transparent text-center text-lg font-medium text-gray-900 focus:outline-none focus:ring-0 dark:text-white"
                  placeholder=""
                  value="1"
                  min="1"
                  required
                />
                <button
                    type="button"
                    id="increment-button"
                    class="inline-flex h-5 w-5 shrink-0 items-center justify-center rounded-md border border-gray-300 bg-gray-100 hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-100 dark:border-gray-600 dark:bg-gray-700 dark:hover:bg-gray-600 dark:focus:ring-gray-700"
                  >
                    <svg class="h-2.5 w-2.5 text-gray-900 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 18">
                      <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 1v16M1 9h16" />
                    </svg>
                </button>
              </div>
              
              @if (Auth::check())
                {{-- Jika sudah login --}}
                <button 
                    class="inline-flex items-center rounded-lg bg-pink-300 px-4 py-2 text-sm font-medium text-gray-800 hover:bg-pink-400 focus:outline-none focus:ring-4 focus:ring-pink-200 dark:bg-pink-400 dark:hover:bg-pink-500 dark:focus:ring-pink-500" 
                    type="button"  
                    onclick="addToCart({{ $product->id }}, '{{ $product->productVariant->isNotEmpty() ? 'true' : 'false' }}')"
                  >
                    <svg class="-ms-2 me-2 h-5 w-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4h1.5L8 16m0 0h8m-8 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4Zm8 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4Zm.75-3H7.5M11 7H6.312M17 4v6m-3-3h6" />
                    </svg>
                    Add to cart
                </button>
              @else
                {{-- Jika belum login --}}
                <a href="/login" class="inline-flex items-center rounded-lg bg-pink-300 px-4 py-2 text-sm font-medium text-gray-800 hover:bg-pink-400 focus:outline-none focus:ring-4 focus:ring-pink-200 dark:bg-pink-400 dark:hover:bg-pink-500 dark:focus:ring-pink-500">
                    <svg class="-ms-2 me-2 h-5 w-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4h1.5L8 16m0 0h8m-8 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4Zm8 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4Zm.75-3H7.5M11 7H6.312M17 4v6m-3-3h6" />
                    </svg>
                    Add to cart
                </a>
              @endif
            </div>

            {{-- script untuk memperbarui counter dan memilih varian dan add to cart --}}
            <script>
              document.addEventListener("DOMContentLoaded", function () {
                const decrementButton = document.getElementById("decrement-button");
                const incrementButton = document.getElementById("increment-button");
                const counterInput = document.getElementById("counter-input");

                // Fungsi untuk memperbarui nilai counter
                function updateCounter(delta) {
                  let currentValue = parseInt(counterInput.value) || 1; // Nilai default adalah 1
                  currentValue += delta; // Tambah atau kurangi nilai
                  if (currentValue < 1) currentValue = 1; // Minimal nilai adalah 1
                  counterInput.value = currentValue; // Perbarui input
                }

                // Tambahkan event listener untuk decrement dan increment
                decrementButton.addEventListener("click", function () {
                  updateCounter(-1);
                });

                incrementButton.addEventListener("click", function () {
                  updateCounter(1);
                });

                // Validasi input agar hanya angka positif
                counterInput.addEventListener("input", function () {
                  let sanitizedValue = parseInt(counterInput.value.replace(/\D/g, "")) || 1;
                  if (sanitizedValue < 1) sanitizedValue = 1; // Minimal adalah 1
                  counterInput.value = sanitizedValue;
                });

                let selectedVariantId = null; // Simpan varian yang dipilih

                // Fungsi untuk memilih varian
                window.selectVariant = function (button, variantId, images, stock) {
                    selectedVariantId = variantId; // Simpan varian yang dipilih

                    // Hapus warna aktif dari semua tombol
                    document.querySelectorAll(".variant-button").forEach(btn => {
                        btn.classList.remove("bg-gray-300", "text-white");
                        btn.classList.add("bg-white", "text-gray-900");
                    });

                    // Tambahkan warna aktif ke tombol yang dipilih
                    button.classList.remove("bg-white", "text-gray-900");
                    button.classList.add("bg-gray-300", "text-white");

                    // Panggil showVariant untuk mengganti gambar utama dan stok
                    showVariant(images, stock, button);
                };

                
                // Fungsi untuk menampilkan stok produk tanpa varian
                

                
                // Fungsi addToCart
                // window.addToCart = function (productId) {
                //   // Ambil nilai terbaru dari counter input
                //   const quantity = parseInt(counterInput.value) || 1;
                //   console.log("Selected Variant ID:", selectedVariantId); // Debugging

                //   fetch("{{ route('cart.add') }}", {
                //     method: "POST",
                //     headers: {
                //       "Content-Type": "application/json",
                //       "X-CSRF-TOKEN": "{{ csrf_token() }}",
                //     },
                //     body: JSON.stringify({
                //       product_id: productId,
                //       quantity: quantity, // Kirimkan jumlah item yang dipilih
                //       variant_id: selectedVariantId,
                //     }),
                //   })
                //     .then((response) => response.json())
                //     .then((data) => {
                //       console.log("Response from server:", data); // Debugging
                //       alert(data.message); // Tampilkan notifikasi
                //       updateCartItemCount(); // Perbarui jumlah item di ikon cart
                //     })
                //     .catch((error) => console.error("Error:", error));
                // };
                window.addToCart = function (productId) {
                    // Ambil nilai terbaru dari counter input
                    const quantity = parseInt(counterInput.value) || 1;
                    console.log("Selected Variant ID:", selectedVariantId); // Debugging

                    fetch("{{ route('cart.add') }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": "{{ csrf_token() }}",
                        },
                        body: JSON.stringify({
                            product_id: productId,
                            quantity: quantity, // Kirimkan jumlah item yang dipilih
                            variant_id: selectedVariantId,
                        }),
                    })
                    .then((response) => {
                        if (!response.ok) {
                            // Jika respons tidak OK (misalnya, status 400), lempar error
                            return response.json().then(data => {
                                throw new Error(data.message || 'Terjadi kesalahan saat menambahkan ke keranjang.');
                            });
                        }
                        return response.json();
                    })
                    .then((data) => {
                        console.log("Response from server:", data); // Debugging
                        alert(data.message); // Tampilkan notifikasi sukses
                        updateCartItemCount(); // Perbarui jumlah item di ikon cart
                    })
                    .catch((error) => {
                        console.error("Error:", error);
                        alert(error.message); // Tampilkan notifikasi error
                    });
                };
              });

              // Event listener untuk DOMContentLoaded
              document.addEventListener("DOMContentLoaded", function () {
                  // Pilih tombol varian pertama secara otomatis jika ada varian
                  const defaultVariantButton = document.getElementById("default-variant");
                  if (defaultVariantButton) {
                      const variantId = defaultVariantButton.getAttribute("data-variant-id");
                      const images = defaultVariantButton.getAttribute("data-images");
                      const stock = parseInt(defaultVariantButton.getAttribute("data-stock"));

                      // Panggil fungsi selectVariant untuk memilih varian pertama
                      selectVariant(defaultVariantButton, variantId, images, stock);
                  } else {
                      // Jika tidak ada varian, tampilkan stok produk
                      const productStock = parseInt(document.getElementById('product-stock').getAttribute('data-stock'));
                      showProductStock(productStock);
                  }
              });

              // Fungsi untuk menampilkan stok produk tanpa varian
              function showProductStock(productStock) {
                  const stockElement = document.querySelector('.text-green-600, .text-red-600');
                  if (productStock === 0) {
                      stockElement.classList.remove('text-green-600', 'bg-green-100');
                      stockElement.classList.add('text-red-600', 'bg-red-100');
                      stockElement.textContent = 'Out of stock';
                  } else {
                      stockElement.classList.remove('text-red-600', 'bg-red-100');
                      stockElement.classList.add('text-green-600', 'bg-green-100');
                      stockElement.textContent = `Stok: ${productStock}`;
                  }
              }



              // document.addEventListener("DOMContentLoaded", function () {
              //     // Pilih tombol varian pertama secara otomatis
              //     const defaultVariantButton = document.getElementById("default-variant");
              //     if (defaultVariantButton) {
              //         const variantId = defaultVariantButton.getAttribute("data-variant-id");
              //         const images = defaultVariantButton.getAttribute("data-images");
              //         const stock = parseInt(defaultVariantButton.getAttribute("data-stock"));
                  
              //         // Panggil fungsi selectVariant untuk memilih varian pertama
              //         selectVariant(defaultVariantButton, variantId, images, stock);
              //     }
              // });
            </script>

            <div class="space-y-4">
              <!-- Colour Options -->
              @if ($variants->isNotEmpty())
                  <div>
                      <h3 class="text-lg font-semibold mb-3">Colour</h3>
                      <div class="flex flex-wrap gap-2">
                          @foreach ($variants as $index => $variant)
                              <button
                                  type="button"
                                  class="variant-button text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700"
                                  data-variant-id="{{ $variant->id }}"
                                  data-images='{{ json_encode($variant->images) }}'
                                  data-stock="{{ $variant->stock }}"
                                  onclick="selectVariant(this, {{ $variant->id }}, '{{ json_encode($variant->images) }}', {{ $variant->stock }})"
                                  @if ($index === 0) id="default-variant" @endif
                              >
                                  {{ $variant->color }}
                              </button>
                          @endforeach
                      </div>
                  </div>
              @endif

              <div>
                <h3 class="text-lg font-semibold text-gray-800">Delivery Options</h3>
                    @php
                        // Validasi URL
                        $shopeeUrl = filter_var($product->external_product[0]['external_product'] ?? '#', FILTER_VALIDATE_URL) ? $product->external_product[0]['external_product'] : '#';
                        $lazadaUrl = filter_var($product->external_product[1]['external_product'] ?? '#', FILTER_VALIDATE_URL) ? $product->external_product[1]['external_product'] : '#';
                    @endphp
                <div class="space-y-3">
                    <!-- Shopee Button -->
                    @if ($shopeeUrl !== '#')
                        <a href="{{ $shopeeUrl }}" target="_blank" class="py-3 px-4 rounded-lg text-sm font-medium text-gray-700 bg-gray-100 inline-flex items-center justify-center hover:bg-gray-200 focus:outline-none focus:ring-4 focus:ring-gray-300 transition duration-150 ease-in-out">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-orange-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 2L15.09 8.26L22 9.27l-5.46 4.73L17.64 21 12 17.27 6.36 21l1.09-6.99L2 9.27l6.91-1.01L12 2z" />
                            </svg>
                            <span>Kirim melalui Shopee</span>
                        </a>
                    @else
                        <button class="py-3 px-4 rounded-lg text-sm font-medium text-gray-500 bg-gray-200 cursor-not-allowed" disabled>
                            Tautan Shopee Tidak Tersedia
                        </button>
                    @endif

                    <!-- Lazada Button -->
                    @if ($lazadaUrl !== '#')
                        <a href="{{ $lazadaUrl }}" target="_blank" class="py-3 px-4 rounded-lg text-sm font-medium text-gray-700 bg-gray-100 inline-flex items-center justify-center hover:bg-gray-200 focus:outline-none focus:ring-4 focus:ring-gray-300 transition duration-150 ease-in-out">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-blue-600 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 2L15.09 8.26L22 9.27l-5.46 4.73L17.64 21 12 17.27 6.36 21l1.09-6.99L2 9.27l6.91-1.01L12 2z" />
                            </svg>
                            <span>Kirim melalui Lazada</span>
                        </a>
                    @else
                        <button class="py-3 px-4 rounded-lg text-sm font-medium text-gray-500 bg-gray-200 cursor-not-allowed" disabled>
                            Tautan Lazada Tidak Tersedia
                        </button>
                    @endif
                </div>
              </div>

            </div>

          </div>
        </div>

      </div>
    </div>
</section>
  <!-- end detail product -->

  <!-- deskripsi & reviews -->
  <div id="produkReview" class="py-8 pt-20 bg-inherit max-w-screen-xl px-4 mx-auto 2xl:px-0">
    <!-- Tab Navigation -->
    <div class="flex max-w-screen-xl border-b border-gray-300">
      <button id="tabDetail" onclick="showDetailTab('detail')" class="tab-btn text-white bg-pink-400 hover:bg-pink-600 px-4 py-2 font-semibold">Detail Produk</button>
      <button id="tabUlasan" onclick="showDetailTab('ulasan')" class="tab-btn text-white bg-pink-400 hover:bg-pink-600 px-4 py-2 font-semibold">Ulasan Produk</button>
    </div>

    <!-- Tab Content -->
    <div id="contentDetail" class="tab-content hidden mt-6">
      <section class="bg-inherit border border-gray-400 py-4 antialiased dark:bg-gray-900">
        <div class="mx-auto max-w-screen-xl px-4 2xl:px-0">
          <div class="mx-auto max-w-5xl">
            
            <div class="mx-auto max-w-2xl space-y-6">
                {{ $product->description }}
            </div>

            <div class="text-center my-10">
              <a
                href="#"
                class="mb-2 mr-2 rounded-lg border border-gray-200 bg-white px-5 py-2.5 text-sm font-medium text-gray-900 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:outline-none focus:ring-4 focus:ring-gray-100 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white dark:focus:ring-gray-700"
                >Show more...</a
              >
            </div>
          </div>
        </div>
      </section>
    </div>

    <div id="contentUlasan" class="tab-content mt-6">
      <section class="bg-inherit border border-gray-400 py-4 antialiased dark:bg-gray-900">
        <div class="mx-auto max-w-screen-xl px-4 2xl:px-0">
          <div class="mx-auto max-w-5xl">
            <h1 class="text-2xl font-semibold mx-auto">Ulasan Produk</h1>
            <div class="gap-4 sm:flex sm:items-center sm:justify-end">
              <div class="mt-6 sm:mt-0">
                  <form method="GET" action="{{ url()->current() }}">
                      <label for="order-type" class="sr-only mb-2 block text-sm font-medium text-gray-900 dark:text-white">
                          Select review type
                      </label>
                      <select
                            id="order-type"
                            name="stars"
                            class="block w-full min-w-[8rem] rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder:text-gray-400 dark:focus:border-primary-500 dark:focus:ring-primary-500"
                            onchange="this.form.action += '#produkReview'; this.form.submit()"
                        >
                            <option value="" {{ request('stars') === null ? 'selected' : '' }}>All reviews</option>
                            <option value="5" {{ request('stars') == 5 ? 'selected' : '' }}>5 stars</option>
                            <option value="4" {{ request('stars') == 4 ? 'selected' : '' }}>4 stars</option>
                            <option value="3" {{ request('stars') == 3 ? 'selected' : '' }}>3 stars</option>
                            <option value="2" {{ request('stars') == 2 ? 'selected' : '' }}>2 stars</option>
                            <option value="1" {{ request('stars') == 1 ? 'selected' : '' }}>1 star</option>
                        </select>
                  
                  </form>
              </div>
          </div>
          

          @foreach ($reviews as $review)
            <div class="mt-6 flow-root sm:mt-8">
              <div class="divide-y divide-gray-200 dark:divide-gray-700">
                <div class="grid md:grid-cols-12 gap-4 md:gap-6 pb-4 md:pb-6">
                  <dl class="md:col-span-3 order-3 md:order-1 flex items-center space-x-4 ">
                    <!-- Foto Profil -->
                    <dt class="sr-only">User Profile</dt>
                    <dd>
                      <img src="{{ $review->profile_image ? asset($review->profile_image) : asset('img/component/profile_default.png') }}" alt="{{ $review->first_name ?? 'Anonymous' }} {{ $review->last_name ?? '' }}" class="min-w-10 h-10 border border-gray-300 rounded-full object-cover" />
                    </dd>

                    <!-- Nama Pengguna -->
                    <dd class="text-base font-semibold text-gray-900 dark:text-white">
                      <a href="#" class="hover:underline">  {{ $review->first_name ?? 'Anonymous' }} {{ $review->last_name ?? '' }}
                      </a>
                    </dd>
                  </dl>

                  <dl class="md:col-span-6 order-4 md:order-2 flex items-center">
                    <dt class="sr-only">Message:</dt>
                    <dd class="text-gray-500 dark:text-gray-40)"> {{ $review->review }}
                    </dd>
                  </dl>

                  <div class="md:col-span-3 content-center order-1 md:order-3 flex items-center justify-between">
                    <dl>
                      <dt class="sr-only">Stars:</dt>
                      <dd class="flex items-center space-x-1">
                        @for ($i = 1; $i <= 5; $i++)
                          @if ($i <= $review->rating)
                            <svg class="w-5 h-5 text-yellow-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                              <path d="M13.8 4.2a2 2 0 0 0-3.6 0L8.4 8.4l-4.6.3a2 2 0 0 0-1.1 3.5l3.5 3-1 4.4c-.5 1.7 1.4 3 2.9 2.1l3.9-2.3 3.9 2.3c1.5 1 3.4-.4 3-2.1l-1-4.4 3.4-3a2 2 0 0 0-1.1-3.5l-4.6-.3-1.8-4.2Z"></path>
                            </svg>
                          @else
                            <!-- Bintang tidak aktif -->
                            <svg class="w-5 h-5 text-gray-300" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                              <path d="M13.8 4.2a2 2 0 0 0-3.6 0L8.4 8.4l-4.6.3a2 2 0 0 0-1.1 3.5l3.5 3-1 4.4c-.5 1.7 1.4 3 2.9 2.1l3.9-2.3 3.9 2.3c1.5 1 3.4-.4 3-2.1l-1-4.4 3.4-3a2 2 0 0 0-1.1-3.5l-4.6-.3-1.8-4.2Z"></path>
                            </svg>
                          @endif
                        @endfor
                      </dd>
                    </dl>
                  </div>
                </div>
              </div>
            </div>
          @endforeach
            <nav class="mt-6 flex items-center justify-center sm:mt-8" aria-label="Page navigation example">
              <ul class="flex h-8 items-center -space-x-px text-sm">
                <li>
                  <a
                    href="#"
                    class="ms-0 flex h-8 items-center justify-center rounded-s-lg border border-e-0 border-gray-300 bg-white px-3 leading-tight text-gray-500 hover:bg-gray-100 hover:text-gray-700 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white"
                  >
                    <span class="sr-only">Previous</span>
                    <svg class="h-4 w-4 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                      <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m15 19-7-7 7-7" />
                    </svg>
                  </a>
                </li>
                <li>
                  <a
                    href="#"
                    class="flex h-8 items-center justify-center border border-gray-300 bg-white px-3 leading-tight text-gray-500 hover:bg-gray-100 hover:text-gray-700 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white"
                    >1</a
                  >
                </li>
                <li>
                  <a
                    href="#"
                    class="flex h-8 items-center justify-center border border-gray-300 bg-white px-3 leading-tight text-gray-500 hover:bg-gray-100 hover:text-gray-700 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white"
                    >2</a
                  >
                </li>
                <li>
                  <a
                    href="#"
                    aria-current="page"
                    class="z-10 flex h-8 items-center justify-center border border-primary-300 bg-primary-50 px-3 leading-tight text-primary-600 hover:bg-primary-100 hover:text-primary-700 dark:border-gray-700 dark:bg-gray-700 dark:text-white"
                    >3</a
                  >
                </li>
                <li>
                  <a
                    href="#"
                    class="flex h-8 items-center justify-center border border-gray-300 bg-white px-3 leading-tight text-gray-500 hover:bg-gray-100 hover:text-gray-700 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white"
                    >...</a
                  >
                </li>
                <li>
                  <a
                    href="#"
                    class="flex h-8 items-center justify-center border border-gray-300 bg-white px-3 leading-tight text-gray-500 hover:bg-gray-100 hover:text-gray-700 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white"
                    >100</a
                  >
                </li>
                <li>
                  <a
                    href="#"
                    class="flex h-8 items-center justify-center rounded-e-lg border border-gray-300 bg-white px-3 leading-tight text-gray-500 hover:bg-gray-100 hover:text-gray-700 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white"
                  >
                    <span class="sr-only">Next</span>
                    <svg class="h-4 w-4 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                      <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m9 5 7 7-7 7" />
                    </svg>
                  </a>
                </li>
              </ul>
            </nav>
          </div>
        </div>
      </section>

      {{-- <div id="deleteReviewModal" tabindex="-1" aria-hidden="true" class="fixed left-0 right-0 top-0 z-50 hidden h-modal w-full items-center justify-center overflow-y-auto overflow-x-hidden md:inset-0 md:h-full">
        <div class="relative h-full w-full max-w-md p-4 md:h-auto">
          <!-- Modal content -->
          <div class="relative rounded-lg bg-white p-4 text-center shadow dark:bg-gray-800 sm:p-5">
            <button
              type="button"
              class="absolute right-2.5 top-2.5 ml-auto inline-flex items-center rounded-lg bg-transparent p-1.5 text-sm text-gray-400 hover:bg-gray-200 hover:text-gray-900 dark:hover:bg-gray-600 dark:hover:text-white"
              data-modal-toggle="deleteReviewModal"
            >
              <svg aria-hidden="true" class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path
                  fill-rule="evenodd"
                  d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                  clip-rule="evenodd"
                ></path>
              </svg>
              <span class="sr-only">Close modal</span>
            </button>
            <div class="mx-auto mb-4 flex h-12 w-12 items-center justify-center rounded-lg bg-gray-100 p-2 dark:bg-gray-700">
              <svg class="h-8 w-8 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 7h14m-9 3v8m4-8v8M10 3h4a1 1 0 0 1 1 1v3H9V4a1 1 0 0 1 1-1ZM6 7h12v13a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1V7Z" />
              </svg>
              <span class="sr-only">Danger icon</span>
            </div>
            <p class="mb-3.5 text-gray-900 dark:text-white">Are you sure you want to delete this review?</p>
            <p class="mb-4 text-gray-500 dark:text-gray-300">This action cannot be undone.</p>
            <div class="flex items-center justify-center space-x-4">
              <button
                data-modal-toggle="deleteReviewModal"
                type="button"
                class="py-2 px-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700"
              >
                No, cancel
              </button>
              <button
                type="submit"
                class="rounded-lg bg-red-700 px-3 py-2 text-center text-sm font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-4 focus:ring-red-300 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900"
              >
                Yes, delete
              </button>
            </div>
          </div>
        </div>
      </div>

      <div id="editReviewModal" tabindex="-1" aria-hidden="true" class="fixed left-0 right-0 top-0 z-50 hidden h-[calc(100%-1rem)] max-h-full w-full items-center justify-center overflow-y-auto overflow-x-hidden md:inset-0 antialiased">
        <div class="relative max-h-full w-full max-w-2xl p-4">
          <!-- Modal content -->
          <div class="relative rounded-lg bg-white shadow dark:bg-gray-800">
            <!-- Modal header -->
            <div class="flex items-center justify-between rounded-t border-b border-gray-200 p-4 dark:border-gray-700 md:p-5">
              <h3 class="mb-1 text-lg font-semibold text-gray-900 dark:text-white">Edit review</h3>
              <button
                type="button"
                class="absolute right-5 top-5 ms-auto inline-flex h-8 w-8 items-center justify-center rounded-lg bg-transparent text-sm text-gray-400 hover:bg-gray-200 hover:text-gray-900 dark:hover:bg-gray-600 dark:hover:text-white"
                data-modal-toggle="editReviewModal"
              >
                <svg class="h-3 w-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                  <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                </svg>
                <span class="sr-only">Close modal</span>
              </button>
            </div>
            <!-- Modal body -->
            <form class="p-4 md:p-5">
              <div class="mb-4 grid grid-cols-2 gap-4">
                <div class="col-span-2">
                  <div class="flex items-center">
                    <svg class="h-6 w-6 text-yellow-300" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 22 20">
                      <path
                        d="M20.924 7.625a1.523 1.523 0 0 0-1.238-1.044l-5.051-.734-2.259-4.577a1.534 1.534 0 0 0-2.752 0L7.365 5.847l-5.051.734A1.535 1.535 0 0 0 1.463 9.2l3.656 3.563-.863 5.031a1.532 1.532 0 0 0 2.226 1.616L11 17.033l4.518 2.375a1.534 1.534 0 0 0 2.226-1.617l-.863-5.03L20.537 9.2a1.523 1.523 0 0 0 .387-1.575Z"
                      />
                    </svg>
                    <svg class="ms-2 h-6 w-6 text-yellow-300" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 22 20">
                      <path
                        d="M20.924 7.625a1.523 1.523 0 0 0-1.238-1.044l-5.051-.734-2.259-4.577a1.534 1.534 0 0 0-2.752 0L7.365 5.847l-5.051.734A1.535 1.535 0 0 0 1.463 9.2l3.656 3.563-.863 5.031a1.532 1.532 0 0 0 2.226 1.616L11 17.033l4.518 2.375a1.534 1.534 0 0 0 2.226-1.617l-.863-5.03L20.537 9.2a1.523 1.523 0 0 0 .387-1.575Z"
                      />
                    </svg>
                    <svg class="ms-2 h-6 w-6 text-yellow-300" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 22 20">
                      <path
                        d="M20.924 7.625a1.523 1.523 0 0 0-1.238-1.044l-5.051-.734-2.259-4.577a1.534 1.534 0 0 0-2.752 0L7.365 5.847l-5.051.734A1.535 1.535 0 0 0 1.463 9.2l3.656 3.563-.863 5.031a1.532 1.532 0 0 0 2.226 1.616L11 17.033l4.518 2.375a1.534 1.534 0 0 0 2.226-1.617l-.863-5.03L20.537 9.2a1.523 1.523 0 0 0 .387-1.575Z"
                      />
                    </svg>
                    <svg class="ms-2 h-6 w-6 text-gray-300 dark:text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 22 20">
                      <path
                        d="M20.924 7.625a1.523 1.523 0 0 0-1.238-1.044l-5.051-.734-2.259-4.577a1.534 1.534 0 0 0-2.752 0L7.365 5.847l-5.051.734A1.535 1.535 0 0 0 1.463 9.2l3.656 3.563-.863 5.031a1.532 1.532 0 0 0 2.226 1.616L11 17.033l4.518 2.375a1.534 1.534 0 0 0 2.226-1.617l-.863-5.03L20.537 9.2a1.523 1.523 0 0 0 .387-1.575Z"
                      />
                    </svg>
                    <svg class="ms-2 h-6 w-6 text-gray-300 dark:text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 22 20">
                      <path
                        d="M20.924 7.625a1.523 1.523 0 0 0-1.238-1.044l-5.051-.734-2.259-4.577a1.534 1.534 0 0 0-2.752 0L7.365 5.847l-5.051.734A1.535 1.535 0 0 0 1.463 9.2l3.656 3.563-.863 5.031a1.532 1.532 0 0 0 2.226 1.616L11 17.033l4.518 2.375a1.534 1.534 0 0 0 2.226-1.617l-.863-5.03L20.537 9.2a1.523 1.523 0 0 0 .387-1.575Z"
                      />
                    </svg>
                    <span class="ms-2 text-lg font-bold text-gray-900 dark:text-white">3.0 out of 5</span>
                  </div>
                </div>
                <div class="col-span-2">
                  <label for="title" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Review title</label>
                  <input
                    type="text"
                    name="title"
                    id="title"
                    class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-600 focus:ring-primary-600 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder:text-gray-400 dark:focus:border-primary-500 dark:focus:ring-primary-500"
                    required=""
                  />
                </div>
                <div class="col-span-2">
                  <label for="description" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Review description</label>
                  <textarea
                    id="description"
                    rows="6"
                    class="mb-2 block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder:text-gray-400 dark:focus:border-primary-500 dark:focus:ring-primary-500"
                    required=""
                  ></textarea>
                  <p class="ms-auto text-xs text-gray-500 dark:text-gray-400">Problems with the product or delivery? <a href="#" class="text-primary-600 hover:underline dark:text-primary-500">Send a report</a>.</p>
                </div>
                <div class="col-span-2">
                  <div class="flex items-center">
                    <input
                      id="review-checkbox"
                      type="checkbox"
                      value=""
                      class="h-4 w-4 rounded border-gray-300 bg-gray-100 text-primary-600 focus:ring-2 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700 dark:ring-offset-gray-800 dark:focus:ring-primary-600"
                    />
                    <label for="review-checkbox" class="ms-2 text-sm font-medium text-gray-500 dark:text-gray-400"
                      >By publishing this review you agree with the <a href="#" class="text-primary-600 hover:underline dark:text-primary-500">terms and conditions</a>.</label
                    >
                  </div>
                </div>
              </div>
              <div class="border-t border-gray-200 pt-4 dark:border-gray-700 md:pt-5">
                <button
                  type="submit"
                  class="me-2 inline-flex items-center rounded-lg bg-primary-700 px-5 py-2.5 text-center text-sm font-medium text-white hover:bg-primary-800 focus:outline-none focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800"
                >
                  Edit review
                </button>
                <button
                  type="button"
                  data-modal-toggle="editReviewModal"
                  class="me-2 rounded-lg border border-gray-200 bg-white px-5 py-2.5 text-sm font-medium text-gray-900 hover:bg-gray-100 hover:text-primary-700 focus:z-10 focus:outline-none focus:ring-4 focus:ring-gray-100 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white dark:focus:ring-gray-700"
                >
                  Cancel
                </button>
              </div>
            </form>
          </div>
        </div>
      </div> --}}
    </div>
  </div>
  <!-- deskripsi & reviews -->
@include('layouts.footer')