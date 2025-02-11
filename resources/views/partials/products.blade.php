@foreach ($allProducts as $product)
    <div class="product-item rounded-lg border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-800">
        <div class="h-40 w-full">
            <a href="{{ route('product.show', $product->slug) }}">
            @if($product->main_image)
                <!-- Jika ada gambar dari varian -->
                <img class="mx-auto h-full" 
                    src="{{ asset('storage/' . $product->main_image) }}" 
                    alt="{{ $product->name }}" />
            @else
                <!-- Jika tidak ada gambar dari varian, gunakan gambar dari produk -->
                @php
                    $productImages = json_decode($product->product_image, true);
                    $mainImage = $productImages[0] ?? null;
                @endphp
                @if($mainImage)
                    <img class="mx-auto h-full" 
                        src="{{ asset('storage/' . $mainImage) }}" 
                        alt="{{ $product->name }}" />
                @else
                    <!-- Jika tidak ada gambar sama sekali, gunakan gambar default -->
                    <img class="mx-auto h-full" 
                        src="{{ asset('path/to/default/image.jpg') }}" 
                        alt="{{ $product->name }}" />
                @endif
            @endif
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
                </div>
                <p class="text-sm font-medium text-gray-900 dark:text-white">
                    @if ($product->total_reviews > 0)
                        {{ number_format($product->average_rating, 1) }}
                    @else
                        <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Belum ada ulasan</span>
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
                    <p class="text-sm text-gray-500 dark:text-gray-400"><del>Rp{{ number_format($product->base_price, 0, ',', '.') }},00</p>
                    <p class="text-lg font-extrabold leading-tight text-gray-900 dark:text-white">Rp{{ number_format($product->het_price, 0, ',', '.') }},00</p>
                </div>
                
                {{-- Jika sudah login --}}
                
                @if (Auth::check())
                  @if($product->variants->isEmpty())
                      <button 
                          type="button" 
                          class="inline-flex items-center rounded-lg bg-pink-300 px-4 py-2 text-sm font-medium text-gray-800 hover:bg-pink-400 focus:outline-none focus:ring-4 focus:ring-pink-200 dark:bg-pink-400 dark:hover:bg-pink-500 dark:focus:ring-pink-500" 
                          onclick="addToCart({{ $product->id }})"
                      >
                          <svg class="-ms-2 me-2 h-5 w-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                              <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4h1.5L8 16m0 0h8m-8 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4Zm8 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4Zm.75-3H7.5M11 7H6.312M17 4v6m-3-3h6" />
                          </svg>
                          Add to cart
                      </button>
                  @else
                      {{-- <button 
                          type="button" 
                          class="inline-flex items-center rounded-lg bg-pink-300 px-4 py-2 text-sm font-medium text-gray-800 hover:bg-pink-400 focus:outline-none focus:ring-4 focus:ring-pink-200 dark:bg-pink-400 dark:hover:bg-pink-500 dark:focus:ring-pink-500" 
                          data-modal-target="popup-modal-{{ $product->id }}" 
                          data-modal-toggle="popup-modal-{{ $product->id }}"
                      >
                          <svg class="-ms-2 me-2 h-5 w-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                              <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4h1.5L8 16m0 0h8m-8 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4Zm8 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4Zm.75-3H7.5M11 7H6.312M17 4v6m-3-3h6" />
                          </svg>
                          Add to cart
                      </button> --}}
                      <button 
                          type="button" 
                          class="inline-flex items-center rounded-lg bg-pink-300 px-4 py-2 text-sm font-medium text-gray-800 hover:bg-pink-400 focus:outline-none focus:ring-4 focus:ring-pink-200 dark:bg-pink-400 dark:hover:bg-pink-500 dark:focus:ring-pink-500"
                          onclick="openModal('{{ $product->name }}', '{{ route('product.show', $product->slug) }}')"
                        >
                          <svg class="-ms-2 me-2 h-5 w-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                              <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4h1.5L8 16m0 0h8m-8 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4Zm8 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4Zm.75-3H7.5M11 7H6.312M17 4v6m-3-3h6" />
                          </svg>
                          Add to cart
                      </button>
                  @endif
                @else
                    {{-- Jika belum login --}}
                    <a href="/login" class="inline-flex items-center rounded-lg bg-pink-300 px-4 py-2 text-sm font-medium text-gray-800 hover:bg-pink-400 focus:outline-none focus:ring-4 focus:ring-pink-200 dark:bg-pink-400 dark:hover:bg-pink-500 dark:focus:ring-pink-500">
                        <svg class="-ms-2 me-2 h-5 w-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4h1.5L8 16m0 0h8m-8 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4Zm8 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4Zm.75-3H7.5M11 7H6.312M17 4v6m-3-3h6" />
                        </svg>
                        Add to cart
                    </a>
                @endif
                
                {{-- <div id="popup-modal-{{ $product->id }}" tabindex="-1" class="hidden fixed inset-0 z-50 flex justify-center items-center w-full h-full bg-gray-800 bg-opacity-50">
                    <div class="relative p-4 w-full max-w-md max-h-full bg-white rounded-lg shadow dark:bg-gray-700">
                        <!-- Button Close -->
                        <button 
                            type="button" 
                            class="absolute top-3 right-3 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                            data-modal-hide="popup-modal-{{ $product->id }}"
                          >
                            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                            </svg>
                            <span class="sr-only">Close modal</span>
                        </button>
                        <div class="p-4 md:p-5 text-center">
                            <svg class="mx-auto mb-4 text-gray-400 w-12 h-12 dark:text-gray-200" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                            </svg>
                            <h3 class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400">Anda belum menentukan warna produk, ke halaman detail untuk memilih</h3>
                
                            <a data-modal-hide="popup-modal-{{ $product->id }}" href="{{ route('product.show', $product->slug) }}" class="text-white bg-pink-400 hover:bg-pink-500 focus:ring-4 focus:outline-none focus:ring-pink-400 dark:focus:ring-red-800 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center">Lihat Detail</a>
                
                            <button data-modal-hide="popup-modal-{{ $product->id }}" type="button" class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-pink-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">Tutup</button>
                        </div>
                    </div>
                </div> --}}

                <!-- Modal Container -->
                <div id="customModal" class="hidden fixed inset-0 z-50 flex justify-center items-center w-full h-full bg-gray-800 bg-opacity-50">
                  <div class="relative p-4 w-full max-w-md max-h-full bg-white rounded-lg shadow dark:bg-gray-700">
                      <!-- Modal Content -->
                      <div class="p-4 md:p-5 text-center">
                          <h3 id="modalTitle" class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400"></h3>
                          <a id="modalDetailLink" href="#" class="text-white bg-pink-400 hover:bg-pink-500 focus:ring-4 focus:outline-none focus:ring-pink-400 dark:focus:ring-red-800 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center">Lihat Detail</a>
                          <button id="closeModalButton" type="button" class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-pink-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">Tutup</button>
                      </div>
                  </div>
                </div>
            </div>
        </div>
    </div>
    <div id="loadingIndicator" class="hidden w-full text-center py-4">
        <div class="text-center">
          <div role="status">
              <svg aria-hidden="true" class="inline w-8 h-8 text-gray-200 animate-spin dark:text-gray-600 fill-blue-600" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"/>
                  <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"/>
              </svg>
              <span class="sr-only">Loading...</span>
          </div>
      </div>
    </div>
@endforeach