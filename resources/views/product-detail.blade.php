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

                    // Hapus kelas 'selected' dari semua tombol
                        document.querySelectorAll('.variant-button').forEach(btn => {
                            btn.classList.remove('selected');
                        });

                        // Tambahkan kelas 'selected' ke tombol yang diklik
                        button.classList.add('selected');

                        // Panggil showVariant untuk mengganti gambar utama dan stok
                        showVariant(images, stock, button);
                    };

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
                            Swal.fire({
                                icon: 'success',
                                title: 'Sukses!',
                                text: data.message,
                            });
                            updateCartItemCount(); // Perbarui jumlah item di ikon cart
                        })
                        .catch((error) => {
                            console.error("Error:", error);
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: error.message,
                            });
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
                </script>

                <div class="space-y-4">
                <!-- Colour Options -->
                @if ($variants->isNotEmpty())
                    <div>
                        <h3 class="text-lg font-semibold mb-3">Colour</h3>
                        <div class="flex flex-wrap gap-2">
                            @foreach ($variants as $variant)
                                <button
                                    type="button"
                                    class="variant-button font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 focus:outline-none focus:ring-4 transition-all duration-200 ease-in-out shadow-md hover:shadow-lg @if ($loop->first) selected @endif"
                                    style="background-color: {{ $variant->color }}; color: {{ getContrastColor($variant->color) }}; border-color: {{ $variant->color }};"
                                    data-variant-id="{{ $variant->id }}"
                                    data-images='{{ json_encode($variant->images) }}'
                                    data-stock="{{ $variant->stock }}"
                                    onclick="selectVariant(this, {{ $variant->id }}, '{{ json_encode($variant->images) }}', {{ $variant->stock }})"
                                    @if ($loop->first) id="default-variant" @endif
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
                    <div class="space-y-6">
                        <!-- Shopee Button -->
                        @if ($shopeeUrl !== '#')
                            <a href="{{ $shopeeUrl }}" target="_blank" class="rounded-lg text-sm font-medium text-gray-700 bg-gray-100 inline-flex items-center justify-center hover:bg-gray-200 focus:outline-none focus:ring-4 focus:ring-gray-300 transition duration-150 ease-in-out">
                                <!-- Gambar berwarna jika tautan tersedia -->
                                <img src="{{ asset('img/shopee.png') }}" alt="Shopee" class="w-12 h-8" />
                            </a>
                        @else
                            <button class=" rounded-lg text-sm font-medium text-gray-500 bg-gray-200 cursor-not-allowed inline-flex items-center justify-center" disabled>
                                <!-- Gambar abu-abu jika tautan tidak tersedia -->
                                <img src="{{ asset('img/shopee.png') }}" alt="Shopee" class="w-12 h-8 grayscale" />
                            </button>
                        @endif

                        <!-- Lazada Button -->
                        @if ($lazadaUrl !== '#')
                            <a href="{{ $lazadaUrl }}" target="_blank" class="rounded-lg text-sm font-medium text-gray-700 bg-gray-100 inline-flex items-center justify-center hover:bg-gray-200 focus:outline-none focus:ring-4 focus:ring-gray-300 transition duration-150 ease-in-out">
                                <!-- Gambar berwarna jika tautan tersedia -->
                                <img src="{{ asset('img/lazada.jpeg') }}" alt="Lazada" class="w-8 h-8" />
                            </a>
                        @else
                            <button class="rounded-lg text-sm font-medium text-gray-500 bg-gray-200 cursor-not-allowed inline-flex items-center justify-center" disabled>
                                <!-- Gambar abu-abu jika tautan tidak tersedia -->
                                <img src="{{ asset('img/lazada.jpeg') }}" alt="Lazada" class="w-12 h-8 grayscale" />
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
    <section class="py-8 bg-inherit dark:bg-gray-900 antialiased">
        <div class="max-w-screen-xl px-4 mx-auto 2xl:px-0">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8"> <!-- Grid dengan 2 kolom -->
                <!-- Deskripsi Produk (Kiri) -->
                <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300">
                    <h3 class="text-2xl font-semibold mb-4 flex items-center">
                        <svg class="w-6 h-6 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Deskripsi Produk
                    </h3>
                    <div class="prose max-w-none text-gray-700">
                        {!! $product->description !!}
                    </div>
                    <!-- Tombol "Lihat Selengkapnya" untuk deskripsi panjang -->
                    <button id="readMoreBtn" class="text-blue-500 hover:text-blue-700 mt-4 focus:outline-none">
                        Lihat Selengkapnya
                    </button>
                </div>
    
                <!-- Ulasan Produk (Kanan) -->
                <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300" id="produkReview">
                    <h3 class="text-2xl font-semibold mb-4 flex items-center">
                        <svg class="w-6 h-6 text-yellow-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.196-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                        </svg>
                        Ulasan Produk
                    </h3>
    
                    <!-- Rating Rata-rata -->
                    <div class="flex items-center mb-6 p-4 bg-gray-50 rounded-lg">
                        <span class="text-3xl font-bold mr-2 text-gray-800">{{ number_format($product->average_rating, 1) }}</span>
                        <div class="flex items-center">
                            @for ($i = 1; $i <= 5; $i++)
                                @if ($i <= $product->average_rating)
                                    <svg class="w-6 h-6 text-yellow-400" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M13.849 4.22c-.684-1.626-3.014-1.626-3.698 0L8.397 8.387l-4.552.361c-1.775.14-2.495 2.331-1.142 3.477l3.468 2.937-1.06 4.392c-.413 1.713 1.472 3.067 2.992 2.149L12 19.35l3.897 2.354c1.52.918 3.405-.436 2.992-2.15l-1.06-4.39 3.468-2.938c1.353-1.146.633-3.336-1.142-3.477l-4.552-.36-1.754-4.17Z" />
                                    </svg>
                                @else
                                    <svg class="w-6 h-6 text-gray-300" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M13.849 4.22c-.684-1.626-3.014-1.626-3.698 0L8.397 8.387l-4.552.361c-1.775.14-2.495 2.331-1.142 3.477l3.468 2.937-1.06 4.392c-.413 1.713 1.472 3.067 2.992 2.149L12 19.35l3.897 2.354c1.52.918 3.405-.436 2.992-2.15l-1.06-4.39 3.468-2.938c1.353-1.146.633-3.336-1.142-3.477l-4.552-.36-1.754-4.17Z" />
                                    </svg>
                                @endif
                            @endfor
                        </div>
                        <span class="text-sm text-gray-600 ml-2">({{ $product->total_reviews ?? 0 }} ulasan)</span>
                    </div>
    
                    <!-- Daftar Ulasan -->
                    <div class="space-y-6">
                        @if ($product->reviews->isEmpty())
                            <div class="text-center py-8">
                                <svg class="w-16 h-16 mx-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <p class="text-gray-600 mt-4">Belum ada ulasan untuk produk ini.</p>
                            </div>
                        @else
                            @foreach ($product->reviews as $review)
                                <div class="border-b border-gray-200 pb-4 hover:bg-gray-50 transition-colors duration-200 p-4 rounded-lg">
                                    <div class="flex items-center mb-2">
                                        <div class="w-10 h-10 bg-gray-200 rounded-full flex items-center justify-center mr-3">
                                            <span class="text-gray-600 font-semibold">{{ strtoupper(substr($review->user->first_name, 0, 1)) }}</span>
                                        </div>
                                        <div>
                                            <span class="font-semibold">{{ $review->user->first_name }}</span>
                                            <div class="flex items-center mt-1">
                                                @for ($i = 1; $i <= 5; $i++)
                                                    @if ($i <= $review->rating)
                                                        <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 24 24">
                                                            <path d="M13.849 4.22c-.684-1.626-3.014-1.626-3.698 0L8.397 8.387l-4.552.361c-1.775.14-2.495 2.331-1.142 3.477l3.468 2.937-1.06 4.392c-.413 1.713 1.472 3.067 2.992 2.149L12 19.35l3.897 2.354c1.52.918 3.405-.436 2.992-2.15l-1.06-4.39 3.468-2.938c1.353-1.146.633-3.336-1.142-3.477l-4.552-.36-1.754-4.17Z" />
                                                        </svg>
                                                    @else
                                                        <svg class="w-4 h-4 text-gray-300" fill="currentColor" viewBox="0 0 24 24">
                                                            <path d="M13.849 4.22c-.684-1.626-3.014-1.626-3.698 0L8.397 8.387l-4.552.361c-1.775.14-2.495 2.331-1.142 3.477l3.468 2.937-1.06 4.392c-.413 1.713 1.472 3.067 2.992 2.149L12 19.35l3.897 2.354c1.52.918 3.405-.436 2.992-2.15l-1.06-4.39 3.468-2.938c1.353-1.146.633-3.336-1.142-3.477l-4.552-.36-1.754-4.17Z" />
                                                        </svg>
                                                    @endif
                                                @endfor
                                            </div>
                                        </div>
                                    </div>
                                    <p class="text-gray-600">{{ $review->review }}</p>
                                    <p class="text-sm text-gray-500 mt-2">{{ $review->created_at->format('d M Y') }}</p>
                                </div>
                            @endforeach
                        @endif
                    </div>
    
                    <!-- Tombol "Load More" untuk ulasan -->
                    <div class="mt-6 text-center">
                        <button class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 focus:outline-none">
                            Muat Lebih Banyak
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>
  <!-- deskripsi & reviews -->

  <script>
    document.addEventListener("DOMContentLoaded", function () {
      const readMoreBtn = document.getElementById("readMoreBtn");
      const description = document.querySelector(".prose");

      if (description.scrollHeight > description.clientHeight) {
          description.parentElement.classList.add("show-read-more");
      }

      readMoreBtn.addEventListener("click", function () {
          description.classList.toggle("line-clamp-none");
          readMoreBtn.textContent = description.classList.contains("line-clamp-none")
              ? "Sembunyikan"
              : "Lihat Selengkapnya";
      });
    });
  </script>
  <script src="{{ asset('js/frontend/detail-product.js') }}"></script>
@include('layouts.footer')