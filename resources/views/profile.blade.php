@include('layouts.header')
<section class="bg-white py-8 antialiased dark:bg-gray-900 md:py-8">
    <div class="mx-auto max-w-screen-lg px-4 2xl:px-0">
      <nav class="mb-4 flex" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
          <li class="inline-flex items-center">
            <a href="/home" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-primary-600 dark:text-gray-400 dark:hover:text-white">
              <svg class="me-2 h-4 w-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m4 12 8-8 8 8M6 10.5V19a1 1 0 0 0 1 1h3v-3a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v3h3a1 1 0 0 0 1-1v-8.5" />
              </svg>
              Home
            </a>
          </li>
          <li>
            <div class="flex items-center">
              <svg class="mx-1 h-4 w-4 text-gray-400 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m9 5 7 7-7 7" />
              </svg>
              <a href="#" class="ms-1 text-sm font-medium text-gray-700 hover:text-primary-600 dark:text-gray-400 dark:hover:text-white md:ms-2">My account</a>
            </div>
          </li>
          <li aria-current="page">
            <div class="flex items-center">
              <svg class="mx-1 h-4 w-4 text-gray-400 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m9 5 7 7-7 7" />
              </svg>
              <span class="ms-1 text-sm font-medium text-gray-500 dark:text-gray-400 md:ms-2">Account</span>
            </div>
          </li>
        </ol>
      </nav>
      <h2 class="mb-4 text-xl font-semibold text-gray-900 dark:text-white sm:text-2xl md:mb-6">General overview</h2>
      <div class="grid grid-cols-2 gap-6 border-b border-t border-gray-200 py-4 dark:border-gray-700 md:py-8 lg:grid-cols-4 xl:gap-16">
        <div>
          <svg class="mb-2 h-8 w-8 text-gray-400 dark:text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
              <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 4h1.5L9 16m0 0h8m-8 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4Zm8 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4Zm-8.5-3h9.25L19 7H7.312" />
          </svg>
          <h3 class="mb-2 text-gray-500 dark:text-gray-400">Pembelian</h3>
          <span class="flex items-center text-2xl font-bold text-gray-900 dark:text-white">
              {{ $totalOrdersThisMonth }}
              @if($orderPercentageChange > 0)
                  <span class="ms-2 inline-flex items-center rounded bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800 dark:bg-green-900 dark:text-green-300">
                      <svg class="-ms-1 me-1 h-4 w-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                          <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v13m0-13 4 4m-4-4-4 4"></path>
                      </svg>
                      {{ number_format($orderPercentageChange, 2) }}%  
                  </span>
              @elseif($orderPercentageChange < 0)
                  <span class="ms-2 inline-flex items-center rounded bg-red-100 px-2.5 py-0.5 text-xs font-medium text-red-800 dark:bg-red-900 dark:text-red-300">
                      <svg class="-ms-1 me-1 h-4 w-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                          <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18V5m0 13-4-4m4 4 4-4"></path>
                      </svg>
                      {{ number_format(abs($orderPercentageChange), 2) }}%
                  </span>
              @else
                  <span class="ms-2 inline-flex items-center rounded bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-800 dark:bg-gray-900 dark:text-gray-300">
                      0%
                  </span>
              @endif
          </span>
        
          <p class="mt-2 flex items-center text-sm text-gray-500 dark:text-gray-400 sm:text-base">
              <svg class="me-1.5 h-4 w-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                  <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 11h2v5m-2 0h4m-2.592-8.5h.01M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
              </svg>
              Total bulan lalu: {{ $totalOrdersLastMonth }}
          </p>
      </div>
      
      
      <div>
        <svg class="mb-2 h-8 w-8 text-gray-400 dark:text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
          <path stroke="currentColor" stroke-width="2" d="M11.083 5.104c.35-.8 1.485-.8 1.834 0l1.752 4.022a1 1 0 0 0 .84.597l4.463.342c.9.069 1.255 1.2.556 1.771l-3.33 2.723a1 1 0 0 0-.337 1.016l1.03 4.119c.214.858-.71 1.552-1.474 1.106l-3.913-2.281a1 1 0 0 0-1.008 0L7.583 20.8c-.764.446-1.688-.248-1.474-1.106l1.03-4.119A1 1 0 0 0 6.8 14.56l-3.33-2.723c-.698-.571-.342-1.702.557-1.771l4.462-.342a1 1 0 0 0 .84-.597l1.753-4.022Z"/>
        </svg>
        <h3 class="mb-2 text-gray-500 dark:text-gray-400">Review</h3>
        <span class="flex items-center text-2xl font-bold text-gray-900 dark:text-white">
          {{ $totalReviewsThisMonth }}
          <span class="ms-2 inline-flex items-center rounded 
            {{ $reviewPercentageChange >= 0 ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300' }} 
            px-2.5 py-0.5 text-xs font-medium">
            <svg class="-ms-1 me-1 h-4 w-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
              <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v13m0-13 4 4m-4-4-4 4"></path>
            </svg>
            {{ number_format(abs($reviewPercentageChange), 1, ',', '') }}%
          </span>
        </span>
        <p class="mt-2 flex items-center text-sm text-gray-500 dark:text-gray-400 sm:text-base">
          <svg class="me-1.5 h-4 w-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 11h2v5m-2 0h4m-2.592-8.5h.01M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
          </svg>
          total bulan lalu {{ $totalReviewsLastMonth }}
        </p>
      </div>
      

        <div>
            <svg class="mb-2 h-8 w-8 text-gray-400 dark:text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v13m0-13 4 4m-4-4-4 4" />
            </svg>
            <h3 class="mb-2 text-gray-500 dark:text-gray-400">Total Belanja</h3>
            <span class="flex items-center text-2xl font-bold text-gray-900 dark:text-white">
                {{ $formattedThisMonth }}
                @if($spendingPercentageChange > 0)
                    <span class="ms-2 inline-flex items-center rounded bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800 dark:bg-green-900 dark:text-green-300">
                        <svg class="-ms-1 me-1 h-4 w-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v13m0-13 4 4m-4-4-4 4"></path>
                        </svg>
                        {{ number_format($spendingPercentageChange, 2) }}%  
                    </span>
                @elseif($spendingPercentageChange < 0)
                    <span class="ms-2 inline-flex items-center rounded bg-red-100 px-2.5 py-0.5 text-xs font-medium text-red-800 dark:bg-red-900 dark:text-red-300">
                        <svg class="-ms-1 me-1 h-4 w-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18V5m0 13-4-4m4 4 4-4"></path>
                        </svg>
                        {{ number_format(abs($spendingPercentageChange), 2) }}%
                    </span>
                @else
                    <span class="ms-2 inline-flex items-center rounded bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-800 dark:bg-gray-900 dark:text-gray-300">
                        0%
                    </span>
                @endif
            </span>
            <p class="mt-2 flex items-center text-sm text-gray-500 dark:text-gray-400 sm:text-base">
                <svg class="me-1.5 h-4 w-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 11h2v5m-2 0h4m-2.592-8.5h.01M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
                bulan lalu Rp. {{ $formattedLastMonth }}
            </p>
        </div>
      
        <div>
          <svg class="mb-2 h-8 w-8 text-gray-400 dark:text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <path stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
              d="M12 17a5 5 0 1 0 0-10 5 5 0 0 0 0 10Zm0 0v4m-3 0h6M8 2h8l-2 5h-4l-2-5Z" />
          </svg>
          <h3 class="mb-2 text-gray-500 dark:text-gray-400">Level reseller</h3>
          <span class="flex items-center text-2xl font-bold text-gray-900 dark:text-white"
            >
            <span id="resellerLevel">{{ $resellerLevel }}</span></p>
            
          </span>
          <p id="progress-text" class="mt-2 flex items-center text-sm text-gray-500 dark:text-gray-400 sm:text-base">
              <svg class="me-1.5 h-4 w-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                  <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 11h2v5m-2 0h4m-2.592-8.5h.01M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
              </svg>
              Rp <span id="remaining-amount">0</span>&nbsp;lagi ke&nbsp;<span id="next-level">Silver</span>
          </p>
        
        </div>
      </div>
    
      
      <div class="py-4 md:py-8">
        <div class="mb-4 grid gap-4 sm:grid-cols-2 sm:gap-8 lg:gap-16">
          <!-- Account Information Section -->
          <div class="space-y-4">
            <div class="flex space-x-4">
                <form id="uploadImageForm" enctype="multipart/form-data">
                 
                    @csrf
                    <!-- Input gambar tersembunyi -->
                    <input type="file" id="imageInput" name="image" accept="image/*" class="hidden">
                
                    <!-- Avatar yang bisa diklik -->
                    <label for="imageInput" class="cursor-pointer">
                        <img id="preview" class="h-16 w-16 rounded-lg object-cover" 
                            src="{{ $user->image ? asset('storage/avatars/' . $user->image) : asset('img/component/profile_default.png') }}" 
                            alt="User Avatar">
                    </label>
                  
                </form>
                <script>
                  document.getElementById('imageInput').addEventListener('change', function(event) {
                      let formData = new FormData();
                      formData.append('image', event.target.files[0]);
                  
                      fetch("{{ route('profile.upload') }}", {
                          method: "POST",
                          body: formData,
                          headers: {
                              "X-CSRF-TOKEN": document.querySelector('input[name=\"_token\"]').value
                          }
                      })
                      .then(response => response.json())
                      .then(data => {
                          if (data.success) {
                              document.getElementById('preview').src = data.image;
                              alert('Gambar berhasil diperbarui!');
                          } else {
                              alert('Upload gagal!');
                          }
                      })
                      .catch(error => console.error('Error:', error));
                  });
                  </script> 
            
            

              <div>
                <span class="mb-2 inline-block rounded bg-yellow-300 px-2.5 py-0.5 text-xs font-medium text-yellow-900 dark:bg-yellow-700 dark:text-yellow-200">
                  @auth
                      <p id="userRole">
                          @if (Auth::user()->hasRole('Reseller'))
                              Reseller
                          @else
                              Customer
                          @endif
                      </p>
                  @endauth
                </span>
                <h2 class="flex items-center text-xl font-bold leading-none text-gray-900 dark:text-white sm:text-2xl">{{ Auth::user()->first_name . ' ' . Auth::user()->last_name }}</h2>
                
              </div>
            </div>
            <dl>
              <dt class="font-semibold text-gray-900 dark:text-white">Email Address</dt>
              <dd class="text-gray-500 dark:text-gray-400">{{ Auth::user()->email }}</dd>
            </dl>
            <dl>
              <dt class="font-semibold text-gray-900 dark:text-white">address</dt>
              <dd class="flex items-center gap-1 text-gray-500 dark:text-gray-400">
                <svg class="hidden h-5 w-5 shrink-0 text-gray-400 dark:text-gray-500 lg:inline" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                  <path
                    stroke="currentColor"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M13 7h6l2 4m-8-4v8m0-8V6a1 1 0 0 0-1-1H4a1 1 0 0 0-1 1v9h2m8 0H9m4 0h2m4 0h2v-4m0 0h-5m3.5 5.5a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0Zm-10 0a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0Z"
                  />
                </svg>
                {{ Auth::user()->address }}
              </dd>
            </dl>
            <dl>
              <dt class="font-semibold text-gray-900 dark:text-white">Phone Number</dt>
              <dd class="text-gray-500 dark:text-gray-400">{{ Auth::user()->phone_number }}</dd>
            </dl>
          </div>

          <!-- Progress Bar Section -->
          <div class="max-w-sm p-6 bg-white border border-gray-200 rounded-lg shadow-md dark:bg-gray-800 dark:border-gray-700">
            <div class="flex flex-col space-y-4">
              <!-- Judul -->
              <p class="text-gray-600 dark:text-gray-300 text-lg font-semibold text-center">
                Total Pembelian Bulan Ini
              </p>
          
              <!-- Informasi Tambahan -->
              <div class="text-center">
                <p class="text-gray-500 dark:text-gray-400 text-sm">
                    Anda telah mencapai <span class="font-medium text-green-500 progress-text">0%</span> dari target pembelian.
                </p>
                <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">
                    Sisa hari: <span id="days-left" class="font-medium">15 hari</span>
                </p>
            </div>
            
          
              <!-- Progress Bar with Label -->
              <div class="w-full mt-4">
                <div class="w-full bg-gray-100 rounded-full h-2.5 dark:bg-gray-700 relative">
                  <div class="bg-green-500 h-2.5 rounded-full progress-bar" style="width: 0%"></div>
                </div>
                <div class="flex justify-between w-full mt-2 text-sm text-gray-500 dark:text-gray-400">
                  <span class="spent-amount">Rp0</span>
                  <span class="target-amount">Rp1.000.000</span>
                </div>
              </div>
          
              <!-- Call to Action Link -->
              <a href="#" class="mt-4 inline-flex items-center justify-center text-blue-600 hover:text-blue-700 font-medium dark:text-blue-400 dark:hover:text-blue-300 transition-colors">
                Lihat Detail Pembelian
                <svg class="w-5 h-5 ml-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                  <path fill-rule="evenodd" d="M12.293 9.293a1 1 0 011.414 0l3 3a1 1 0 010 1.414l-3 3a1 1 0 01-1.414-1.414L13.586 13H4a1 1 0 110-2h9.586l-1.293-1.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
              </a>
          
              <!-- Tips atau Informasi Tambahan -->
              <div class="mt-4 p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                <p class="text-gray-600 dark:text-gray-300 text-sm">
                  💡 Tips: Manfaatkan promo bulan ini untuk mencapai target pembelian lebih cepat!
                </p>
              </div>
            </div>
          </div>
        </div>

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script>
            function updateProgressBar() {
                $.ajax({
                    url: "{{ route('progress.data') }}",
                    type: "GET",
                    success: function(response) {
                        if (response.progressPercentage) {
                            $(".progress-bar").css("width", response.progressPercentage + "%");
                            $(".progress-text").text(response.progressPercentage + "%");
                            $(".spent-amount").text("Rp" + response.currentSpent);
                            $(".target-amount").text("Rp" + response.target);
                        }
                    },
                    error: function(xhr) {
                        console.log("Error:", xhr);
                    }
                });
            }

            // Jalankan update setiap 5 detik
            setInterval(updateProgressBar, 5000);

            // Panggil saat halaman pertama kali dimuat
            $(document).ready(function() {
                updateProgressBar();
            });
        </script>
        <script>
          function updateProgressText() {
              $.ajax({
                  url: "{{ route('progress.data') }}",
                  type: "GET",
                  success: function(response) {
                      if (response.readyForUpgrade) {
                          $("#progress-text").html(`
                            <svg class="me-1.5 h-4 w-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 11h2v5m-2 0h4m-2.592-8.5h.01M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            </svg>
                            Level siap dinaikkan
                            `);
                      } else {
                          $("#remaining-amount").text(response.remainingAmount.trim());
                          $("#next-level").text(response.nextLevel.trim());
                          $("#progress-text").html(`Rp <span id="remaining-amount">${response.remainingAmount.trim()}</span>&nbsp;lagi ke&nbsp;<span id="next-level">${response.nextLevel.trim()}</span>`);
                      }
                  },
                  error: function(xhr) {
                      console.log("Error:", xhr);
                  }
              });
          }

          $(document).ready(updateProgressText);
          setInterval(updateProgressText, 5000);

          $(document).ready(function() {
              updateProgressBar();
              updateProgressText();
              updateDaysLeft();
          });

          function updateDaysLeft() {
              $.ajax({
                  url: "{{ route('progress.data') }}",
                  type: "GET",
                  success: function(response) {
                      if (response.daysLeftInMonth) {
                          $("#days-left").text(response.daysLeftInMonth + " hari");
                      }
                  },
                  error: function(xhr) {
                      console.log("Error:", xhr);
                  }
              });
          }

          setInterval(updateDaysLeft, 5000);

      </script>

        <div class="flex flex-wrap gap-2">
          <!-- Edit Data Button -->
          <button
            type="button"
            data-modal-target="accountInformationModal2"
            data-modal-toggle="accountInformationModal2"
            class="inline-flex items-center justify-center rounded-lg bg-primary-700 px-5 py-2.5 text-sm font-medium text-white hover:bg-primary-800 focus:outline-none focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800"
          >
            <svg class="-ms-0.5 me-1.5 h-4 w-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
              <path
                stroke="currentColor"
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="m14.304 4.844 2.852 2.852M7 7H4a1 1 0 0 0-1 1v10a1 1 0 0 0 1 1h11a1 1 0 0 0 1-1v-4.5m2.409-9.91a2.017 2.017 0 0 1 0 2.853l-6.844 6.844L8 14l.713-3.565 6.844-6.844a2.015 2.015 0 0 1 2.852 0Z"
              />
            </svg>
            Edit your data
          </button>
        
          <!-- Upgrade Reseller Button -->
          @if (Auth::user()->hasRole('Reseller'))
            <button
              type="button"
              id="upgradeResellerButton"
              class="inline-flex items-center justify-center rounded-lg bg-green-600 px-5 py-2.5 text-sm font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-4 focus:ring-green-300 dark:bg-green-500 dark:hover:bg-green-600 dark:focus:ring-green-800"
            >
              Upgrade Reseller
            </button>
          @endif
        
          <!-- Join Reseller Button -->
          @if (Auth::user()->hasRole('Customer'))
            <button
              type="button"
              id="joinResellerButton"
              class="inline-flex items-center justify-center rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-300 dark:bg-blue-500 dark:hover:bg-blue-600 dark:focus:ring-blue-800"
            >
              Join Reseller
            </button>
          @endif
        </div>
          <script>
            document.getElementById('joinResellerButton')?.addEventListener('click', function() {
                fetch('/join-reseller', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({})
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('userRole').textContent = 'Reseller'; // Update teks role
                        alert(data.success); // Notifikasi sukses
            
                        // Sembunyikan tombol Join Reseller
                        document.getElementById('joinResellerButton').style.display = 'none';
            
                        // Tampilkan tombol Upgrade Reseller
                        let upgradeButton = document.getElementById('upgradeResellerButton');
                        if (upgradeButton) {
                            upgradeButton.style.display = 'inline-flex';
                        } else {
                            // Jika tidak ada di awal, buat tombolnya
                            let newButton = document.createElement('button');
                            newButton.id = 'upgradeResellerButton';
                            newButton.textContent = 'Upgrade Reseller';
                            newButton.className = 'inline-flex w-full items-center justify-center  rounded-lg bg-green-600 px-5 py-2.5 text-sm font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-4 focus:ring-green-300 dark:bg-green-500 dark:hover:bg-green-600 dark:focus:ring-green-800 sm:w-auto';
                            document.getElementById('joinResellerButton').after(newButton);
                        }
                    } else {
                        alert(data.error || 'Gagal mengubah peran.');
                    }
                })
                .catch(error => console.error('Error:', error));
            });

            document.getElementById('upgradeResellerButton')?.addEventListener('click', function() {
                fetch('/upgrade-reseller', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({})
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.success); // Tampilkan notifikasi sukses

                        // // **Perbarui teks level reseller di halaman tanpa refresh**
                        // let levelElement = document.getElementById('resellerLevel');
                        // if (levelElement) {
                        //     levelElement.textContent = data.newLevel;
                        // }
                        // Update teks level reseller di halaman tanpa refresh
                        document.getElementById('resellerLevel').textContent = data.newLevel;
                    } else {
                        alert(data.error || 'Gagal meng-upgrade level.');
                    }
                })
                .catch(error => console.error('Error:', error));
            });


          </script>
            

      </div>
    

      <div class="rounded-lg border border-gray-200 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-800 md:p-8">
        <h3 class="mb-4 text-xl font-semibold text-gray-900 dark:text-white">Latest orders</h3>
        @foreach ($orders as $order)
        <div class="flex flex-wrap items-center gap-y-4 border-b border-gray-200 pb-4 dark:border-gray-700 md:pb-5">
          <dl class="w-1/2 sm:w-48">
            <dt class="text-base font-medium text-gray-500 dark:text-gray-400">Invoice:</dt>
            <dd class="mt-1.5 text-base font-semibold text-gray-900 dark:text-white">
              <a href="#" class="hover:underline">{{ $order->invoice_number }}</a>
            </dd>
          </dl>

          <dl class="w-1/2 sm:w-1/4 md:flex-1 lg:w-auto">
            <dt class="text-base font-medium text-gray-500 dark:text-gray-400">Tanggal:</dt>
            <dd class="mt-1.5 text-base font-semibold text-gray-900 dark:text-white">{{ $order->created_at->format('d.m.Y') }}</dd>
          </dl>

          <dl class="w-1/2 sm:w-1/5 md:flex-1 lg:w-auto">
            <dt class="text-base font-medium text-gray-500 dark:text-gray-400">Total Harga:</dt>
            <dd class="mt-1.5 text-base font-semibold text-gray-900 dark:text-white">Rp{{ number_format($order->total_price, 0, ',', '.') }}</dd>
          </dl>
          
          @if (isset($payments[$order->id]))
            @switch($payments[$order->id]->payment_status)
                @case('pending')
                    <dl class="w-1/2 sm:w-1/4 sm:flex-1 lg:w-auto">
                        <dt class="text-base font-medium text-gray-500 dark:text-gray-400">Status Pembayaran:</dt>
                        <dd class="mt-1.5 inline-flex items-center rounded bg-yellow-100 px-2.5 py-0.5 text-xs font-medium text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300">
                            <svg class="me-1 h-3 w-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Pending
                        </dd>
                    </dl>
                    @break
                @case('dibayar')
                    <dl class="w-1/2 sm:w-1/4 sm:flex-1 lg:w-auto">
                        <dt class="text-base font-medium text-gray-500 dark:text-gray-400">Status Pembayaran:</dt>
                        <dd class="me-2 mt-1.5 inline-flex shrink-0 items-center rounded bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800 dark:bg-green-900 dark:text-green-300">
                          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                          </svg>
                            Dibayar
                        </dd>
                    </dl>
                    @break
                @default
                    <p class="text-gray-600">Status Pembayaran: Tidak Diketahui</p>
            @endswitch
        @else
            <p class="text-gray-600">Status Pembayaran: Data Pembayaran Tidak Tersedia</p>
        @endif

          @switch($order->status->id)
                @case(1)
                  <dl class="w-1/2 sm:w-1/4 sm:flex-1 lg:w-auto">
                      <dt class="text-base font-medium text-gray-500 dark:text-gray-400">Status Pemesanan:</dt>
                      <dd class="mt-1.5 inline-flex items-center rounded bg-yellow-100 px-2.5 py-0.5 text-xs font-medium text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300">
                          <svg class="me-1 h-3 w-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                              <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                          </svg>
                          Diproses
                      </dd>
                  </dl>
                  @break
                @case(2)
                  <dl class="w-1/2 sm:w-1/4 sm:flex-1 lg:w-auto">
                      <dt class="text-base font-medium text-gray-500 dark:text-gray-400">Status:</dt>
                      <dd class="me-2 mt-1.5 inline-flex shrink-0 items-center rounded bg-blue-100 px-2.5 py-0.5 text-xs font-medium text-blue-800 dark:bg-blue-900 dark:text-blue-300">
                          <svg class="me-1 h-3 w-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                              <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h6l2 4m-8-4v8m0-8V6a1 1 0 0 0-1-1H4a1 1 0 0 0-1 1v9h2m8 0H9m4 0h2m4 0h2v-4m0 0h-5m3.5 5.5a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0Zm-10 0a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0Z"></path>
                          </svg>
                          Siap Diambil
                      </dd>
                  </dl>
                  @break
                @case(3)
                  <dl class="w-1/2 sm:w-1/4 sm:flex-1 lg:w-auto">
                      <dt class="text-base font-medium text-gray-500 dark:text-gray-400">Status:</dt>
                      <dd class="mt-1.5 inline-flex items-center rounded bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800 dark:bg-green-900 dark:text-green-300">
                          <svg class="me-1 h-3 w-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                              <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 11.917 9.724 16.5 19 7.5"></path>
                          </svg>
                          Selesai
                      </dd>
                  </dl>
                  @break
                @case(4)
                  <dl class="w-1/2 sm:w-1/4 sm:flex-1 lg:w-auto">
                      <dt class="text-base font-medium text-gray-500 dark:text-gray-400">Status:</dt>
                      <dd class="mt-1.5 inline-flex items-center rounded bg-red-100 px-2.5 py-0.5 text-xs font-medium text-red-800 dark:bg-red-900 dark:text-red-300">
                          <svg class="me-1 h-3 w-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                              <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18 17.94 6M18 18 6.06 6"></path>
                          </svg>
                          Cancelled
                      </dd>
                  </dl>
                  @break  
            @default
              <p>Status tidak diketahui</p>
          @endswitch

          @php
              $dropdownId = 'dropdownOrderModal' . $order->id;
              $buttonId = 'actionsMenuDropdownModal' . $order->id;
          @endphp

          <div class="relative w-full sm:w-32 sm:items-center sm:justify-end sm:gap-4">
            <!-- Tombol Actions -->
            <button
              id="{{ $buttonId }}"
              type="button"
              class="flex w-full items-center justify-center rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm font-medium text-gray-900 hover:bg-gray-100 hover:text-primary-700 focus:outline-none focus:ring-4 focus:ring-gray-100 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white dark:focus:ring-gray-700 md:w-auto"
              onclick="toggleDropdown('{{ $dropdownId }}')"
            >
              Actions
              <svg class="-me-0.5 ms-1.5 h-4 w-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 9-7 7-7-7"></path>
              </svg>
            </button>
          
            <!-- Dropdown Menu sebagai Modal -->
            <div id="{{ $dropdownId }}" class="absolute left-0 mt-2  w-48 z-50 hidden rounded-lg bg-white shadow-lg dark:bg-gray-700">
              <ul class="p-2 text-left text-sm font-medium text-gray-500 dark:text-gray-400">
                <li>
                  <a href="#" class="group flex items-center rounded-md px-3 py-2 hover:bg-gray-100 hover:text-gray-900 dark:hover:bg-gray-600 dark:hover:text-white">
                    <svg class="me-1.5 h-4 w-4 text-gray-400 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                      <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.651 7.65a7.131 7.131 0 0 0-12.68 3.15M18.001 4v4h-4m-7.652 8.35a7.13 7.13 0 0 0 12.68-3.15M6 20v-4h4"></path>
                    </svg>
                    Order again
                  </a>
                </li>
                <li>
                  <a  href="{{ route('order.order-detail', $order->id) }}" class="group flex items-center rounded-md px-3 py-2 hover:bg-gray-100 hover:text-gray-900 dark:hover:bg-gray-600 dark:hover:text-white">
                    <svg class="me-1.5 h-4 w-4 text-gray-400 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                      <path stroke="currentColor" stroke-width="2" d="M21 12c0 1.2-4.03 6-9 6s-9-4.8-9-6c0-1.2 4.03-6 9-6s9 4.8 9 6Z"></path>
                      <path stroke="currentColor" stroke-width="2" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"></path>
                    </svg>
                    Order details
                  </a>
                </li>
                <!-- Tombol Cancel Order hanya muncul jika status order adalah 1 (Diproses) -->
                @if ($order->status->id == 1)
                    <li>
                        <a
                            href="#"
                            class="group flex items-center rounded-md px-3 py-2 text-red-600 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white"
                            onclick="cancelOrder({{ $order->id }})"
                        >
                            <svg class="me-1.5 h-4 w-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 7h14m-9 3v8m4-8v8M10 3h4a1 1 0 0 1 1 1v3H9V4a1 1 0 0 1 1-1ZM6 7h12v13a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1V7Z"></path>
                            </svg>
                            Cancel order
                        </a>
                    </li>
                @endif
              </ul>
            </div>
          </div>
          
          <script>
            function toggleDropdown(id) {
              var dropdown = document.getElementById(id);
              
              document.querySelectorAll(".absolute.z-50").forEach((el) => {
                if (el.id !== id) {
                  el.classList.add("hidden");
                }
              });

              // Toggle dropdown saat tombol diklik
              dropdown.classList.toggle("hidden");
            }

            function cancelOrder(orderId) {
                if (confirm("Apakah Anda yakin ingin membatalkan pesanan ini?")) {
                    fetch(`/orders/${orderId}/cancel`, {
                        method: "PUT",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": "{{ csrf_token() }}", // Tambahkan CSRF token untuk keamanan
                        },
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert("Pesanan berhasil dibatalkan.");
                            location.reload(); // Muat ulang halaman untuk memperbarui tampilan
                        } else {
                            alert(data.error || "Gagal membatalkan pesanan.");
                        }
                    })
                    .catch(error => {
                        console.error("Error:", error);
                        alert("Terjadi kesalahan saat membatalkan pesanan.");
                    });
                }
            }

            // Menutup dropdown saat klik di luar elemen dropdown
            document.addEventListener("click", function (event) {
              const dropdowns = document.querySelectorAll(".absolute.z-50");
              let isClickInside = false;

              dropdowns.forEach((dropdown) => {
                if (dropdown.contains(event.target) || event.target.closest("button[onclick^='toggleDropdown']")) {
                  isClickInside = true;
                }
              });

              if (!isClickInside) {
                dropdowns.forEach((dropdown) => {
                  dropdown.classList.add("hidden");
                });
              }
            });

          </script>
        </div>
        @endforeach
        
      </div>
    </div>
    <!-- Account Information Modal -->
    <div
      id="accountInformationModal2"
      tabindex="-1"
      aria-hidden="true"
      class="max-h-auto fixed left-0 right-0 top-0 z-50 hidden h-[calc(100%-1rem)] max-h-full w-full items-center justify-center overflow-y-auto overflow-x-hidden antialiased md:inset-0"
    >
      <div class="max-h-auto relative max-h-full w-full max-w-lg p-4">
        <!-- Modal content -->
        <div class="relative rounded-lg bg-white shadow dark:bg-gray-800">
          <!-- Modal header -->
          <div class="flex items-center justify-between rounded-t border-b border-gray-200 p-4 dark:border-gray-700 md:p-5">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Account Information</h3>
            <button
              type="button"
              class="ms-auto inline-flex h-8 w-8 items-center justify-center rounded-lg bg-transparent text-sm text-gray-400 hover:bg-gray-200 hover:text-gray-900 dark:hover:bg-gray-600 dark:hover:text-white"
              data-modal-toggle="accountInformationModal2"
            >
              <svg class="h-3 w-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
              </svg>
              <span class="sr-only">Close modal</span>
            </button>
          </div>
          <!-- Modal body -->
          <form class="p-4 md:p-5" action="{{ route('profile.update') }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-5 grid grid-cols-1 gap-4 sm:grid-cols-2">
              <div class="col-span-2 sm:col-span-1">
                <label for="first_name_info_modal" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white"> Your First Name* </label>
                <input
                  type="text"
                  id="first_name_info_modal"
                  name="first_name"
                  class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder:text-gray-400 dark:focus:border-primary-500 dark:focus:ring-primary-500"
                  placeholder="Enter your first name"
                  value="{{ old('last_name', Auth::user()->first_name) }}"
                  required
                />
              </div>
              <div class="col-span-2 sm:col-span-1">
                <label for="last_name_info_modal" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white"> Your Last Name* </label>
                <input
                  type="text"
                  id="last_name_info_modal"
                  name="last_name"
                  class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder:text-gray-400 dark:focus:border-primary-500 dark:focus:ring-primary-500"
                  placeholder="Enter your first name"
                  value="{{ old('last_name', Auth::user()->last_name) }}"
                  required
                />
              </div>

              <div class="col-span-2">
                <label for="email_info_modal" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white"> Your Email* </label>
                <input
                  type="text"
                  id="email_info_modal"
                  name="email"
                  class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder:text-gray-400 dark:focus:border-primary-500 dark:focus:ring-primary-500"
                  placeholder="Enter your email here"
                  value="{{ old('last_name', Auth::user()->email) }}"
                  required
                />
              </div>

              <div class="col-span-2">
                <label for="phone-input" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white"> Phone Number* </label>
                <div class="flex items-center">
                  <div class="relative w-full">
                    <input
                      type="number"
                      id="phone-input"
                      name="phone_number"
                      class="z-20 block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder:text-gray-400 dark:focus:border-primary-500"
                      pattern="[0-9]{3}-[0-9]{3} -[0-9]{4}"
                      placeholder="123-456-7890"
                      value="{{ old('last_name', Auth::user()->phone_number) }}"
                      required
                    />
                  </div>
                </div>
              </div>

              <div class="col-span-2">
                <label for="address_billing_modal" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Address* </label>
                <textarea
                  id="address_billing_modal"
                  name="address"
                  rows="4"
                  class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder:text-gray-400 dark:focus:border-primary-500 dark:focus:ring-primary-500"
                  placeholder="Enter here your address"
                  value="{{ old('last_name', Auth::user()->address) }}"
                ></textarea>
              </div>
            </div>
            <div class="border-t border-gray-200 pt-4 dark:border-gray-700 md:pt-5">
              <button
                type="submit"
                class="me-2 inline-flex items-center rounded-lg bg-primary-700 px-5 py-2.5 text-center text-sm font-medium text-white hover:bg-primary-800 focus:outline-none focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800"
              >
                Save information
              </button>
              <button
                type="button"
                data-modal-toggle="accountInformationModal2"
                class="me-2 rounded-lg border border-gray-200 bg-white px-5 py-2.5 text-sm font-medium text-gray-900 hover:bg-gray-100 hover:text-primary-700 focus:z-10 focus:outline-none focus:ring-4 focus:ring-gray-100 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white dark:focus:ring-gray-700"
              >
                Cancel
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <div id="deleteOrderModal" tabindex="-1" aria-hidden="true" class="fixed left-0 right-0 top-0 z-50 hidden h-modal w-full items-center justify-center overflow-y-auto overflow-x-hidden md:inset-0 md:h-full">
      <div class="relative h-full w-full max-w-md p-4 md:h-auto">
        <!-- Modal content -->
        <div class="relative rounded-lg bg-white p-4 text-center shadow dark:bg-gray-800 sm:p-5">
          <!-- Tombol Close -->
          <button
            type="button"
            class="absolute right-2.5 top-2.5 ml-auto inline-flex items-center rounded-lg bg-transparent p-1.5 text-sm text-gray-400 hover:bg-gray-200 hover:text-gray-900 dark:hover:bg-gray-600 dark:hover:text-white"
            data-modal-toggle="deleteOrderModal"
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
    
          <!-- Icon Danger -->
          <div class="mx-auto mb-4 flex h-12 w-12 items-center justify-center rounded-lg bg-gray-100 p-2 dark:bg-gray-700">
            <svg class="h-8 w-8 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
              <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 7h14m-9 3v8m4-8v8M10 3h4a1 1 0 0 1 1 1v3H9V4a1 1 0 0 1 1-1ZM6 7h12v13a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1V7Z" />
            </svg>
            <span class="sr-only">Danger icon</span>
          </div>
    
          <!-- Pesan Konfirmasi -->
          <p class="mb-3.5 text-gray-900 dark:text-white">
            <span id="usernamePlaceholder" class="font-medium text-primary-700 hover:underline dark:text-primary-500"></span>, are you sure you want to cancel this order?
          </p>
          <p class="mb-4 text-gray-500 dark:text-gray-300">This action cannot be undone.</p>
    
          <!-- Tombol Aksi -->
          <div class="flex items-center justify-center space-x-4">
            <button
              data-modal-toggle="deleteOrderModal"
              type="button"
              class="rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm font-medium text-gray-500 hover:bg-gray-100 hover:text-gray-900 focus:z-10 focus:outline-none focus:ring-4 focus:ring-primary-300 dark:border-gray-500 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600 dark:hover:text-white dark:focus:ring-gray-600"
            >
              No, cancel
            </button>
            <button
              id="confirmDeleteButton"
              type="button"
              class="rounded-lg bg-red-700 px-3 py-2 text-center text-sm font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-4 focus:ring-red-300 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900"
            >
              Yes, delete
            </button>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- Add review modal -->
  <div id="review-modal" tabindex="-1" aria-hidden="true" class="fixed left-0 right-0 top-0 z-50 hidden h-[calc(100%-1rem)] max-h-full w-full items-center justify-center overflow-y-auto overflow-x-hidden md:inset-0 antialiased">
    <div class="relative max-h-full w-full max-w-2xl p-4">
      <!-- Modal content -->
      <div class="relative rounded-lg bg-white shadow dark:bg-gray-800">
        <!-- Modal header -->
        <div class="flex items-center justify-between rounded-t border-b border-gray-200 p-4 dark:border-gray-700 md:p-5">
          <div>
            <h3 class="mb-1 text-lg font-semibold text-gray-900 dark:text-white">Add a review for:</h3>
            <a href="#" class="font-medium text-primary-700 hover:underline dark:text-primary-500">Apple iMac 24" All-In-One Computer, Apple M1, 8GB RAM, 256GB SSD</a>
          </div>
          <button
            type="button"
            class="absolute right-5 top-5 ms-auto inline-flex h-8 w-8 items-center justify-center rounded-lg bg-transparent text-sm text-gray-400 hover:bg-gray-200 hover:text-gray-900 dark:hover:bg-gray-600 dark:hover:text-white"
            data-modal-toggle="review-modal"
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
              Add review
            </button>
            <button
              type="button"
              data-modal-toggle="review-modal"
              class="me-2 rounded-lg border border-gray-200 bg-white px-5 py-2.5 text-sm font-medium text-gray-900 hover:bg-gray-100 hover:text-primary-700 focus:z-10 focus:outline-none focus:ring-4 focus:ring-gray-100 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white dark:focus:ring-gray-700"
            >
              Cancel
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
@include('layouts.footer')