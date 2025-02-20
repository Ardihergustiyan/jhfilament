<div class="swiper-slide rounded-lg border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-800">
    <div class="h-40 w-full">
      <a href="{{ route('product.show', $product->slug) }}">
        @if($product->main_image)
          <img class="mx-auto h-full w-full object-cover" 
              src="{{ asset('storage/' . $product->main_image) }}" 
              alt="{{ $product->name }}" />
        @else
          @php
              $productImages = json_decode($product->product_image, true);
              $mainImage = $productImages[0] ?? null;
          @endphp
          @if($mainImage)
              <img class="mx-auto h-full w-full object-cover" 
                  src="{{ asset('storage/' . $mainImage) }}" 
                  alt="{{ $product->name }}" />
          @else
              <img class="mx-auto h-full w-full object-cover" 
                  src="{{ asset('path/to/default/image.jpg') }}" 
                  alt="{{ $product->name }}" />
          @endif
        @endif
      </a>
    </div>
    <div class="pt-4">
      <div class="mb-3 flex items-center justify-between gap-4">
          <div class="flex items-center gap-2">
              <span class="rounded bg-pink-100 px-2.5 py-0.5 text-xs font-medium text-pink-800 dark:bg-pink-900 dark:text-pink-300">
                  50% Off
              </span>
      
              @if ($product->total_discount > 0)
                  <span class="text-sm text-gray-500 dark:text-gray-400">+</span>
                  <span class="rounded bg-yellow-200 px-2.5 py-0.5 text-xs font-medium text-blue-800 dark:bg-blue-900 dark:text-blue-300">
                      🎉 {{ $product->discount_display }} Off
                  </span>
              @endif
          </div>
      </div>
  
      <a href="{{ route('product.show', $product->slug) }}" class="text-lg font-semibold leading-tight text-gray-900 hover:underline dark:text-white">{{ $product->name }}</a>
  
      <div class="mt-2 flex items-center gap-2">
        <div class="flex items-center">
          <svg class="h-4 w-4 text-yellow-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
            <path d="M13.8 4.2a2 2 0 0 0-3.6 0L8.4 8.4l-4.6.3a2 2 0 0 0-1.1 3.5l3.5 3-1 4.4c-.5 1.7 1.4 3 2.9 2.1l3.9-2.3 3.9 2.3c1.5 1 3.4-.4 3-2.1l-1-4.4 3.4-3a2 2 0 0 0-1.1-3.5l-4.6-.3-1.8-4.2Z" />
          </svg>
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
            <p class="text-sm text-gray-500 dark:text-gray-400">
                <del>Rp{{ number_format($product->base_price, 0, ',', '.') }},00</del>
            </p>
            
            @auth
                <p id="userRole" class="text-lg font-extrabold leading-tight text-gray-900 dark:text-white">
                    @if ($product->total_discount > 0 && $product->discounted_price < $product->final_price)
                        Rp {{ number_format($product->discounted_price, 0, ',', '.') }},00
                    @else
                        @if (Auth::user()->hasRole('Reseller'))
                            Rp {{ number_format($product->final_price, 0, ',', '.') }},00
                        @else
                            Rp {{ number_format($product->het_price, 0, ',', '.') }},00
                        @endif
                    @endif
                </p>
            @else
                <p class="text-lg font-extrabold leading-tight text-gray-900 dark:text-white">
                    @if ($product->total_discount > 0 && $product->discounted_price < $product->het_price)
                        Rp {{ number_format($product->discounted_price, 0, ',', '.') }},00
                    @else
                        Rp {{ number_format($product->het_price, 0, ',', '.') }},00
                    @endif
                </p>
            @endauth
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
    function addToCart(productId) {
        const addToCartButton = document.querySelector(`button[data-product-id="${productId}"]`);
        const hasVariants = addToCartButton.getAttribute('data-has-variants') === 'true';
        const variantId = addToCartButton.getAttribute('data-variant-id');

        const requestData = {
            product_id: productId,
            quantity: 1,
        };

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
                return response.json().then(data => {
                    throw new Error(data.message || 'Terjadi kesalahan saat menambahkan ke keranjang.');
                });
            }
            return response.json();
        })
        .then(data => {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: data.message,
            });
            updateCartItemCount();
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: error.message,
            });
        });
    }
</script>