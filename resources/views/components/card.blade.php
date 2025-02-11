<div class="swiper-slide rounded-lg border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-800">
  <div class="h-40 w-full">
    <a href="{{ route('product.show', $product->slug) }}">
      <img class="mx-auto h-full" src="{{ asset('storage/' . $product->main_image) }}" alt="{{ $product->name }}" />
    </a>
  </div>
  <div class="pt-4">
    <div class="mb-3 flex items-center justify-between gap-4">
      <span class="rounded bg-pink-100 px-2.5 py-0.5 text-xs font-medium text-pink-800 dark:bg-pink-900 dark:text-pink-300">Up to 35% off</span>
    </div>

    <a href="{{ route('product.show', $product->slug) }}" class="text-lg font-semibold leading-tight text-gray-900 hover:underline dark:text-white">{{ $product->name }}</a>

    <div class="mt-2 flex items-center gap-2">
      <div class="flex items-center">
        <svg class="h-4 w-4 text-yellow-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
          <path d="M13.8 4.2a2 2 0 0 0-3.6 0L8.4 8.4l-4.6.3a2 2 0 0 0-1.1 3.5l3.5 3-1 4.4c-.5 1.7 1.4 3 2.9 2.1l3.9-2.3 3.9 2.3c1.5 1 3.4-.4 3-2.1l-1-4.4 3.4-3a2 2 0 0 0-1.1-3.5l-4.6-.3-1.8-4.2Z" />
        </svg>
        <!-- Ulangi ikon bintang sesuai kebutuhan -->
      </div>
      
      <p class="text-sm font-medium text-gray-900 dark:text-white">
        @if ($product->total_reviews > 0)
            {{ number_format($product->average_rating, 1) }}
        @else
            Belum ada ulasan
        @endif
    </p>
    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">
        @if ($product->total_reviews > 0)
            ({{ $product->total_reviews }})
        @endif
    </p>
      
    </div>

    <div class="mt-4 flex items-center justify-between gap-1">
      <div>
        <p class="text-sm text-gray-500 dark:text-gray-400"><del>Rp{{ number_format($product->base_price, 0, ',', '.') }},00</del></p>
        <p class="text-lg font-extrabold leading-tight text-gray-900 dark:text-white">Rp{{ number_format($product->het_price, 0, ',', '.') }},00</p>
      </div>
      @if (Auth::check())
          <button
              type="button"
              class="inline-flex z-10 items-center justify-center rounded-full bg-pink-300 p-2 text-sm font-medium text-gray-800 hover:bg-pink-400 focus:outline-none focus:ring-4 focus:ring-pink-200 dark:bg-pink-400 dark:hover:bg-pink-500 dark:focus:ring-pink-500"
              onclick="addToCart({{ $product->id }})" 
              data-product-id="{{ $product->id }}" 
              data-variant-id="{{ $product->variants->first()->id ?? null }}" 
              data-has-variants="{{ $product->variants->isNotEmpty() ? 'true' : 'false' }}">
          
              <svg class="h-5 w-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                  <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4h1.5L8 16m0 0h8m-8 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4Zm8 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4Zm.75-3H7.5M11 7H6.312M17 4v6m-3-3h6" />
              </svg>
          </button>
      @else
          <a href="/login"
              class="inline-flex z-10 items-center justify-center rounded-full bg-pink-300 p-2 text-sm font-medium text-gray-800 hover:bg-pink-400 focus:outline-none focus:ring-4 focus:ring-pink-200 dark:bg-pink-400 dark:hover:bg-pink-500 dark:focus:ring-pink-500"
          >
              <svg class="h-5 w-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                  <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4h1.5L8 16m0 0h8m-8 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4Zm8 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4Zm.75-3H7.5M11 7H6.312M17 4v6m-3-3h6" />
              </svg>
          </a>
      @endif

    </div>
  </div>
</div>

<script>
  // function addToCart(productId) {
  //     fetch('{{ route('cart.add') }}', {
  //         method: 'POST',
  //         headers: {
  //             'Content-Type': 'application/json',
  //             'X-CSRF-TOKEN': '{{ csrf_token() }}',
  //         },
  //         body: JSON.stringify({
  //             product_id: productId,
  //             quantity: 1 // Tambahkan nilai default quantity
  //         }),
  //     })
  //     .then(response => response.json())
  //     .then(data => {
  //         alert(data.message); // Tampilkan notifikasi
  //         updateCartItemCount(); // Perbarui jumlah item di ikon cart
  //     })
  //     .catch(error => console.error('Error:', error));
  // }
  function addToCart(productId) {
      // Ambil tombol yang diklik
      const addToCartButton = document.querySelector(`button[data-product-id="${productId}"]`);

      // Ambil data dari atribut tombol
      const hasVariants = addToCartButton.getAttribute('data-has-variants') === 'true';
      const variantId = addToCartButton.getAttribute('data-variant-id');

      // Siapkan data untuk dikirim ke server
      const requestData = {
          product_id: productId,
          quantity: 1, // Default quantity
      };

      // Jika produk memiliki varian, tambahkan variant_id ke requestData
      if (hasVariants && variantId) {
          requestData.variant_id = variantId;
      }

      fetch('{{ route('cart.add') }}', {
          method: 'POST',
          headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': '{{ csrf_token() }}',
          },
          body: JSON.stringify(requestData),
      })
      .then(response => {
          if (!response.ok) {
              // Jika respons tidak OK (misalnya, status 400), lempar error
              return response.json().then(data => {
                  throw new Error(data.message || 'Terjadi kesalahan saat menambahkan ke keranjang.');
              });
          }
          return response.json();
      })
      .then(data => {
          alert(data.message); // Tampilkan notifikasi sukses
          updateCartItemCount(); // Perbarui jumlah item di ikon cart
      })
      .catch(error => {
          console.error('Error:', error);
          alert(error.message); // Tampilkan notifikasi error
      });
  }

</script>
          