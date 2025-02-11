<!-- navbar -->
<div id="navbar" class="w-full sticky top-0 z-30 bg-gradient-to-r from-pink-100 via-white to-pink-100 border-b border-gray-200 shadow-lg">
  <div class="container mx-auto max-w-screen-2xl">
      <nav class="flex justify-between items-center py-3 px-4">
      <!-- Left Side: Menu Icon  -->
        <div class="flex items-center space-x-4">
          <!-- Menu Icon -->
          <a class="flex items-center text-3xl font-bold font-heading" href="#">
            <img class="h-10 md:h-12 block" src="{{ asset('img/component/LogoJH.png') }}" alt="logo" />
            <span class="text-slate-800 md:flex text-sm md:text-xl lg:text-xl font-semibold mr-1 hover:text-pink-500 transition-colors duration-300">Jims Honey Pati</span>
          </a>
        </div>
        <!-- Navigasi Mid -->
          <ul class="hidden md:flex px-4 mx-auto font-heading space-x-6 lg:space-x-8">
              <li>
              <a href="/home" 
                  class="text-slate-800 font-medium text-base lg:text-lg px-4 py-2 rounded-lg transition-all duration-300 ease-in-out hover:bg-pink-300 hover:text-white {{ Request::is('home') ? 'bg-pink-300 text-white' : '' }}" 
                  aria-current="page" 
                  aria-label="Home">
                  Home
              </a>
              </li>
              <li>
              <a href="/product" 
              class="text-slate-800 font-medium text-base lg:text-lg px-4 py-2 rounded-lg transition-all duration-300 ease-in-out hover:bg-pink-300 hover:text-white 
              {{ Request::is('product*') || Request::is('product-detail*') ? 'bg-pink-300 text-white' : '' }}" 
              aria-label="Product">
                  Product
              </a>
              </li>
              <li>
              <a href="/about" 
                  class="text-slate-800 font-medium text-base lg:text-lg px-4 py-2 rounded-lg transition-all duration-300 ease-in-out hover:bg-pink-300 hover:text-white {{ Request::is('about') ? 'bg-pink-300 text-white' : '' }}" 
                  aria-label="About Us">
                  About Us
              </a>
              </li>
          </ul>
    
    
        <!-- Right Side: Icons (Search, User, Cart) -->
        <div class="flex items-center space-x-4">
          <!-- Search Icon -->
          <div class="relative">
            <button id="openModalButton" class="block text-black focus:outline-none hover:text-pink-500 font-medium rounded-full text-sm py-2 text-center md:h-12 md:w-12 transition-colors duration-300" type="button">
              <svg class="h-6 w-6 md:h-8 md:w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.3" d="M15.5 15.5L20 20M4 10a6 6 0 1112 0a6 6 0 01-12 0z" />
              </svg>
            </button>
            <div id="myModal" class="hidden fixed inset-0 z-50 flex items-center justify-center">
              <!-- Overlay -->
              <div id="modalOverlay" class="absolute inset-0 bg-black bg-opacity-50"></div>
            
              <!-- Modal content -->
              <div class="relative bg-white dark:bg-gray-800 rounded-lg shadow-lg w-full max-w-lg mx-4 p-6">
                <!-- Modal Header -->
                <div class="flex items-center justify-between mb-4">
                  <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Cari Produk</h3>
                  <button id="closeModal" class="text-gray-500 hover:text-gray-700 dark:hover:text-gray-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                  </button>
                </div>
            
                <!-- Search Input -->
                <div class="relative mb-4">
                  <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                      <path
                            d="M15.7955 15.8111L21 21M18 10.5C18 14.6421 14.6421 18 10.5 18C6.35786 18 3 14.6421 3 10.5C3 6.35786 6.35786 3 10.5 3C14.6421 3 18 6.35786 18 10.5Z"
                            stroke="#000000"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"></path>
                    </svg>
                  </span>
                  <input
                    type="text"
                    id="searchInput"
                    class="w-full p-3 pl-10 border border-gray-300 rounded-lg shadow-sm focus:ring focus:ring-pink-400 focus:border-pink-400 text-gray-800"
                    placeholder="Mulai mengetik untuk mencari produk..."
                  />
                </div>
            
                <!-- Search Results -->
                <div class="mb-4">
                  <ul id="searchResults" class="space-y-3">
                    <li class="text-gray-500 dark:text-gray-400"></li>
                  </ul>
                </div>
            
                <!-- Recent Searches -->
                <div>
                  <p class="text-gray-800 dark:text-gray-200 font-semibold mb-2 text-lg">Pencarian Terakhir</p>
                  <div class="max-h-64 overflow-y-auto space-y-3 scrollbar-thin scrollbar-thumb-gray-300 dark:scrollbar-thumb-gray-600 scrollbar-track-gray-100 dark:scrollbar-track-gray-700">
                    <ul id="recentSearchesList" class="space-y-2">
                      <!-- Recent searches -->
                    </ul>
                  </div>
                </div>
              </div>
            </div>
            
          </div>
            
          <!-- Cart Icon -->
          <a class="relative flex items-center text-black hover:text-pink-500 transition-colors duration-300" href="/cart">
            <svg class="!h-6 !w-6 md:!h-7 md:!w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
            <span id="cart-item-count" class="absolute -top-1 -right-1 bg-pink-500 text-white text-xs font-bold rounded-full w-5 h-5 flex items-center justify-center animate-bounce">0</span>

            <script>
                function updateCartItemCount() {
                    fetch('{{ route("cart.itemCount") }}')
                        .then(response => response.json())
                        .then(data => {
                            document.getElementById('cart-item-count').textContent = data.count;
                        })
                        .catch(error => console.error('Error:', error));
                }

                // Call the function to update the count on page load
                updateCartItemCount();
            </script>

          </a>
         
    
          <!-- Button Humberger -->
          <div class="inline-flex items-center relative">
              <button
                id="dropdownButton"
                type="button"
                class="md:hidden inline-flex items-center p-1 w-6 h-6 justify-center text-sm text-gray-500 rounded-lg hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600"
                aria-expanded="true"
                aria-haspopup="true"
              >
                <span class="sr-only">Open main menu</span>
                <svg class="w-5 h-5" aria-hidden="true" fill="none" viewBox="0 0 17 14">
                  <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 1h15M1 7h15M1 13h15" />
                </svg>
              </button>
    
              <!-- Dropdown Menu -->
              <div id="dropdownMenu" class="hidden z-20 absolute right-0 top-8 w-44 origin-top-left bg-white divide-y divide-gray-100 rounded-lg shadow-lg ring-1 ring-black ring-opacity-5 dark:bg-gray-700 dark:divide-gray-600">
                
                <a href="{{ Auth::check() ? '/profile/' . strtolower(Auth::user()->first_name) : '/login' }}" class="block px-4 py-3 hover:bg-gray-100 dark:hover:bg-gray-600">
                  <span class="block text-sm text-gray-900 dark:text-white"> 
                    
                    @auth
                    Halo, {{ Auth::user()->first_name }} {{ Auth::user()->last_name }}
                    @else
                    Guest
                    @endauth
                  </span>

                  <span class="block text-sm text-gray-500 truncate dark:text-gray-400">
                    @auth
                    {{ Auth::user()->email }}
                    @endauth</span>
                </a>
                <ul class="py-2 text-sm text-gray-700 dark:text-gray-200">
                  <li>
                    <a href="/home" class="block px-4 py-2 hover:bg-pink-300 hover:text-white dark:hover:bg-gray-600 dark:hover:text-white {{ Request::is('home') ? 'bg-pink-300 text-white' : '' }}">Home</a>
                  </li>
                  <li>
                    <a href="/product" class="block px-4 py-2 hover:bg-pink-300 hover:text-white dark:hover:bg-gray-600 dark:hover:text-white {{ Request::is('product') ? 'bg-pink-300 text-white' : '' }}">Product</a>
                  </li>
                  <li>
                    <a href="/about" class="block px-4 py-2 hover:bg-pink-300 hover:text-white dark:hover:bg-gray-600 dark:hover:text-white {{ Request::is('about') ? 'bg-pink-300 text-white' : '' }}">About Us</a>
                  </li>
                </ul>
                <div class="py-2">
                  @if (Auth::check())
                  {{-- Jika pengguna sudah login, tampilkan tombol Logout --}}
                  <a href="{{ route('logout') }}" class="flex items-center px-4 py-2 text-sm text-gray-700"  onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <img src="{{ asset('img/svg/sign.svg') }}" alt="logout" class="mr-2" />
                    Logout
                  </a>
                  {{-- Form untuk Logout --}}
                  <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                    @csrf
                  </form>
                  @else
                  <a href="/login" class="flex items-center px-4 py-2 text-sm text-gray-700">
                    <img src="{{ asset('img/svg/sign.svg') }}" alt="logout" class="mr-2" />
                    Login
                  </a>
                  @endif
                </div>
              </div>
            </div>
    
            <!-- profile -->
            <div class="hidden md:flex items-center md:order-2 space-x-3 md:space-x-0 rtl:space-x-reverse">
              <button
                type="button"
                class="flex text-sm ring-2 ring-red-300 bg-slate-400 rounded-full md:me-0 focus:ring-4 focus:ring-slate-600 dark:focus:ring-gray-600"
                id="user-menu-button"
                aria-expanded="false"
                data-dropdown-toggle="user-dropdown"
                data-dropdown-placement="bottom"
              >
                <span class="sr-only">Open user menu</span>
                <img class="w-10 h-10 rounded-full"  src="{{ Auth::user() && Auth::user()->image ? asset('storage/avatars/' . Auth::user()->image) : asset('img/component/profile_default.png') }}"  alt="user photo" />
              </button>
              <!-- Dropdown menu -->
              <div class="z-50 hidden my-4 text-base list-none bg-white divide-y divide-gray-100 rounded-lg shadow dark:bg-gray-700 dark:divide-gray-600" id="user-dropdown">
                
                <a href="{{ Auth::check() ? '/profile/' . strtolower(Auth::user()->first_name) : '/login' }}" class="block px-4 py-3 hover:bg-gray-100 dark:hover:bg-gray-600">
                  <span class="block text-sm text-gray-900 dark:text-white"> 
                    
                    @auth
                    Halo, {{ Auth::user()->first_name }} {{ Auth::user()->last_name }}
                    @else
                    Guest
                    @endauth
                  </span>

                  <span class="block text-sm text-gray-500 truncate dark:text-gray-400">
                    @auth
                    {{ Auth::user()->email }}
                    @endauth</span>
                </a>
                <ul class="py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white" aria-labelledby="user-menu-button">
                  <!-- Logout with SVG Icon -->
                  @if (Auth::check())
                    {{-- Jika pengguna sudah login, tampilkan tombol Logout --}}
                    <a href="{{ route('logout') }}" 
                      class="flex items-center px-4 py-2 text-sm text-gray-700"
                      onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <img src="{{ asset('img/svg/sign.svg') }}" class="mr-2" />
                        Logout
                    </a>

                    {{-- Form untuk Logout --}}
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                        @csrf
                    </form>
                    @else
                        {{-- Jika pengguna belum login, tampilkan tombol Login --}}
                        <a href="/login" class="flex items-center px-4 py-2 text-sm text-gray-700">
                            <img src="{{ asset('img/svg/sign.svg') }}" class="mr-2" />
                            Login
                        </a>
                    @endif
                </ul>
              </div>
            </div>
        </div>
      </nav>
    </div>
</div>
  