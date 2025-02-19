@include('layouts.header')
@include('components.navbar')

<section class=" py-8 antialiased dark:bg-gray-900 md:py-12">
    <div class="mx-auto max-w-screen-xl px-4 2xl:px-0">
      <!-- Heading & Filters -->
      <div class="mb-4 items-end justify-between space-y-4 sm:flex sm:space-y-0 md:mb-8">
        <div>
          <nav class="flex" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
              <li class="inline-flex items-center">
                <a href="home" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-primary-600 dark:text-gray-400 dark:hover:text-white">
                  <svg class="me-2.5 h-3 w-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                    <path
                      d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z"
                    />
                  </svg>
                  Home
                </a>
              </li>
              <li>
                <div class="flex items-center">
                  <svg class="h-5 w-5 text-gray-400 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m9 5 7 7-7 7" />
                  </svg>
                  <a href="{{ route('product', array_merge(request()->query(), ['category' => 'all'])) }}" class="ms-1 text-sm font-medium text-gray-700 hover:text-primary-600 dark:text-gray-400 dark:hover:text-white md:ms-2">Products</a>
                </div>
              </li>
              <li aria-current="page">
                <div class="flex items-center">
                  <svg class="h-5 w-5 text-gray-400 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m9 5 7 7-7 7" />
                  </svg>
                  @php
                      $categories = [
                          'man' => 'Men',
                          'woman' => 'Women',
                          'tools' => 'Tools',
                      ];

                      $currentCategory = request('category') ? ($categories[request('category')] ?? ucfirst(request('category'))) : 'All';
                  @endphp

                  <span class="ms-1 text-sm font-medium text-gray-500 dark:text-gray-400 md:ms-2">
                      {{ $currentCategory }}
                  </span>
                </div>
              </li>
            </ol>
          </nav>
          <h2 class="mt-3 text-xl font-semibold text-gray-900 dark:text-white sm:text-2xl">
            Pencarian: 
            <span class="text-pink-500">
                {{ implode(', ', $selectedCategoryNames) ?: 'Semua Produk' }}
            </span>
        </h2>
        </div>
        <div class="flex items-center space-x-4">
          <button
            id="toggleSidebarBtn"
            type="button"
            class="flex w-full items-center justify-center rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm font-medium text-gray-900 hover:bg-gray-100 hover:text-primary-700 focus:z-10 focus:outline-none focus:ring-4 focus:ring-gray-100 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white dark:focus:ring-gray-700 sm:w-auto"
          >
            <svg class="-ms-0.5 me-2 h-4 w-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
              <path
                stroke="currentColor"
                stroke-linecap="round"
                stroke-width="2"
                d="M18.796 4H5.204a1 1 0 0 0-.753 1.659l5.302 6.058a1 1 0 0 1 .247.659v4.874a.5.5 0 0 0 .2.4l3 2.25a.5.5 0 0 0 .8-.4v-7.124a1 1 0 0 1 .247-.659l5.302-6.059c.566-.646.106-1.658-.753-1.658Z"
              />
            </svg>
            Filters
            <svg class="-me-0.5 ms-2 h-4 w-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
              <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 9-7 7-7-7" />
            </svg>
          </button>

          <button
            id="sortDropdownButton1"
            data-dropdown-toggle="dropdownSort1"
            type="button"
            class="flex w-full items-center justify-center rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm font-medium text-gray-900 hover:bg-gray-100 hover:text-primary-700 focus:z-10 focus:outline-none focus:ring-4 focus:ring-gray-100 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white dark:focus:ring-gray-700 sm:w-auto"
          >
            <svg class="-ms-0.5 me-2 h-4 w-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
              <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4v16M7 4l3 3M7 4 4 7m9-3h6l-6 6h6m-6.5 10 3.5-7 3.5 7M14 18h4" />
            </svg>
            Sort
            <svg class="-me-0.5 ms-2 h-4 w-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
              <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 9-7 7-7-7" />
            </svg>
          </button>
          <div id="dropdownSort1" class="z-50 hidden w-40 divide-y divide-gray-100 rounded-lg bg-white shadow dark:bg-gray-700" data-popper-placement="bottom">
            <ul class="p-2 text-left text-sm font-medium text-gray-500 dark:text-gray-400" aria-labelledby="sortDropdownButton">
              <li>
                <a href="{{ route('product', array_merge(request()->query(), ['filter' => 'trending'])) }}"  class="group inline-flex w-full items-center rounded-md px-3 py-2 text-sm text-gray-500 hover:bg-gray-100 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-gray-600 dark:hover:text-white"> Trending </a>
              </li>
              <li>
                <a href="{{ route('product', array_merge(request()->query(), ['filter' => 'terbaru'])) }}" class="group inline-flex w-full items-center rounded-md px-3 py-2 text-sm text-gray-500 hover:bg-gray-100 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-gray-600 dark:hover:text-white"> Terbaru </a>
              </li>
              <li>
                <a href="{{ route('product', array_merge(request()->query(), ['filter' => 'terlaris'])) }}" class="group inline-flex w-full items-center rounded-md px-3 py-2 text-sm text-gray-500 hover:bg-gray-100 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-gray-600 dark:hover:text-white"> Terlaris </a>
              </li>
              <li>
                <a href="{{ route('product', array_merge(request()->query(), ['filter' => 'termahal'])) }}" class="group inline-flex w-full items-center rounded-md px-3 py-2 text-sm text-gray-500 hover:bg-gray-100 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-gray-600 dark:hover:text-white"> Termahal </a>
              </li>
              <li>
                <a href="{{ route('product', array_merge(request()->query(), ['filter' => 'termurah'])) }}" class="group inline-flex w-full items-center rounded-md px-3 py-2 text-sm text-gray-500 hover:bg-gray-100 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-gray-600 dark:hover:text-white"> Termurah </a>
              </li>
              <li>
                <a href="{{ route('product', array_merge(request()->query(), ['filter' => 'discount'])) }}" class="group inline-flex w-full items-center rounded-md px-3 py-2 text-sm text-gray-500 hover:bg-gray-100 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-gray-600 dark:hover:text-white"> Discount % </a>
              </li>
            </ul>
          </div>
        </div>
      </div>
      {{-- end heading --}}
      
      {{-- main --}}
      <div id="productList" class="mb-4 grid gap-4 sm:grid-cols-2 md:mb-8 lg:grid-cols-3 xl:grid-cols-4">
        
        @if($allProducts->isEmpty())
            <div class=" py-8">
                <p class="text-lg font-semibold text-gray-500 dark:text-gray-400">
                    Produk tidak ditemukan.
                </p>
                <a href="/" class="mt-4 inline-block bg-pink-300 px-4 py-2 text-sm font-medium text-gray-800 hover:bg-pink-400 focus:outline-none focus:ring-4 focus:ring-pink-200 dark:bg-pink-400 dark:hover:bg-pink-500 dark:focus:ring-pink-500 rounded-lg">
                    Kembali ke Beranda
                </a>
            </div>
        @else
        @foreach ($allProducts as $product)
            <div class="product-item rounded-lg border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                <div class="h-40 w-full">
                    <a href="{{ route('product.show', $product->slug) }}">
                        @if($product->main_image)
                            <img class="mx-auto h-full" src="{{ asset('storage/' . $product->main_image) }}" alt="{{ $product->name }}" />
                        @else
                            @php
                                $productImages = json_decode($product->product_image, true);
                                $mainImage = $productImages[0] ?? null;
                            @endphp
                            @if($mainImage)
                                <img class="mx-auto h-full" src="{{ asset('storage/' . $mainImage) }}" alt="{{ $product->name }}" />
                            @else
                                <img class="mx-auto h-full" src="{{ asset('path/to/default/image.jpg') }}" alt="{{ $product->name }}" />
                            @endif
                        @endif
                    </a>
                </div>
                <div class="pt-4">
                  <div class="mb-3 flex items-center gap-2">
                      <!-- Badge untuk Diskon Reguler -->
                      <div class="flex items-center gap-2">
                          <span class="rounded  bg-pink-100 px-2.5 py-0.5 text-xs font-medium text-pink-800 dark:bg-pink-900 dark:text-pink-300 ">
                              50% Off
                          </span>
                  
                          <!-- Jika Ada Diskon Spesial -->
                          @if ($product->total_discount > 0)
                              <span class="text-sm text-gray-500 dark:text-gray-400">+</span>
                              <span class="rounded bg-yellow-200 px-2.5 py-0.5 text-xs font-medium text-blue-800 dark:bg-blue-900 dark:text-blue-300 ">
                                  🎉 {{ $product->discount_display }} Off
                              </span>
                          @endif
                      </div>
                  
                      <!-- Tooltip untuk Informasi Diskon -->
                      <div class="group relative inline-block">
                          <svg class="w-4 h-4 text-gray-500 dark:text-gray-400 cursor-pointer" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                          </svg>
                          <div class="hidden group-hover:block absolute z-10 bg-white dark:bg-gray-800 text-sm text-gray-700 dark:text-gray-200 px-3 py-2 rounded-lg shadow-lg w-48">
                              <p><strong>50% Off:</strong> Diskon reguler untuk semua produk.</p>
                              @if ($product->total_discount > 0)
                                  <p class="mt-1"><strong>{{ $product->discount_display }} Off:</strong> Diskon spesial terbatas!</p>
                              @endif
                          </div>
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
                          <p class="text-sm text-gray-500 dark:text-gray-400">
                            <del>Rp{{ number_format($product->base_price, 0, ',', '.') }},00</del>
                          </p>
                        
                          @auth
                              <p id="userRole" class="text-lg font-extrabold leading-tight text-gray-900 dark:text-white">
                                  @if (isset($product->discounted_price) && $product->discounted_price < $product->final_price)
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
                                  @if (isset($product->discounted_price) && $product->discounted_price < $product->het_price)
                                      Rp {{ number_format($product->discounted_price, 0, ',', '.') }},00
                                  @else
                                      Rp {{ number_format($product->het_price, 0, ',', '.') }},00
                                  @endif
                              </p>
                          @endauth
                      </div>
                        
                        
                        @if (Auth::check())
                            @if($product->variants->isEmpty())
                                <button type="button" class="inline-flex items-center rounded-lg bg-pink-300 px-4 py-2 text-sm font-medium text-gray-800 hover:bg-pink-400 focus:outline-none focus:ring-4 focus:ring-pink-200 dark:bg-pink-400 dark:hover:bg-pink-500 dark:focus:ring-pink-500" onclick="addToCart({{ $product->id }})">
                                    <svg class="-ms-2 me-2 h-5 w-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4h1.5L8 16m0 0h8m-8 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4Zm8 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4Zm.75-3H7.5M11 7H6.312M17 4v6m-3-3h6" />
                                    </svg>
                                    Add to cart
                                </button>
                            @else
                                <button type="button" class="inline-flex items-center rounded-lg bg-pink-300 px-4 py-2 text-sm font-medium text-gray-800 hover:bg-pink-400 focus:outline-none focus:ring-4 focus:ring-pink-200 dark:bg-pink-400 dark:hover:bg-pink-500 dark:focus:ring-pink-500" onclick="openModal('{{ $product->name }}', '{{ route('product.show', $product->slug) }}')">
                                    <svg class="-ms-2 me-2 h-5 w-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4h1.5L8 16m0 0h8m-8 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4Zm8 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4Zm.75-3H7.5M11 7H6.312M17 4v6m-3-3h6" />
                                    </svg>
                                    Add to cart
                                </button>
                            @endif
                        @else
                            <a href="/login" class="inline-flex items-center rounded-lg bg-pink-300 px-4 py-2 text-sm font-medium text-gray-800 hover:bg-pink-400 focus:outline-none focus:ring-4 focus:ring-pink-200 dark:bg-pink-400 dark:hover:bg-pink-500 dark:focus:ring-pink-500">
                                <svg class="-ms-2 me-2 h-5 w-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4h1.5L8 16m0 0h8m-8 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4Zm8 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4Zm.75-3H7.5M11 7H6.312M17 4v6m-3-3h6" />
                                </svg>
                                Add to cart
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
            <!-- Modal Container -->
        <div id="customModal" class="hidden fixed inset-0 z-50 flex justify-center items-center w-full h-full bg-gray-800 bg-opacity-50">
            <div class="relative p-4 w-full max-w-md max-h-full bg-white rounded-lg shadow-lg dark:bg-gray-800">
                <div class="p-4 md:p-5">
                    <div class="flex flex-col items-center text-center">
                        <div class="w-12 h-12 mb-4 text-pink-500 dark:text-pink-400">
                            <svg class="w-full h-full" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                            </svg>
                        </div>
                        <h3 id="modalTitle" class="mb-3 text-lg font-semibold text-gray-900 dark:text-white">
                            Anda belum memilih !
                        </h3>
                        <p class="mb-4 text-sm text-gray-500 dark:text-gray-400">
                            Silakan pilih warna produk terlebih dahulu sebelum menambahkan ke keranjang.
                        </p>
                        <div class="flex items-center justify-center gap-3">
                            <a id="modalDetailLink" href="#" class="inline-flex items-center justify-center px-5 py-2.5 text-sm font-medium text-white bg-pink-500 hover:bg-pink-600 focus:ring-4 focus:ring-pink-300 rounded-lg dark:bg-pink-600 dark:hover:bg-pink-700 dark:focus:ring-pink-800">
                                <svg class="w-4 h-4 me-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 7h14m-4 4V6a1 1 0 0 0-1-1H9a1 1 0 0 0-1 1v5m4 0v5m4-5v5m-8-5v5m4-5v5M4 6v13c0 1.1.9 2 2 2h13c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2H6c-1.1 0-2 .9-2 2Z"/>
                                </svg>
                                Lihat Detail
                            </a>
                            <button id="closeModalButton" type="button" class="inline-flex items-center justify-center px-5 py-2.5 text-sm font-medium text-gray-900 bg-white border border-gray-200 hover:bg-gray-100 hover:text-pink-700 focus:z-10 focus:ring-4 focus:ring-gray-100 rounded-lg dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700 dark:focus:ring-gray-700">
                                Tutup
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
      </div>
      {{-- end main --}}
    </div>
    {{-- filter --}}
    <div id="filterSidebar" class="hidden fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 p-4 w-3/4 md:w-1/3 max-w-screen-md bg-white rounded-lg shadow-2xl max-h-screen overflow-y-auto z-50 scrollbar-thin">
        <ul class="grid grid-cols-4 gap-2 mb-4">
          <!-- all -->
          <li>
            <input
              type="radio"
              id="option-all"
              name="category"
              value="all"
              class="hidden peer"
              onchange="window.location.href = '{{ route('product', array_merge(request()->query(), ['category' => 'all', 'categories' => null])) }}'"
              {{ request('category') === 'all' || !request('category') ? 'checked' : '' }}
            />
            <label
              for="option-all"
              class="block p-2 bg-pink-200 text-gray-800 border border-gray-300 rounded-lg cursor-pointer transition-all duration-300 ease-in-out hover:bg-pink-300 peer-checked:bg-pink-400 peer-checked:border-pink-400 peer-checked:text-white shadow-sm hover:shadow-md transform hover:-translate-y-0.5"
            >
              <div class="text-xs font-semibold text-center">All</div>
            </label>
          </li>
          <!-- Woman -->
          <li>
            <input 
                type="radio" 
                id="option-women" 
                name="category" 
                value="women" 
                class="hidden peer" 
                onchange="window.location.href = '{{ route('product', array_merge(request()->query(), ['category' => 'women', 'categories' => null])) }}'"
                {{ request('category') === 'women' ? 'checked' : '' }}
            />
            <label
                for="option-women"
                class="block p-2 bg-pink-200 text-gray-800 border border-gray-300 rounded-lg cursor-pointer transition-all duration-300 ease-in-out hover:bg-pink-300 peer-checked:bg-pink-400 peer-checked:border-pink-400 peer-checked:text-white shadow-sm hover:shadow-md transform hover:-translate-y-0.5"
            >
                <div class="text-xs font-semibold text-center">Woman</div>
            </label>
          </li>
          <!-- Man -->
          <li>
            <input
              type="radio"
              id="option-man"
              name="category"
              value="men"
              class="hidden peer"
              onchange="window.location.href = '{{ route('product', array_merge(request()->query(), ['category' => 'men', 'categories' => null])) }}'"
              {{ request('category') === 'men' ? 'checked' : '' }}
            />
            <label
              for="option-man"
              class="block p-2 bg-pink-200 text-gray-800 border border-gray-300 rounded-lg cursor-pointer transition-all duration-300 ease-in-out hover:bg-pink-300 peer-checked:bg-pink-400 peer-checked:border-pink-400 peer-checked:text-white shadow-sm hover:shadow-md transform hover:-translate-y-0.5"
            >
              <div class="text-xs font-semibold text-center">Man</div>
            </label>
          </li>
          <!-- Tools -->
          <li>
            <input
              type="radio"
              id="option-tools"
              name="category"
              value="tools"
              class="hidden peer"
              onchange="window.location.href = '{{ route('product', array_merge(request()->query(), ['category' => 'tools', 'categories' => null])) }}'"
              {{ request('category') === 'tools' ? 'checked' : '' }}
            />
            <label
              for="option-tools"
              class="block p-2 bg-pink-200 text-gray-800 border border-gray-300 rounded-lg cursor-pointer transition-all duration-300 ease-in-out hover:bg-pink-300 peer-checked:bg-pink-400 peer-checked:border-pink-400 peer-checked:text-white shadow-sm hover:shadow-md transform hover:-translate-y-0.5"
            >
              <div class="text-xs font-semibold text-center">Tools</div>
            </label>
          </li>
        </ul>
        
      <div class="mb-4">
          <button id="accordion-btn-tipe-produk" class="w-full text-left">
            <h2 class="text-lg font-semibold">TIPE PRODUK <span id="icon-tipe-produk" class="float-right">+</span></h2>
          </button>
        <div id="accordion-content-tipe-produk" class="mt-2 overflow-hidden transition-all duration-300 ease-in-out">
          <div class="overflow-y-auto h-56 scrollbar-thin scrollbar-thumb-gray-300 scrollbar-track-gray-100">
            <!-- Checkbox Items -->
              @foreach ($productTypes as $type)
                  <div class="pl-2 mt-2 mb-2">
                      <label class="flex items-center space-x-2">
                          <input
                              id="checkbox-{{ $type->category_id }}"
                              type="checkbox"
                              value="{{ $type->category_slug }}"
                              class="w-4 h-4 text-pink-400 bg-gray-100 border-gray-300 rounded focus:ring-pink-300 dark:focus:ring-pink-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                              onchange="updateFilter('{{ $type->category_slug }}')"
                              {{ in_array($type->category_slug, explode(',', request('categories', ''))) ? 'checked' : '' }}
                          />
                          <span>{{ $type->category_name }}</span>
                      </label>
                  </div>
              @endforeach
          </div>
        </div>
      </div>

      <!-- Harga Accordion and Sliders -->
      <div class="mb-4">
        <button id="accordion-btn-harga" class="w-full text-left">
          <h2 class="text-lg font-semibold">HARGA <span id="icon-harga" class="float-right">+</span></h2>
        </button>
        <div id="accordion-content-harga" class="mt-2 overflow-hidden transition-all duration-300 ease-in-out" >
          <!-- Slider Minimum Price -->
          <div class="max-w-md mx-auto mt-8">
            <h2 class="text-lg font-bold mb-4">Price Range</h2>
            <div class="flex flex-col items-center space-y-4">
              <!-- Slider -->
              <div id="slider-range" class="w-3/4 h-2 bg-gray-200 rounded-full relative flex items-center">
                  <div id="slider-min" class="h-4 w-4 bg-pink-300 rounded-full absolute cursor-pointer" style="left: 0;"></div>
                  <div id="slider-max" class="h-4 w-4 bg-pink-300 rounded-full absolute cursor-pointer" style="left: 100%;"></div>
              </div>
              <!-- Price Range -->
              <div class="flex justify-between items-center w-3/4">
                  <span id="min-price" class="text-gray-600">10000</span>
                  <span id="max-price" class="text-gray-600">500000</span>
              </div>
            </div>
          </div>
        </div>
        <!-- Display Resulting Range -->
        <div class="mt-4 p-2 bg-gray-100 rounded">
            <p class="text-sm">Rentang harga:</p>
            <p class="text-sm font-semibold text-gray-700">
                Rp <span id="rangeResultMin">10000</span> - Rp <span id="rangeResultMax">500000</span>
            </p>
        </div>
      </div>

    </div>
    {{-- end filter --}}
    <div id="backdrop" class="hidden fixed inset-0 bg-black bg-opacity-60 z-40"></div>
  </section>

<script>
  function updateFilter(selectedSlug) {
      const urlParams = new URLSearchParams(window.location.search);
      const currentCategory = urlParams.get('category') || 'all';
      let categories = urlParams.get('categories') ? urlParams.get('categories').split(',') : [];

      // Tambahkan atau hapus filter slug
      if (categories.includes(selectedSlug)) {
          categories = categories.filter(slug => slug !== selectedSlug);
      } else {
          categories.push(selectedSlug);
      }

      // Set parameter categories di URL
      if (categories.length > 0) {
          urlParams.set('categories', categories.join(','));
      } else {
          urlParams.delete('categories');
      }

      // Tambahkan parameter kategori utama jika all dipilih
      urlParams.set('category', currentCategory);

      window.location.search = urlParams.toString();
  }

  // Fungsi untuk membuka modal
  function openModal(title, detailUrl) {
      const modal = document.getElementById('customModal');
      const modalTitle = document.getElementById('modalTitle');
      const modalDetailLink = document.getElementById('modalDetailLink');

      // Isi konten modal
      modalTitle.textContent = title;
      modalDetailLink.href = detailUrl;

      // Tampilkan modal
      modal.classList.remove('hidden');
  }

  // Fungsi untuk menutup modal
  function closeModal() {
      const modal = document.getElementById('customModal');
      modal.classList.add('hidden');
  }

  // Event listener untuk tombol tutup modal
  document.getElementById('closeModalButton').addEventListener('click', closeModal);

  // Event listener untuk menutup modal saat mengklik di luar modal
  window.addEventListener('click', function(event) {
      const modal = document.getElementById('customModal');
      if (event.target === modal) {
          closeModal();
      }
  });


  function openModal(productName, productLink) {
        document.getElementById('modalTitle').innerText = `Anda belum memilih warna untuk ${productName}!`;
        document.getElementById('modalDetailLink').href = productLink;
        document.getElementById('customModal').classList.remove('hidden');
    }

    function closeModal() {
        document.getElementById('customModal').classList.add('hidden');
    }

    document.getElementById('closeModalButton').addEventListener('click', closeModal);


    function addToCart(productId) {
              fetch('{{ route('cart.add') }}', {
                  method: 'POST',
                  headers: {
                      'Content-Type': 'application/json',
                      'X-CSRF-TOKEN': '{{ csrf_token() }}',
                  },
                  body: JSON.stringify({
                      product_id: productId,
                      quantity: 1 // Tambahkan nilai default quantity
                  }),
              })
              .then(response => response.json())
              .then(data => {
                  alert(data.message); // Tampilkan notifikasi
                  updateCartItemCount(); // Perbarui jumlah item di ikon cart
              })
              .catch(error => console.error('Error:', error));
          }
</script>
<script src="{{ asset('js/frontend/product.js') }}"></script>


@include('layouts.footer')