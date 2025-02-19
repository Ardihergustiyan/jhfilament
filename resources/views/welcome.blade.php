@include('layouts.header')
    <!-- marquee -->
    <div class="bg-gradient-to-r from-pink-100 via-white to-pink-100  border-gray-200 overflow-hidden">
        <div class="whitespace-nowrap animate-marquee flex">
          <p class="inline-block text-sm md:text-base text-gray-600 mr-[50%]">Diskon 50% untuk semua produk selama promo 10.10! | Pengiriman gratis untuk pembelian di atas Rp 500.000 | Penawaran spesial akhir bulan!</p>
          <p class="inline-block text-sm md:text-base text-gray-600">Diskon 50% untuk semua produk selama promo 10.10! | Pengiriman gratis untuk pembelian di atas Rp 500.000 | Penawaran spesial akhir bulan!</p>
        </div>
    </div>
    <!-- end marquee -->

    {{-- navbar --}}
    @include('components.navbar')
    {{-- end navbar --}}

    {{-- hero section --}}
    <div class="container mx-auto p-4 sm:p-6">
      <div class="flex flex-wrap -mx-2 sm:-mx-3">
        <!-- Card kiri (besar) -->
        <div class="w-full lg:w-3/5 px-2 sm:px-3 mb-6 lg:mb-0">
          <div class="bg-softPink p-6 sm:p-10 rounded-lg shadow-lg h-full flex flex-col lg:flex-row">
            <!-- Bagian kiri 60% -->
            <div class="w-full lg:w-3/5 flex flex-col justify-center">
              <h3 class="text-2xl sm:text-4xl font-thin font-poppinsLight mb-6 sm:mb-10 text-indigo-800">100% Original</h3>
              <div class="text-3xl sm:text-5xl font-bold mb-5 font-robotoLight">Stylish Bags by Jimshoney</div>
              <p class="mb-6 sm:mb-8 leading-relaxed sm:leading-loose font-roboto text-slate-500">"Jims Honey Deserve You Better, karena setiap detail tas dirancang dengan penuh perhatian untuk memberikan kenyamanan dan gaya terbaik bagi Anda."</p>
              <a
                href="product"
                class="relative inline-flex w-32 items-center rounded-lg bg-pink-300 px-4 py-2 text-sm font-medium text-gray-800 hover:bg-pink-400 focus:outline-none focus:ring-4 focus:ring-pink-300"
              >
                Selengkapnya
              </a>
            </div>
            <!-- Bagian kanan 40% -->
            <div class="w-full lg:w-2/5 flex items-center justify-center mt-6 lg:mt-0">
              <div class="w-full max-w-xs sm:max-w-sm">
                <img src="{{ asset('img/hero1.png') }}" alt="JimsHoney Bag" class="w-full h-auto max-h-64 sm:max-h-96 object-contain" />
              </div>
            </div>
          </div>
        </div>
    
        <!-- Card kanan (dua card kecil) -->
        <div class="w-full lg:w-2/5 px-2 sm:px-3">
          <!-- Card kecil atas -->
          <div class="bg-lavender p-4 sm:p-6 rounded-lg shadow-lg mb-6 flex flex-col lg:flex-row">
            <!-- Bagian kiri 60% -->
            <div class="w-full lg:w-3/5 flex flex-col justify-center">
                @if ($discountedProducts->isNotEmpty())
                    @php
                        $product = $discountedProducts->first(); // Ambil produk pertama
                        // Cek apakah $product->image adalah array atau JSON string
                        $images = is_array($product->image) ? $product->image : json_decode($product->image, true);
                        $productImage = $images[0] ?? 'default-image.jpg'; // Gunakan gambar default jika array kosong
                    @endphp
                    <h3 class="text-2xl font-thin mb-2 text-yellow-600">{{ $product->discount_display }} Off</h3>
                    <div class="text-xl sm:text-2xl font-bold mt-3 sm:mt-5 mb-3 sm:mb-5 font-robotoLight">
                        <a href="{{ route('product.show', $product->slug) }}" class="hover:underline transition duration-300">
                            {{ $product->name }}
                        </a>
                    </div>
                    <p class="mb-4 sm:mb-8 leading-relaxed sm:leading-loose">{{ Str::limit($product->description, 100) }}</p>
                @else
                    <h3 class="text-2xl font-thin mb-2 text-yellow-600">No Discounts Available</h3>
                    <div class="text-xl sm:text-2xl font-bold mt-3 sm:mt-5 mb-3 sm:mb-5 font-robotoLight">Featured Product</div>
                    <p class="mb-4 sm:mb-8 leading-relaxed sm:leading-loose">Check out our amazing collection!</p>
                @endif
            </div>
            <!-- Bagian kanan 40% -->
            <div class="w-full lg:w-2/5 flex items-center justify-center mt-4 sm:mt-6 lg:mt-0">
                <div class="w-full max-w-xs sm:max-w-sm">
                    @if ($discountedProducts->isNotEmpty())
                        <img src="/storage/{{ $productImage }}" alt="{{ $product->name }}" class="object-contain w-full h-auto max-w-full max-h-48 sm:max-h-60" />
                    @else
                        <img src="{{ asset('img/hero2.png') }}" alt="JimsHoney Bag" class="object-contain w-full h-auto max-w-full max-h-48 sm:max-h-60" />
                    @endif
                </div>
            </div>
        </div>
    
          <!-- Card kecil bawah -->
          <div class="bg-blushNude p-4 sm:p-6 rounded-lg shadow-lg flex flex-col lg:flex-row">
            <div class="w-full lg:w-3/5 flex flex-col justify-center">
              <h3 class="text-red-500 text-xl sm:text-2xl font-thin mb-2">Up to 40% Off</h3>
              <div class="text-xl sm:text-2xl font-bold mt-3 sm:mt-5 mb-3 sm:mb-5 font-poppins">Join Reseller</div>
              <p class="mb-4 sm:mb-8 leading-relaxed sm:leading-loose">Gabung Jadi Reseller, Raih Keuntungan Lebih Besar Bersama Toko Jims Honey!</p>
            </div>
            <!-- Bagian kanan -->
            <div class="w-full lg:w-2/5 flex items-center justify-center mt-4 sm:mt-6 lg:mt-0">
              <div class="w-full max-w-xs sm:max-w-md">
                <img src="{{ asset('img/hero3.png') }}" alt="JimsHoney Bag" class="object-contain w-full h-auto max-w-full max-h-48 sm:max-h-80" />
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    {{-- end hero --}}

    {{-- category --}}
    <section class="py-6 md:py-12">
      <div class="container mx-auto px-6">
        <!-- Header with title, view all link, and navigation buttons -->
        <div class="w-full">
          <div class="w-full">
            <div class="flex flex-wrap justify-between items-center mb-5">
              <!-- Judul "Category" -->
              <h2 class="text-xl sm:text-3xl font-semibold text-gray-800">Category</h2>
        
              <div class="flex items-center space-x-2 sm:space-x-4">
                <!-- "View All Categories" link -->
                <a href="{{ route('product', array_merge(request()->query(), ['category' => 'all'])) }}" class="text-gray-600 hover:text-gray-800 transition-all duration-300 text-xs sm:text-sm hover:underline">
                  View All Categories →
                </a>
        
                <!-- Swiper navigation buttons -->
                <div class="flex space-x-1 sm:space-x-2">
                  <!-- Previous button (left arrow) -->
                  <button id="prevBtn" type="button" class="text-white bg-red-300 hover:bg-red-400 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-full text-xs sm:text-sm p-2 sm:p-3 inline-flex items-center justify-center">
                    <svg class="w-3 h-3 sm:w-4 sm:h-4" aria-hidden="true" fill="none" viewBox="0 0 14 10">
                      <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5H1m0 0L5 1m-4 4L5 9" />
                    </svg>
                    <span class="sr-only">Previous slide</span>
                  </button>
        
                  <!-- Next button (right arrow) -->
                  <button id="nextBtn" type="button" class="text-white bg-red-300 hover:bg-red-400 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-full text-xs sm:text-sm p-2 sm:p-3 inline-flex items-center justify-center">
                    <svg class="w-3 h-3 sm:w-4 sm:h-4" aria-hidden="true" fill="none" viewBox="0 0 14 10">
                      <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9" />
                    </svg>
                    <span class="sr-only">Next slide</span>
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Swiper container -->
        <div class="swiper-container overflow-hidden">
          <!-- Added overflow-hidden here -->
          <div class="swiper-wrapper">
            <!-- Card 1 -->
            <a
              href="{{ route('product', ['categories' => 'backpack', 'category' => 'all']) }}"
              class="swiper-slide mb-2 mt-3 bg-white hover:cursor-pointer hover:shadow-lg hover:-translate-y-2 transform transition-all duration-300 rounded-lg overflow-hidden  sm:h-44 flex flex-col items-center justify-center"
            >
              <img src="{{ asset('img/svg/backpack.svg') }}" alt="Bag Icon" class="w-16 h-16 sm:w-24 sm:h-24" />
              <span class="font-arial font-semibold text-sm sm:text-base pb-2 md:pb-0">Backpack</span>
            </a>
            <!-- Card 2 -->
            <a href="{{ route('product', ['categories' => 'bag', 'category' => 'all']) }}" class="swiper-slide mt-3 bg-white hover:cursor-pointer hover:shadow-lg hover:-translate-y-2 transform transition-all duration-300 rounded-lg overflow-hidden  sm:h-44 flex flex-col items-center justify-center">
              <img src="{{ asset('img/svg/bag.svg') }}" alt="Bag Icon" class="w-16 h-16 sm:w-24 sm:h-24" />
              <span class="font-arial font-semibold text-sm sm:text-base pb-2 md:pb-0">Bag</span>
            </a>
            <!-- Card 3 -->
            <a href="{{ route('product', ['categories' => 'wallet', 'category' => 'all']) }}" class="swiper-slide mt-3 bg-white hover:cursor-pointer hover:shadow-lg hover:-translate-y-2 transform transition-all duration-300 rounded-lg overflow-hidden  sm:h-44 flex flex-col items-center justify-center">
              <img src="{{ asset('img/svg/wallet.svg') }}" alt="Bag Icon" class="w-16 h-16 sm:w-24 sm:h-24" />
              <span class="font-arial font-semibold text-sm sm:text-base mt-1 sm:mt-2 pb-2 md:pb-0">Wallet</span>
            </a>
            <!-- Card 4 -->
            <a href="{{ route('product', ['categories' => 'mini-bag', 'category' => 'all']) }}" class="swiper-slide mt-3 bg-white hover:cursor-pointer hover:shadow-lg hover:-translate-y-2 transform transition-all duration-300 rounded-lg overflow-hidden  sm:h-44 flex flex-col items-center justify-center">
              <img src="{{ asset('img/svg/minibag.svg') }}" alt="Bag Icon" class="w-16 h-16 sm:w-24 sm:h-24" />
              <span class="font-arial font-semibold text-sm sm:text-base mt-1 sm:mt-2 pb-2 md:pb-0">Mini Bag</span>
            </a>
            <!-- Card 5 -->
            <a href="{{ route('product', ['categories' => 'watch', 'category' => 'all']) }}" class="swiper-slide mt-3 bg-white hover:cursor-pointer hover:shadow-lg hover:-translate-y-2 transform transition-all duration-300 rounded-lg overflow-hidden  sm:h-44 flex flex-col items-center justify-center">
              <img src="{{ asset('img/svg/watch.svg') }}" alt="Bag Icon" class="w-16 h-16 sm:w-24 sm:h-24" />
              <span class="font-arial font-semibold text-sm sm:text-base mt-1 sm:mt-2 pb-2 md:pb-0">Watch</span>
            </a>
            <!-- Card 6 -->
            <a href="{{ route('product', ['categories' => 'powerbank', 'category' => 'all']) }}" class="swiper-slide mt-3 bg-white hover:cursor-pointer hover:shadow-lg hover:-translate-y-2 transform transition-all duration-300 rounded-lg overflow-hidden  sm:h-44 flex flex-col items-center justify-center">
              <img src="{{ asset('img/svg/powerbank.svg') }}" alt="Bag Icon" class="w-16 h-16 sm:w-24 sm:h-24" />
              <span class="font-arial font-semibold text-sm sm:text-base mt-1 sm:mt-2 pb-2 md:pb-0">Powerbank</span>
            </a>
            <!-- Card 7 -->
            <a href="{{ route('product', ['categories' => 'highheels', 'category' => 'all']) }}" class="swiper-slide mt-3 bg-white hover:cursor-pointer hover:shadow-lg hover:-translate-y-2 transform transition-all duration-300 rounded-lg overflow-hidden  sm:h-44 flex flex-col items-center justify-center">
              <img src="{{ asset('img/svg/higheels.svg') }}" alt="Bag Icon" class="w-16 h-16 sm:w-24 sm:h-24" />
              <span class="font-arial font-semibold text-sm sm:text-base mt-1 sm:mt-2 pb-2 md:pb-0">Highheels</span>
            </a>
            <!-- Card 8 -->
            <a href="{{ route('product', ['categories' => 'tumbler', 'category' => 'all']) }}" class="swiper-slide mt-3 bg-white hover:cursor-pointer hover:shadow-lg hover:-translate-y-2 transform transition-all duration-300 rounded-lg overflow-hidden  sm:h-44 flex flex-col items-center justify-center">
              <img src="{{ asset('img/svg/tumbler.svg') }}" alt="Bag Icon" class="w-16 h-16 sm:w-24 sm:h-24" />
              <span class="font-arial font-semibold text-sm sm:text-base mt-1 sm:mt-2 pb-2 md:pb-0">Tumbler</span>
            </a>
            <!-- Card 9 -->
            <a href="{{ route('product', ['categories' => 'packaging', 'category' => 'all']) }}" class="swiper-slide mt-3 bg-white hover:cursor-pointer hover:shadow-lg hover:-translate-y-2 transform transition-all duration-300 rounded-lg overflow-hidden  sm:h-44 flex flex-col items-center justify-center">
              <img src="{{ asset('img/svg/packaging.svg') }}" alt="Bag Icon" class="w-16 h-16 sm:w-24 sm:h-24" />
              <span class="font-arial font-semibold text-sm sm:text-base mt-1 sm:mt-2 pb-2 md:pb-0">Packaging</span>
            </a>
          </div>
        </div>
      </div>
    </section>
    {{-- end category --}}

    {{-- new arrival --}}
    <section class="py-6 md:py-12">
      <div class="container mx-auto px-6">
        <!-- Header with title, view all link, and navigation buttons -->
        <div class="w-full">
          <div class="w-full">
            <div class="flex flex-wrap justify-between items-center mb-4 sm:mb-5">
              <!-- Judul "New Arrival" -->
              <h2 class="text-xl sm:text-3xl font-semibold text-gray-800">New Arrival</h2>
        
              <div class="flex items-center space-x-2 sm:space-x-4">
                <!-- "View All Product" link -->
                <a href="{{ route('product', array_merge(request()->query(), ['filter' => 'terbaru'])) }}" class="text-gray-600 hover:text-gray-800 transition-all duration-300 text-xs sm:text-sm hover:underline">
                  View All Product →
                </a>
        
                <!-- Swiper navigation buttons -->
                <div class="flex space-x-1 sm:space-x-2">
                  <!-- Previous button (left arrow) -->
                  <button id="prevBtn2" type="button" class="text-white bg-red-300 hover:bg-red-400 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-full text-xs sm:text-sm p-2 sm:p-3 inline-flex items-center justify-center">
                    <svg class="w-3 h-3 sm:w-4 sm:h-4" aria-hidden="true" fill="none" viewBox="0 0 14 10">
                      <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5H1m0 0L5 1m-4 4L5 9" />
                    </svg>
                    <span class="sr-only">Previous slide</span>
                  </button>
        
                  <!-- Next button (right arrow) -->
                  <button
                    id="nextBtn2"
                    type="button"
                    class="text-white bg-red-300 hover:bg-red-400 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-full text-xs sm:text-sm p-2 sm:p-3 inline-flex items-center justify-center"
                  >
                    <svg class="w-3 h-3 sm:w-4 sm:h-4" aria-hidden="true" fill="none" viewBox="0 0 14 10">
                      <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9" />
                    </svg>
                    <span class="sr-only">Next slide</span>
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Swiper container -->
        <div class="swiper-container2 overflow-hidden w-full">
          <!-- Added overflow-hidden here -->
          <div class="swiper-wrapper">
            @foreach ($newArrivals as $product)
              @include('components.card2', ['product' => $product])
            @endforeach
          </div>
        </div>
        <!-- Swiper container end -->
      </div>
    </section>
    {{-- end new arrival --}}

    {{-- product terlaris --}}
    <div class="container mx-auto my-6 px-6">
      <nav class="flex justify-between items-center border-b border-gray-200">
        <!-- Left Tab -->
        <h4 class="text-gray-500 text-sm font-semibold uppercase">Product Terlaris</h4>
        <!-- All Tab -->
        <div class="flex space-x-4">
          <button onclick="showProductTab('all')" id="tab-all" class="text-gray-500 text-sm font-semibold uppercase pb-2 border-b-2 border-transparent hover:text-pink-300" role="tab">All</button>

          <!-- Fruits & Veges Tab -->
          <button onclick="showProductTab('wallet')" id="tab-wallet" class="text-pink-300 text-sm font-semibold uppercase pb-2 border-b-2 border-pink-300" role="tab">wallet</button>

          <!-- Juices Tab -->
          <button onclick="showProductTab('bag')" id="tab-bag" class="text-gray-500 text-sm font-semibold uppercase pb-2 border-b-2 border-transparent hover:text-pink-300" role="tab">Bag</button>
        </div>
      </nav>

        <div id="content-all" class="tab-content mt-4 mb-4 grid gap-4 sm:grid-cols-3 md:mb-8 lg:grid-cols-4 xl:grid-cols-5">
          @foreach ($topProductsAll as $product)
            @include('components.card', ['product' => $product])
          @endforeach
          {{-- @foreach ($topProductsAll as $product)
          <div>
              <img src="{{ asset('storage/' . $product->main_image) }}" alt="{{ $product->name }}" />
              <h3>{{ $product->name }}</h3>
              <p>Harga: Rp{{ number_format($product->het_price) }}</p>
              <p>Terjual: {{ $product->total_quantity }} item</p>
              <p>Rating: {{ number_format($product->average_rating, 1) }} ({{ $product->total_reviews }} ulasan)</p>
          </div>
          @endforeach --}}
        </div>
        <div id="content-wallet" class="tab-content mt-4 mb-4 grid gap-4 sm:grid-cols-3 md:mb-8 lg:grid-cols-4 xl:grid-cols-5">
          {{-- @foreach ($topProductsWallet as $product)
          <div>
              <img src="{{ asset('storage/' . $product->main_image) }}" alt="{{ $product->name }}" />
              <h3>{{ $product->name }}</h3>
              <p>Harga: Rp{{ number_format($product->het_price) }}</p>
              <p>Terjual: {{ $product->total_quantity }} item</p>
              <p>Rating: {{ number_format($product->average_rating, 1) }} ({{ $product->total_reviews }} ulasan)</p>
          </div>
          @endforeach --}}
          @foreach ($topProductsWallet as $product)
            @include('components.card', ['product' => $product])
          @endforeach
        </div>
        <div id="content-bag" class="tab-content mt-4 mb-4 grid gap-4 sm:grid-cols-3 md:mb-8 lg:grid-cols-4 xl:grid-cols-5">
          {{-- @foreach ($topProductsBag as $product)
          <div>
      
              <img src="{{ asset('storage/' . $product->main_image) }}" alt="{{ $product->name }}" />
              <h3>{{ $product->name }}</h3>
              <p>Harga: Rp{{ number_format($product->het_price) }}</p>
              <p>Terjual: {{ $product->total_quantity }} item</p>
              <p>Rating: {{ number_format($product->average_rating, 1) }} ({{ $product->total_reviews }} ulasan)</p>
          </div>
          @endforeach --}}
          @foreach ($topProductsBag as $product)
            @include('components.card', ['product' => $product])
          @endforeach
        </div>
      </div>
    </div>
    {{-- end product terlaris --}}

    {{-- promosi --}}
    <section class="container mx-auto p-4 md:p-6">
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
          @foreach ($discountedProducts as $index => $product)
              <div class="flex flex-row rounded-lg p-4 md:p-6 items-center 
                  {{ $index % 2 === 0 ? 'bg-pink-100' : 'bg-blue-100' }}">
                  <!-- Bagian Teks (Kiri) -->
                  <div class="flex-1 text-left">
                      <p class="text-yellow-600 font-semibold text-sm md:text-lg">Up to {{ $product->discount_display }} Off</p>
                      <h2 class="text-lg md:text-2xl font-bold text-gray-800">{{ $product->name }}</h2>
                      <p class="text-gray-600 mt-2 text-xs md:text-base">{{ Str::limit($product->description, 100) }}</p>
                      <a
                          href="{{ route('product.show', $product->slug) }}"
                          class="mt-4 relative inline-flex w-24 md:w-32 items-center justify-center rounded-lg bg-pink-300 px-3 py-1.5 md:px-4 md:py-2 text-xs md:text-sm font-medium text-gray-800 hover:bg-pink-400 focus:outline-none focus:ring-4 focus:ring-pink-300 dark:bg-pink-400 dark:hover:bg-pink-500 dark:focus:ring-pink-500 transition duration-300 ease-in-out"
                          aria-label="Show products now"
                      >
                          SHOW NOW
                      </a>
                  </div>
  
                  <!-- Bagian Gambar (Kanan) -->
                  <div class="ml-4">
                      @php
                          // Cek apakah $product->image adalah array atau JSON string
                          $images = is_array($product->image) ? $product->image : json_decode($product->image, true);
                          $productImage = $images[0] ?? 'default-image.jpg'; // Gunakan gambar default jika array kosong
                      @endphp
                      <img src="/storage/{{ $productImage }}" alt="{{ $product->name }}" class="w-24 h-24 md:w-40 md:h-40 object-cover rounded-md shadow-md" />
                  </div>
              </div>
          @endforeach
      </div>
    </section>
    {{-- end promosi --}}

    {{-- Trending --}}
    <section class="py-12">
      <div class="container mx-auto px-6">
        <!-- Header with title, view all link, and navigation buttons -->
        <div class="w-full">
          <div class="w-full">
            <div class="flex flex-wrap justify-between items-center mb-4 sm:mb-5">
              <!-- Judul "Trending" -->
              <h2 class="text-xl sm:text-3xl font-semibold text-gray-800">Trending</h2>
        
              <div class="flex items-center space-x-2 sm:space-x-4">
                <!-- "View All Product" link -->
                <a href="{{ route('product', array_merge(request()->query(), ['filter' => 'trending'])) }}" class="text-gray-600 hover:text-gray-800 transition-all duration-300 text-xs sm:text-sm hover:underline">
                  View All Product
                </a>
        
                <!-- Swiper navigation buttons -->
                <div class="flex space-x-1 sm:space-x-2">
                  <!-- Previous button (left arrow) -->
                  <button id="prevBtn3" type="button" class="text-white bg-red-300 hover:bg-red-400 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-full text-xs sm:text-sm p-2 sm:p-3 inline-flex items-center justify-center">
                    <svg class="w-3 h-3 sm:w-4 sm:h-4" aria-hidden="true" fill="none" viewBox="0 0 14 10">
                      <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5H1m0 0L5 1m-4 4L5 9" />
                    </svg>
                    <span class="sr-only">Previous slide</span>
                  </button>
        
                  <!-- Next button (right arrow) -->
                  <button
                    id="nextBtn3"
                    type="button"
                    class="text-white bg-red-300 hover:bg-red-400 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-full text-xs sm:text-sm p-2 sm:p-3 inline-flex items-center justify-center"
                  >
                    <svg class="w-3 h-3 sm:w-4 sm:h-4" aria-hidden="true" fill="none" viewBox="0 0 14 10">
                      <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9" />
                    </svg>
                    <span class="sr-only">Next slide</span>
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Swiper container -->
        <div class="swiper-container3 overflow-hidden w-full">
          <!-- Added overflow-hidden here -->
          <div class="swiper-wrapper">
            @foreach($mostReviewedProducts as $product)
              @include('components.card', ['product' => $product])
            @endforeach
          </div>
        </div>
        <!-- Swiper container end -->
      </div>
    </section>
    {{-- end Trending --}}

    {{-- banner --}}
    <div class="container mx-auto p-4 md:p-6">
      <div class="bg-blue-100 rounded-3xl md:flex md:items-center md:justify-between p-6 md:p-10 lg:p-20 shadow-lg">
          <!-- Left Side - Discount Text -->
          <div class="md:w-1/2 text-center md:text-left mb-10 md:mb-0 md:px-10">
              <h2 class="text-3xl md:text-5xl font-bold text-gray-800 tracking-[0.09em]">
                  Get <span class="text-pink-500">25% Discount</span>
                  <p class="text-3xl md:text-5xl font-bold text-gray-800 tracking-[0.09em] pt-2">on your first</p>
                  <p class="pt-2">purchase</p>
              </h2>
              <p class="text-gray-600 mt-4 md:mt-6 text-base md:text-xl">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Dictumst amet, metus, sit massa posuere maecenas. At tellus ut nunc amet vel egestas.</p>
          </div>
  
          <!-- Right Side - Form -->
          <div class="md:w-1/2 bg-transparent rounded-lg max-w-md mx-auto mb-10 md:mb-0">
              <form action="#" method="POST" class="space-y-4">
                  <div>
                      <label for="name" class="block mb-2 text-gray-700">Name</label>
                      <input type="text" id="name" name="name" placeholder="Name" class="w-full px-4 py-2 border border-blue-100 rounded-md focus:border-none focus:outline-none focus:ring-2 focus:ring-pink-200" />
                  </div>
                  <div>
                      <label for="email" class="block mb-2 text-gray-700">Email</label>
                      <input type="email" id="email" name="email" placeholder="abc@mail.com" class="w-full px-4 py-2 border border-blue-100 rounded-md focus:border-none focus:outline-none focus:ring-2 focus:ring-pink-200" />
                  </div>
  
                  <div class="flex items-center">
                      <input type="checkbox" id="subscribe" name="subscribe" class="text-pink-200 focus:ring-pink-200 border-gray-300 rounded" />
                      <label for="subscribe" class="ml-2 text-gray-600 text-sm md:text-base">Subscribe to the newsletter</label>
                  </div>
                  <div>
                      <button type="submit" class="w-full px-4 py-2 bg-gray-800 text-white rounded-md hover:bg-gray-700 transition duration-300">Submit</button>
                  </div>
              </form>
          </div>
      </div>
    </div>
    {{-- end banner  --}}

  
    
    
@include('components.features')
@include('layouts.footer')