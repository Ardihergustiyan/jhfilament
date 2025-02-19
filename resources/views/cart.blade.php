@include('layouts.header') 
@include('components.navbar')

<section class="bg-white py-8 antialiased dark:bg-gray-900 md:py-16">
    <div class="mx-auto max-w-screen-xl px-4 2xl:px-0">
        <!-- alur -->
        <div class="py-8">
          <ol class="items-center flex w-full max-w-2xl text-center text-sm font-medium text-gray-500 dark:text-gray-400 sm:text-base">
            <li class="after:border-1 flex items-center text-primary-700 after:mx-6 after:hidden after:h-1 after:w-full after:border-b after:border-gray-200 dark:text-primary-500 dark:after:border-gray-700 sm:after:inline-block sm:after:content-[''] md:w-full xl:after:mx-10">
              <span class="flex items-center after:mx-2 after:text-gray-200 after:content-['/'] dark:after:text-gray-500 sm:after:hidden">
                <svg class="me-2 h-4 w-4 sm:h-5 sm:w-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                  <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.5 11.5 11 14l4-4m6 2a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
                Cart
              </span>
            </li>
      
            <li class="after:border-1 flex items-center  after:mx-6 after:hidden after:h-1 after:w-full after:border-b after:border-gray-200  dark:after:border-gray-700 sm:after:inline-block sm:after:content-[''] md:w-full xl:after:mx-10">
              <span class="flex items-center after:mx-2 after:text-gray-200 after:content-['/'] dark:after:text-gray-500 sm:after:hidden">
                <svg class="me-2 h-4 w-4 sm:h-5 sm:w-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                  <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.5 11.5 11 14l4-4m6 2a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
                Checkout
              </span>
            </li>
      
            <li class="flex shrink-0 items-center">
              <svg class="me-2 h-4 w-4 sm:h-5 sm:w-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.5 11.5 11 14l4-4m6 2a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
              </svg>
              Confirmation
            </li>
          </ol>
        </div>

            <h2 class="text-xl font-semibold text-gray-900 dark:text-white sm:text-2xl">Shopping Cart</h2>
            <!-- cart kosong -->
            @if ($isCartEmpty)
            <div class="mt-6 sm:mt-8 md:gap-6 lg:flex lg:items-start xl:gap-8">
                <div class="mx-auto w-full flex-none lg:max-w-2xl xl:max-w-4xl">
                    <div class="space-y-6 text-center py-16">
            
                        <!-- Ikon Keranjang Kosong -->
                        <svg
                            class="mx-auto h-24 w-24 text-gray-400 dark:text-gray-500"
                            xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        >
                            <path d="M5 4h2l1 9h10l1-9h2M16 12v8M8 12v8M10 18h4" />
                        </svg>
            
                        <!-- Teks Keranjang Kosong -->
                        <h2 class="mt-6 text-lg font-semibold text-gray-900 dark:text-white">
                            Keranjang Anda saat ini kosong
                        </h2>
                        <p class="mt-2 text-base font-normal text-gray-500 dark:text-gray-400">
                            Sepertinya Anda belum menambahkan barang ke keranjang. Mulai belanja sekarang!
                        </p>
            
                        <!-- Tombol Lanjut Belanja -->
                        <a
                            href="/product" 
                            class="mt-6 inline-flex items-center justify-center rounded-lg bg-pink-400 px-6 py-3 text-sm font-medium text-white hover:bg-pink-500 focus:outline-none focus:ring-4 focus:ring-pink-200 dark:bg-pink-400 dark:hover:bg-pink-500 dark:focus:ring-pink-500"
                        >
                            Lanjut Belanja
                        </a>
                    </div>
                </div>
            </div>
            @else
            <div class="mt-6 sm:mt-8 md:gap-6 lg:flex lg:items-start xl:gap-8">
                <div class="mx-auto w-full flex-none lg:max-w-2xl xl:max-w-4xl">
                    <div class="space-y-6">
                        @foreach ($cartItems as $item)
                        <div class="rounded-lg border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-800 md:p-6">
                            <div class="space-y-4 md:flex md:items-center md:justify-between md:gap-6 md:space-y-0">
                                <!-- Product Image -->
                                <a href="{{ route('product.show', $item->product->slug) }}" class="shrink-0 md:order-1">
                                    @if($item->main_image)
                                        <img id="main-image-{{ $item->id }}" 
                                             class="h-20 w-20 rounded-lg object-cover" 
                                             src="{{ asset('storage/' . $item->main_image) }}" 
                                             alt="{{ $item->product_name }}" />
                                    @else
                                        @php
                                            $productImages = json_decode($item->product_image, true);
                                            $mainImage = $productImages[0] ?? null;
                                        @endphp
                                        @if($mainImage)
                                            <img id="main-image-{{ $item->id }}" 
                                                 class="h-20 w-20 rounded-lg object-cover" 
                                                 src="{{ asset('storage/' . $mainImage) }}" 
                                                 alt="{{ $item->product_name }}" />
                                        @else
                                            <img id="main-image-{{ $item->id }}" 
                                                 class="h-20 w-20 rounded-lg object-cover" 
                                                 src="{{ asset('path/to/default/image.jpg') }}" 
                                                 alt="{{ $item->product_name }}" />
                                        @endif
                                    @endif
                                </a>
                        
                                <!-- Product Details -->
                                <div class="w-full min-w-0 flex-1 space-y-2 md:order-2 md:max-w-md">
                                    <!-- Product Name -->
                                    <a href="{{ route('product.show', $item->product->slug) }}" class="text-lg font-semibold text-gray-900 hover:underline dark:text-white">
                                        {{ $item->product->name }}
                                    </a>
                        
                                    <!-- Product Description -->
                                    <p class="text-sm text-gray-600 dark:text-gray-400">
                                        {{ Str::words($item->product->description, 20, '...') }}
                                    </p>
                        
                                    <!-- Variant Selection -->
                                    <div class="flex items-center gap-4">
                                        @if (isset($variantsGrouped[$item->product_id]) && $variantsGrouped[$item->product_id]->isNotEmpty())
                                            <select id="variant-select-{{ $item->id }}" 
                                                    name="variant" 
                                                    onchange="updateVariant(this, {{ $item->id }})"
                                                    class="block rounded-md border border-gray-300 bg-white py-1 px-2 text-center text-sm font-medium text-gray-900 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                                                @foreach ($variantsGrouped[$item->product_id] as $variant)
                                                    <option value="{{ $variant->id }}" 
                                                            data-images="{{ str_replace('/storage/', '', Storage::url($variant->image[0] ?? 'default.jpg')) }}" 
                                                            data-stock="{{ $variant->stock }}"
                                                            {{ $item->variant_id == $variant->id ? 'selected' : '' }}>
                                                        {{ $variant->color }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        @endif
                        
                                        <!-- Remove Button -->
                                        <button type="button" class="inline-flex items-center text-sm font-medium text-red-600 hover:underline dark:text-red-500 ml-auto md:ml-0"
                                                onclick="removeCartItem({{ $item->id }})">
                                            <svg class="me-1.5 h-5 w-5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18 17.94 6M18 18 6.06 6" />
                                            </svg>
                                            Remove
                                        </button>
                                    </div>
                                </div>
                        
                                <!-- Quantity and Price -->
                                <div class="flex items-center justify-between md:order-3 md:justify-end">
                                    <!-- Quantity Control -->
                                    <div class="flex items-center">
                                        <button type="button" id="decrement-button-{{ $item->id }}" data-input-counter-decrement="counter-input-{{ $item->id }}" class="inline-flex h-5 w-5 shrink-0 items-center justify-center rounded-md border border-gray-300 bg-gray-100 hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-100 dark:border-gray-600 dark:bg-gray-700 dark:hover:bg-gray-600 dark:focus:ring-gray-700">
                                            <svg class="h-2.5 w-2.5 text-gray-900 dark:text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 2">
                                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 1h16" />
                                            </svg>
                                        </button>
                                        <input type="text" id="counter-input-{{ $item->id }}" data-input-counter data-item-id="{{ $item->id }}" data-price="{{ $item->final_price }}"  class="w-10 shrink-0 border-0 bg-transparent text-center text-sm font-medium text-gray-900 focus:outline-none focus:ring-0 dark:text-white" value="{{ $item->quantity }}" required />
                                        <button type="button" id="increment-button-{{ $item->id }}" data-input-counter-increment="counter-input-{{ $item->id }}" class="inline-flex h-5 w-5 shrink-0 items-center justify-center rounded-md border border-gray-300 bg-gray-100 hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-100 dark:border-gray-600 dark:bg-gray-700 dark:hover:bg-gray-600 dark:focus:ring-gray-700">
                                            <svg class="h-2.5 w-2.5 text-gray-900 dark:text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 18">
                                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 1v16M1 9h16" />
                                            </svg>
                                        </button>
                                    </div>
                        
                                    <!-- Price -->
                                    <div class="text-end md:order-4 md:w-32">
                                        <p class="text-base font-bold text-gray-900 dark:text-white">Rp{{ number_format($item->discounted_price ?? $item->final_price, 0, ',', '.') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
               
               
                
                
                <div class="mx-auto mt-6 max-w-4xl flex-1 space-y-6 lg:mt-0 lg:w-full">
                    <div class="space-y-4 rounded-lg border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-800 sm:p-6">
                        <p class="text-xl font-semibold text-gray-900 dark:text-white">Ringkasan Belanja</p>
                        <div class="space-y-4">
                            <div class="space-y-2">
                                <!-- Subtotal -->
                                <dl class="flex items-center justify-between gap-4">
                                    <dt class="text-base font-normal text-gray-500 dark:text-gray-400">Subtotal</dt>
                                    <dd class="text-base font-medium text-gray-900 dark:text-white" id="subtotal-display">
                                        Rp {{ number_format($subtotal, 0, ',', '.') }}
                                    </dd>
                                </dl>

                                <!-- Diskon Voucher -->
                                <dl class="flex items-center justify-between gap-4">
                                    <dt class="text-base font-normal text-gray-500 dark:text-gray-400">Voucher Diskon</dt>
                                    <dd class="text-base font-medium text-green-600" id="voucher-display">
                                        -Rp {{ number_format($voucherAmount, 0, ',', '.') }} 
                                    </dd>
                                </dl>

                                <!-- Total -->
                                <dl class="flex items-center justify-between gap-4 border-t border-gray-200 pt-2 dark:border-gray-700">
                                    <dt class="text-base font-bold text-gray-900 dark:text-white">Total</dt>
                                    <dd class="text-base font-bold text-gray-900 dark:text-white" id="total-display">
                                        Rp {{ number_format($total, 0, ',', '.') }}
                                    </dd>
                                </dl>
                            </div>
                        </div>

    
                    <form action="{{ route('cart.proceed-to-checkout') }}" method="POST">
                        @csrf
                        <button type="submit" class="flex w-full items-center justify-center rounded-lg bg-pink-400 px-4 py-2 text-sm font-medium text-white hover:bg-pink-500 focus:outline-none focus:ring-4 focus:ring-pink-200 dark:bg-pink-400 dark:hover:bg-pink-500 dark:focus:ring-pink-500">
                            Proses Pembayaran
                        </button>
                    </form>
                    
                    <div class="flex items-center justify-center gap-2">
                        <span class="text-sm font-normal text-gray-500 dark:text-gray-400"> atau </span>
                        <a href="product" title="" class="inline-flex items-center gap-2 text-sm font-medium text-primary-700 underline hover:no-underline dark:text-primary-500">
                        Kembali Berbelanja
                        <svg class="h-5 w-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 12H5m14 0-4 4m4-4-4-4" />
                        </svg>
                        </a>
                    </div>
                    <form id="voucher-form" class="space-y-4">
                        <div>
                            <label for="voucher" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white"> Masukkan Voucher </label>
                            <input
                                type="text"
                                id="voucher-input"
                                class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder:text-gray-400 dark:focus:border-primary-500 dark:focus:ring-primary-500"
                                required
                            />
                        </div>
                        <button
                            type="submit"
                            class="flex w-full items-center justify-center rounded-lg bg-pink-400 px-4 py-2 text-sm font-medium text-white hover:bg-pink-500 focus:outline-none focus:ring-4 focus:ring-pink-200 dark:bg-pink-400 dark:hover:bg-pink-500 dark:focus:ring-pink-500"
                        >
                            Pakai
                        </button>
                    </form>
                    <div id="voucher-message" class="mt-2 text-sm text-red-600"></div>
                    </div>
                    <script>
                        function updateVariant(selectElement, cartItemId) {
                            const variantId = selectElement.value;
                            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

                            if (!csrfToken) {
                                console.error('CSRF token not found!');
                                return;
                            }

                            fetch(`/cart/${cartItemId}/update`, {
                                method: 'PATCH',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': csrfToken
                                },
                                body: JSON.stringify({ variant_id: variantId })
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    alert('Varian berhasil diperbarui!');

                                    // Perbarui gambar produk secara dinamis
                                    const selectedOption = selectElement.options[selectElement.selectedIndex];
                                    const imagePath = selectedOption.getAttribute('data-images');
                                    
                                    if (imagePath) {
                                        const newImageUrl = imagePath.startsWith('/storage/') ? imagePath : `/storage/${imagePath}`;
                                        const productImage = document.getElementById(`main-image-${cartItemId}`);
                                        if (productImage) {
                                            productImage.src = newImageUrl;
                                            console.log('Gambar diperbarui:', newImageUrl);
                                        }
                                    }
                                } else {
                                    alert('Gagal memperbarui varian. ' + (data.message || ''));
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                alert('Terjadi kesalahan saat memperbarui varian.');
                            });
                        }

                        window.addEventListener('pageshow', function(event) {
                            // Periksa apakah halaman dimuat dari cache
                            if (event.persisted) {
                                // Muat ulang halaman untuk memastikan data terbaru
                                location.reload();
                            }
                        });
                        
                        function updateQuantity(inputElement, cartItemId) {
                            const quantity = parseInt(inputElement.value) || 1;
                            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

                            if (!csrfToken) {
                                console.error('CSRF token not found!');
                                return;
                            }

                            fetch(`/cart/${cartItemId}/update`, {
                                method: 'PATCH',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': csrfToken
                                },
                                body: JSON.stringify({ quantity: quantity })
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    console.log('Berhasil menambah jumlah');
                                    // Perbarui subtotal, diskon, dan total tanpa refresh
                                    updateCartSummary(data.subtotal, data.voucherAmount, data.total);
                                    // Memuat ulang halaman untuk memastikan data terbaru
                                    location.reload();
                                } else {
                                    alert('Gagal memperbarui jumlah. ' + (data.message || ''));
                                    window.location.reload(); // Refresh halaman
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                alert('Terjadi kesalahan saat memperbarui jumlah.');
                            });
                        }

                        function updateCartSummary(subtotal, voucherAmount, total) {
                            // Perbarui subtotal
                            const subtotalDisplay = document.getElementById('subtotal-display');
                            if (subtotalDisplay) {
                                subtotalDisplay.textContent = `Rp ${new Intl.NumberFormat('id-ID').format(subtotal)}`;
                            }

                            // Perbarui diskon
                            const voucherDisplay = document.getElementById('voucher-display');
                            if (voucherDisplay) {
                                voucherDisplay.textContent = `-Rp ${new Intl.NumberFormat('id-ID').format(voucherAmount)}`;
                            }

                            // Perbarui total
                            const totalDisplay = document.getElementById('total-display');
                            if (totalDisplay) {
                                totalDisplay.textContent = `Rp ${new Intl.NumberFormat('id-ID').format(total)}`;
                            }
                        }

                        // function updateCartSummaryWithVoucher(voucherPercentage) {
                        //     // Ambil data terbaru dari server
                        //     fetch('/cart/data') // Buat endpoint untuk mengambil data cart terbaru
                        //         .then(response => response.json())
                        //         .then(data => {
                        //             const subtotal = data.subtotal;
                        //             const voucherAmount = (subtotal * voucherPercentage) / 100;
                        //             const total = subtotal - voucherAmount;

                        //             // Perbarui tampilan
                        //             updateCartSummary(subtotal, voucherAmount, total);
                        //         })
                        //         .catch(error => console.error('Error:', error));
                        // }
                        
                        // function updateCartSummaryWithVoucher(voucherPercentage) {
                        //     const subtotalDisplay = document.getElementById("subtotal-display");
                        //     const totalDisplay = document.getElementById("total-display");
                        //     const voucherDisplay = document.getElementById("voucher-display");

                        //     if (subtotalDisplay && totalDisplay && voucherDisplay) {
                        //         const subtotal = parseFloat(subtotalDisplay.textContent.replace(/[^0-9]/g, "")) || 0;
                        //         const voucherAmount = (subtotal * voucherPercentage) / 100;
                        //         const total = subtotal - voucherAmount;

                        //         // Perbarui tampilan voucher dan total
                        //         voucherDisplay.textContent = `-Rp ${new Intl.NumberFormat("id-ID").format(voucherAmount)}`;
                        //         totalDisplay.textContent = `Rp ${new Intl.NumberFormat("id-ID").format(total)}`;
                        //     }
                        // }

                        function updateCartSummaryWithVoucher(voucherPercentage, useServerData = false) {
                            if (useServerData) {
                                // Ambil data terbaru dari server
                                fetch('/cart/data')
                                    .then(response => response.json())
                                    .then(data => {
                                        const subtotal = data.subtotal;
                                        const voucherAmount = (subtotal * voucherPercentage) / 100;
                                        const total = subtotal - voucherAmount;

                                        // Perbarui tampilan
                                        updateCartSummary(subtotal, voucherAmount, total);
                                    })
                                    .catch(error => console.error('Error:', error));
                            } else {
                                // Gunakan data yang sudah ada di halaman
                                const subtotalDisplay = document.getElementById("subtotal-display");
                                const totalDisplay = document.getElementById("total-display");
                                const voucherDisplay = document.getElementById("voucher-display");

                                if (subtotalDisplay && totalDisplay && voucherDisplay) {
                                    const subtotal = parseFloat(subtotalDisplay.textContent.replace(/[^0-9]/g, "")) || 0;
                                    const voucherAmount = (subtotal * voucherPercentage) / 100;
                                    const total = subtotal - voucherAmount;

                                    // Perbarui tampilan voucher dan total
                                    voucherDisplay.textContent = `-Rp ${new Intl.NumberFormat("id-ID").format(voucherAmount)}`;
                                    totalDisplay.textContent = `Rp ${new Intl.NumberFormat("id-ID").format(total)}`;
                                }
                            }
                        }
                        
                        document.addEventListener("DOMContentLoaded", function () {
                            document.body.addEventListener("click", function (event) {
                                // Check for decrement button click
                                if (event.target.closest("[data-input-counter-decrement]")) {
                                    const decrementButton = event.target.closest("[data-input-counter-decrement]");
                                    const counterInputId = decrementButton.getAttribute("data-input-counter-decrement");
                                    const counterInput = document.getElementById(counterInputId);
                                    updateCounter(counterInput, -1);
                                }
    
                                // Check for increment button click
                                if (event.target.closest("[data-input-counter-increment]")) {
                                    const incrementButton = event.target.closest("[data-input-counter-increment]");
                                    const counterInputId = incrementButton.getAttribute("data-input-counter-increment");
                                    const counterInput = document.getElementById(counterInputId);
                                    updateCounter(counterInput, 1);
                                }
                            });
    
                            function updateCounter(inputElement, change) {
                                if (!inputElement) return;
                                let currentValue = parseInt(inputElement.value) || 0;
                                
    
                                if (currentValue < 1) currentValue = 1; // Ensure minimum value of 1
                                inputElement.value = currentValue;
    
                                let itemId = inputElement.dataset.itemId;
                                updateCartInDatabase(itemId, currentValue);
                                updateQuantity(inputElement, itemId);

                            }
    
                            function updateCartInDatabase(itemId, quantity) {

                                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                                fetch(`/cart/update/${itemId}`, {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': csrfToken,
                                    },
                                    body: JSON.stringify({ quantity: quantity }),
                                })
                                .then(response => response.json())
                                .then(data => {
                                    if (data.success) {
                                        console.log('Cart updated successfully');
                                    } else {
                                        console.error('Failed to update cart');
                                    }
                                })
                                .catch(error => console.error('Error:', error));
                            }
    
                            // Validasi input agar hanya angka positif
                            document.body.addEventListener("input", function (event) {
                                if (event.target.hasAttribute("data-input-counter")) {
                                    let inputElement = event.target;
                                    let sanitizedValue = parseInt(inputElement.value.replace(/\D/g, '')) || 1;
                                    if (sanitizedValue < 1) sanitizedValue = 1; // Minimal adalah 1
                                    inputElement.value = sanitizedValue;
                                }
                            });
    
                        });

                        function removeCartItem(cartId) {
                            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

                            if (!csrfToken) {
                                console.error('CSRF token not found!');
                                return;
                            }

                            fetch(`/cart/${cartId}`, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': csrfToken,
                                    'Content-Type': 'application/json'
                                }
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.message) {
                                    alert(data.message);
                                    location.reload();
                                }
                            })
                            .catch(error => console.error('Error:', error));
                        }

                        document.addEventListener("DOMContentLoaded", function () {
                            const voucherForm = document.getElementById("voucher-form");
                            const voucherInput = document.getElementById("voucher-input");
                            const voucherMessage = document.getElementById("voucher-message");

                            if (voucherForm) {
                                voucherForm.addEventListener("submit", function (event) {
                                    event.preventDefault(); // Mencegah form submit default

                                    const voucherCode = voucherInput.value.trim();
                                    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

                                    if (!csrfToken) {
                                        console.error('CSRF token not found!');
                                        return;
                                    }

                                    // Kirim request ke server untuk validasi voucher
                                    fetch("/validate-voucher", {
                                        method: "POST",
                                        headers: {
                                            "Content-Type": "application/json",
                                            "X-CSRF-TOKEN": csrfToken,
                                        },
                                        body: JSON.stringify({ code: voucherCode }),
                                    })
                                    .then(response => response.json())
                                    .then(data => {
                                        if (data.valid) {
                                            // Jika voucher valid, perbarui tampilan dan hitung ulang total
                                            voucherMessage.textContent = "Voucher berhasil diterapkan!";
                                            voucherMessage.classList.remove("text-red-600");
                                            voucherMessage.classList.add("text-green-600");

                                            // Perbarui subtotal, voucher, dan total
                                            updateCartSummaryWithVoucher(data.voucher);
                                        } else {
                                            // Jika voucher tidak valid, tampilkan pesan error
                                            voucherMessage.textContent = data.message || "Voucher tidak valid.";
                                            voucherMessage.classList.remove("text-green-600");
                                            voucherMessage.classList.add("text-red-600");
                                        }
                                    })
                                    .catch(error => {
                                        console.error("Error:", error);
                                        voucherMessage.textContent = "Terjadi kesalahan saat memvalidasi voucher.";
                                        voucherMessage.classList.remove("text-green-600");
                                        voucherMessage.classList.add("text-red-600");
                                    });
                                });
                            }
                        });

                        
                    </script>
                </div>
            </div>
            @endif
      </div>
</section>

@include('layouts.footer')
