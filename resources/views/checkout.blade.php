@include('layouts.header') 
<nav class="bg-white  sticky top-0 z-50">
  <div class="container mx-auto px-4 py-5">
      <h1 class="text-2xl font-bold">Jims Honey Pati</h1>
  </div>
</nav>

<section class="bg-parent py-8 antialiased dark:bg-gray-900 md:py-16">
  <form id="checkout-form" action="{{ route('checkout.process') }}" method="POST" class="mx-auto max-w-screen-xl px-4 2xl:px-0">
    @csrf
    <ol class="items-center flex w-full max-w-2xl text-center text-sm font-medium text-gray-500 dark:text-gray-400 sm:text-base">
      <li class="after:border-1 flex items-center text-primary-700 after:mx-6 after:hidden after:h-1 after:w-full after:border-b after:border-gray-200 dark:text-primary-500 dark:after:border-gray-700 sm:after:inline-block sm:after:content-[''] md:w-full xl:after:mx-10">
        <a href="/cart" class="flex items-center after:mx-2 after:text-gray-200 after:content-['/'] dark:after:text-gray-500 sm:after:hidden">
          <svg class="me-2 h-4 w-4 sm:h-5 sm:w-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.5 11.5 11 14l4-4m6 2a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
          </svg>
          Cart
        </a>
      </li>

      <li class="after:border-1 flex items-center text-primary-700 after:mx-6 after:hidden after:h-1 after:w-full after:border-b after:border-gray-200 dark:text-primary-500 dark:after:border-gray-700 sm:after:inline-block sm:after:content-[''] md:w-full xl:after:mx-10">
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
      <div class="mt-6 sm:mt-8 lg:flex lg:items-start lg:gap-12 xl:gap-16">
        <div class="min-w-0 flex-1 space-y-8">
          <div class="space-y-4">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Buat Pesanan</h2>
  
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
              <div>
                <label for="your_name" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white"> Nama </label>
                <input type="text" id="your_name" class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder:text-gray-400 dark:focus:border-primary-500 dark:focus:ring-primary-500"
                  value="@auth {{ Auth::user()->first_name . ' ' . Auth::user()->last_name }} @endauth" readonly required />
              </div>
              <div>
                  <label for="status" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Status</label>
                  <input type="text" id="status" name="status" class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder:text-gray-400 dark:focus:border-primary-500 dark:focus:ring-primary-500"
                      value="@auth {{ Auth::user()->hasRole('Reseller') ? 'Reseller' : 'Customer' }} @endauth" readonly required />
              </div>

              <div>
                <label for="phone-input" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">
                    Nomor HP
                </label>
                <input type="text" id="phone-input" name="phone_number"
                    class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder:text-gray-400 dark:focus:border-primary-500 dark:focus:ring-primary-500"
                    value="{{ Auth::user()->phone_number ? Auth::user()->phone_number : '' }}"
                    placeholder="Masukkan nomor HP Anda" 
                    required />
              </div>

              <div>
                <label for="email" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white"> Email </label>
                <input type="text" id="email" name="email" class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder:text-gray-400 dark:focus:border-primary-500 dark:focus:ring-primary-500"
                    value="@auth {{ Auth::user()->email }} @endauth" readonly required />
              </div>
  
              
            </div>
          </div>
  
          <div class="space-y-4">
            <h3 class="text-xl font-semibold text-gray-900 mb-6">Pilih Opsi Pembayaran</h3>

            <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                <!-- COD Option -->
                <label for="cash" class="cursor-pointer">
                    <div class="rounded-lg border-2 border-gray-200 bg-white p-6 hover:border-primary-500 transition-all">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <input id="cash" type="radio" name="payment-method" value="cash" class="h-5 w-5 border-gray-300 text-primary-600 focus:ring-primary-500" checked />
                            </div>
                            <div class="ml-4">
                                <div class="flex items-center gap-2">
                                    <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m4 4h6a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                    <span class="text-lg font-medium text-gray-900">Cash</span>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                </label>

                <!-- Bank Transfer Option -->
                <label for="bank-transfer" class="cursor-pointer">
                    <div class="rounded-lg border-2 border-gray-200 bg-white p-6 hover:border-primary-500 transition-all">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <input id="bank-transfer" type="radio" name="payment-method" value="bank-transfer" class="h-5 w-5 border-gray-300 text-primary-600 focus:ring-primary-500" />
                            </div>
                            <div class="ml-4">
                                <div class="flex items-center gap-2">
                                    <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                    </svg>
                                    <span class="text-lg font-medium text-gray-900">Bank Transfer</span>
                                </div>
                              
                            </div>
                        </div>
                    </div>
                </label>

                <!-- E-Wallet Option -->
                <label for="e-wallet" class="cursor-pointer">
                    <div class="rounded-lg border-2 border-gray-200 bg-white p-6 hover:border-primary-500 transition-all">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <input id="e-wallet" type="radio" name="payment-method" value="e-wallet" class="h-5 w-5 border-gray-300 text-primary-600 focus:ring-primary-500" />
                            </div>
                            <div class="ml-4">
                                <div class="flex items-center gap-2">
                                    <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m4 4h6a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                    <span class="text-lg font-medium text-gray-900">E-Wallet</span>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                </label>
            </div>
          </div>  
        
  
        <div class="space-y-4">
            <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Metode Pengambilan</h3>
          
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <!-- COD Option -->
                <label for="cod" class="cursor-pointer">
                    <div class="rounded-lg border-2 border-gray-200 bg-white p-6 hover:border-primary-500 transition-all">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <input id="cod" type="radio" name="delivery-method" value="cod" class="h-5 w-5 border-gray-300 text-primary-600 focus:ring-primary-500" checked />
                            </div>
                            <div class="ml-4">
                                <div class="flex items-center gap-2">
                                    <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m4 4h6a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                    <span class="text-lg font-medium text-gray-900">Cash on Delivery (COD)</span>
                                </div>
                                <p class="mt-2 text-sm text-gray-500">Bayar di tempat saat barang sampai</p>
                            </div>
                        </div>
                    </div>
                </label>
            
                <!-- Ambil di Toko Option -->
                <label for="pickup" class="cursor-pointer">
                    <div class="rounded-lg border-2 border-gray-200 bg-white p-6 hover:border-primary-500 transition-all">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <input id="pickup" type="radio" name="delivery-method" value="pickup" class="h-5 w-5 border-gray-300 text-primary-600 focus:ring-primary-500" />
                            </div>
                            <div class="ml-4">
                                <div class="flex items-center gap-2">
                                    <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                    </svg>
                                    <span class="text-lg font-medium text-gray-900">Ambil di Toko</span>
                                </div>
                                <p class="mt-2 text-sm text-gray-500">Ambil barang langsung di toko</p>
                            </div>
                        </div>
                    </div>
                </label>
            </div>

            <!-- Notes Section -->
            <div class="mt-6">
              <label for="notes" class="block text-sm font-medium text-gray-900 mb-2">Catatan (Opsional)</label>
              <textarea
                  id="notes"
                  name="notes"
                  rows="3"
                  class="w-full p-3 border-2 border-gray-200 rounded-lg focus:border-primary-500 focus:ring-primary-500 transition-all"
                  placeholder="Tambahkan catatan khusus untuk pengiriman..."
              ></textarea>
            </div>
          </div>
          
        </div>
  
        <div class="mt-6 w-full space-y-6 sm:mt-8 lg:mt-0 lg:max-w-xs xl:max-w-md">
          <div class="flow-root">
            <div class="-my-3 divide-y divide-gray-200 dark:divide-gray-800">
              <dl class="flex items-center justify-between gap-4 py-3">
                <dt class="text-base font-normal text-gray-500 dark:text-gray-400">Subtotal</dt>
                <dd class="text-base font-medium text-gray-900 dark:text-white">Rp {{ number_format($subtotal, 0, ',', '.') }}</dd>
              </dl>
          
             

              <dl class="flex items-center justify-between gap-4 py-3">
                <dt class="text-base font-normal text-gray-500 dark:text-gray-400">voucher</dt>
                <dd class="text-base font-medium text-green-500">-Rp {{ number_format($voucherAmount, 0, ',', '.') }}</dd>
              </dl>
          
           
              <dl class="flex items-center justify-between gap-4 py-3">
                <dt class="text-base font-bold text-gray-900 dark:text-white">Total</dt>
                <dd class="text-base font-bold text-gray-900 dark:text-white">Rp {{ number_format($total, 0, ',', '.') }}</dd>
              </dl>
          
              
            </div>
          </div>
          
  
          <div class="space-y-3">
            <button id="submit-button" type="submit" class="flex w-full items-center justify-center rounded-lg bg-primary-700 px-5 py-2.5 text-sm font-medium text-white hover:bg-primary-800 focus:outline-none focus:ring-4  focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">Bayar</button>
            
            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            <p class="text-sm font-normal text-gray-500 dark:text-gray-400">Pastikan semua informasi sudah benar sebelum melakukan pembayaran. 
            @auth
              @if (Auth::user()->hasRole('Reseller'))
                  <a href="#" title="" class="font-medium text-primary-700 underline hover:no-underline dark:text-primary-500">Upgrade level reseller untuk diskon lebih besar</a>.
              @elseif (Auth::user()->hasRole('Customer'))
                  <a href="#" title="" class="font-medium text-primary-700 underline hover:no-underline dark:text-primary-500">Join reseller untuk diskon lebih besar</a>.
              @else
                  <p>Ini adalah homepage untuk Moderator.</p>
              @endif
            @endauth</p>

            
            
          </div>
          
          <div class="mt-6 sm:mt-8">
            <div class="relative overflow-x-auto border-b border-gray-200 dark:border-gray-800">
                <table class="w-full text-left font-medium text-gray-900 dark:text-white md:table-fixed">
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                        @foreach ($cartItems as $item)
                            <tr class="border-b dark:border-gray-700">
                              <td class="py-4 pl-4 pr-2 w-1/2">
                                  <div class="flex items-center gap-4">
                                      <a href="#" class="flex items-center aspect-square w-12 h-12 shrink-0">
                                        @if($item->main_image)
                                        <!-- Jika ada gambar dari varian -->
                                            <img class="h-full w-full object-cover dark:hidden" 
                                                src="{{ Storage::url($item->main_image) }}" 
                                                alt="{{ $item->product_name }}" />
                                        @else
                                            <!-- Jika tidak ada gambar dari varian, gunakan gambar dari produk -->
                                            @php
                                                $productImages = json_decode($item->product_image, true);
                                                $mainImage = $productImages[0] ?? null;
                                            @endphp
                                            @if($mainImage)
                                                <img class="h-full w-full object-cover dark:hidden" 
                                                    src="{{ Storage::url($mainImage) }}" 
                                                    alt="{{ $item->product_name }}" />
                                            @else
                                                <!-- Jika tidak ada gambar sama sekali, gunakan gambar default -->
                                                <img class="h-full w-full object-cover dark:hidden" 
                                                    src="{{ asset('path/to/default/image.jpg') }}" 
                                                    alt="{{ $item->product_name }}" />
                                            @endif
                                        @endif
                                      </a>
                                      <div>
                                          <a href="#" class="hover:text-blue-600 hover:underline block font-medium">{{ $item->product->name }}</a>
                                          @if(isset($item->color))
                                            <p class="text-sm text-gray-600 dark:text-gray-400">Color: {{ $item->color }}</p>
                                          @endif
                                      </div>
                                  </div>
                              </td>
                          
                              <td class="py-4 px-2 text-base font-normal text-gray-900 dark:text-white align-middle">x{{ $item->quantity }}</td>
                          
                              <td class="py-4 pl-2 pr-4 text-right text-base font-bold text-gray-900 dark:text-white align-middle">
                                  Rp{{ number_format($item->discounted_price ?? $item->final_price, 0, ',', '.') }}</p>
                              </td>
                          </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
      </div>
  </form>
  
</section>

<script>
  document.querySelector('form').addEventListener('submit', function (event) {
      const phoneInput = document.getElementById('phone-input');
      const paymentMethod = document.querySelector('input[name="payment-method"]:checked');
      const deliveryMethod = document.querySelector('input[name="delivery-method"]:checked');

      if (!phoneInput.value) {
          Swal.fire({
              icon: 'error',
              title: 'Oops...',
              text: 'Nomor HP wajib diisi!',
          });
          event.preventDefault();
          return;
      }

      if (!paymentMethod) {
          Swal.fire({
              icon: 'error',
              title: 'Oops...',
              text: 'Pilih metode pembayaran!',
          });
          event.preventDefault();
          return;
      }

      if (!deliveryMethod) {
          Swal.fire({
              icon: 'error',
              title: 'Oops...',
              text: 'Pilih metode pengiriman!',
          });
          event.preventDefault();
          return;
      }
  });
  </script>

<footer class="bg-white shadow-md">
  <div class="container mx-auto px-4 py-5 text-center">
      <span class="text-sm text-gray-500 sm:text-center dark:text-gray-400"
          >©
          <script>
            document.write(new Date().getFullYear());
          </script>
          <a href="https://flowbite.com/" class="hover:underline">JimsHoney Pati</a>. All Rights Reserved.</span
        >
  </div>
</footer>
<script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>
<script>
    document.getElementById('checkout-form').addEventListener('submit', function (event) {
        event.preventDefault(); // Mencegah form submit default

        // Tampilkan loading indicator (opsional)
        document.getElementById('submit-button').disabled = true;
        document.getElementById('submit-button').innerText = 'Processing...';

        // Kirim data form ke backend menggunakan AJAX
        fetch("{{ route('checkout.process') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                // Data form yang diperlukan
                'payment-method': document.querySelector('[name="payment-method"]').value,
                'delivery-method': document.querySelector('[name="delivery-method"]').value,
                'phone_number': document.querySelector('[name="phone_number"]').value,
                'notes': document.querySelector('[name="notes"]').value,
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.snap_token) {
                // Tampilkan pop-up Midtrans
                snap.pay(data.snap_token, {
                    onSuccess: function(result) {
                        // Redirect ke halaman order detail setelah pembayaran berhasil
                        window.location.href = "{{ route('order.order-detail', ['order_id' => ':order_id']) }}".replace(':order_id', data.order_id);
                    },
                    onPending: function(result) {
                        alert("Menunggu pembayaran Anda!");
                        window.location.href = "{{ route('order.order-detail', ['order_id' => ':order_id']) }}".replace(':order_id', data.order_id);
                    },
                    onError: function(result) {
                        alert("Pembayaran gagal!");
                        window.location.href = "{{ route('order.order-detail', ['order_id' => ':order_id']) }}".replace(':order_id', data.order_id);
                    },
                    onClose: function() {
                        alert("Anda menutup pop-up tanpa menyelesaikan pembayaran.");
                        window.location.href = "{{ route('order.order-detail', ['order_id' => ':order_id']) }}".replace(':order_id', data.order_id);
                    }
                });
            } else {
                alert("Terjadi kesalahan saat memproses pembayaran.");
                document.getElementById('submit-button').disabled = false;
                document.getElementById('submit-button').innerText = 'Checkout';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert("Terjadi kesalahan saat memproses pembayaran.");
            document.getElementById('submit-button').disabled = false;
            document.getElementById('submit-button').innerText = 'Checkout';
        });
    });
</script>