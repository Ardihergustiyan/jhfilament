@include('layouts.header')
@include('components.navbar')
<!-- about -->
<div class="flex flex-col items-center text-center relative z-0 ">
    <!-- Background Image -->
    <div class="absolute inset-0 flex items-center justify-center">
      <img src="{{ asset('img/earth.png') }}" alt="Map Background" class="w-full h-auto max-w-screen-2xl object-cover" />
    </div>

    <!-- Main Content Container -->
    <div class="relative z-10 max-w-6xl mx-auto flex flex-col lg:flex-row items-center lg:justify-between">
      <!-- Left Side: Text Content -->
      <div class="w-full lg:w-2/3 text-center lg:text-left p-6">
        <!-- Title Section -->
        <h1 class="text-5xl font-extrabold text-[#f98b88] mb-4 flex justify-center">Jims Honey Pati</h1>

        <!-- Description Section -->
        <p class="text-lg text-gray-700 flex text-center">
          Lorem ipsum dolor sit amet consectetur adipisicing elit. Voluptatem fuga consequatur architecto ullam earum? Sed consequuntur nobis, similique dignissimos amet dicta praesentium in quibusdam delectus voluptatem laudantium.
          Voluptates ipsa est, a fuga dolorem qui? Veniam laudantium neque vel impedit non ad quos, eius dignissimos dolorem qui deserunt quas ullam quae.
        </p>
      </div>

      <!-- Right Side: Logo -->
      <div class="w-full lg:w-1/3 flex justify-center">
        <img src="{{ asset('img/component/LogoJH.png') }}" alt="Jims Honey Logo" class="w-80 h-auto lg:w-auto lg:h-auto opacity-80" />
      </div>
    </div>

    <!-- Stats Section -->
    <div class="relative z-10 w-full flex flex-col md:flex-row justify-center items-center space-y-6 md:space-y-0 md:space-x-8 mt-">
      <div class="text-center">
        <h2 class="text-5xl font-bold text-[#f98b88]">8 Year</h2>
        <p class="text-gray-500 text-base">in business</p>
      </div>
      <div class="hidden md:block border-l-2 border-gray-300 h-12"></div>
      <div class="text-center">
        <h2 class="text-5xl font-bold text-[#f98b88]">24,000+</h2>
        <p class="text-gray-500 text-base">customers</p>
      </div>
      <div class="hidden md:block border-l-2 border-gray-300 h-12"></div>
      <div class="text-center">
        <h2 class="text-5xl font-bold text-[#f98b88]">8K+</h2>
        <p class="text-gray-500 text-base">social media followers</p>
      </div>
    </div>
  </div>
  <!-- end about -->

  <!-- get in touch -->
  <div class="my-10 flex items-center justify-center">
    <div class="flex items-center justify-center p-4 md:p-8 bg-[#f98b88] w-full max-w-screen-2xl rounded-lg shadow-lg">
      <div class="bg-white rounded-lg shadow-xl p-6 md:p-8 m-4 w-full">
        <!-- Judul Section -->
        <h2 class="text-center text-2xl md:text-3xl font-semibold text-[#f98b88] mb-6 md:mb-8">Get In Touch</h2>
  
        <!-- Grid untuk Form dan Informasi Kontak -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 md:gap-8">
          <!-- Left Section: Form -->
          <div>
            <h3 class="text-lg md:text-xl font-medium mb-4 text-gray-800">Leave us a message</h3>
            <form action="#" method="POST" class="space-y-4">
              <!-- Input Nama -->
              <div>
                <label for="name" class="block font-medium text-gray-700">Name</label>
                <input
                  type="text"
                  id="name"
                  name="name"
                  placeholder="Your name"
                  class="w-full mt-1 px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#f98b88] focus:border-transparent"
                />
              </div>
  
              <!-- Input Email -->
              <div>
                <label for="email" class="block font-medium text-gray-700">Email Address</label>
                <input
                  type="email"
                  id="email"
                  name="email"
                  placeholder="Email Address"
                  class="w-full mt-1 px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#f98b88] focus:border-transparent"
                />
              </div>
  
              <!-- Input Pesan -->
              <div>
                <label for="message" class="block font-medium text-gray-700">Your Message</label>
                <textarea
                  id="message"
                  name="message"
                  rows="4"
                  placeholder="Your Message"
                  class="w-full mt-1 px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#f98b88] focus:border-transparent"
                ></textarea>
              </div>
  
              <!-- Tombol Submit -->
              <button
                type="submit"
                class="w-full md:w-auto px-6 py-2 bg-[#f98b88] text-white font-semibold rounded-md shadow-md hover:bg-[#f77d7a] focus:outline-none focus:ring-2 focus:ring-[#f98b88] focus:ring-opacity-50 transition-colors duration-200"
              >
                Send
              </button>
            </form>
          </div>
  
          <!-- Right Section: Informasi Kontak -->
          <div>
            <h3 class="text-lg md:text-xl font-medium mb-4 text-gray-800">Contact Information</h3>
  
            <!-- Alamat -->
            <div class="flex items-center mb-3">
              <span class="mr-2 text-xl text-[#f98b88]">📍</span>
              <p class="text-gray-700">Jl. Dr. Susanto No.92, Pati Lor, Kabupaten Pati, Jawa Tengah 59111</p>
            </div>
  
            <!-- Nomor Telepon -->
            <div class="flex items-center mb-3">
              <span class="mr-2 text-xl text-[#f98b88]">📞</span>
              <p class="text-gray-700">+62 899-0636-000</p>
            </div>
  
            <!-- Jam Buka -->
            <div class="flex items-center mb-6">
              <span class="mr-2 text-xl text-[#f98b88]">📧</span>
              <p class="text-gray-700">Buka : Jam 9 - 10 Malam</p>
            </div>
  
            <!-- Google Maps -->
            <div class="mapouter">
              <div class="gmap_canvas">
                <iframe
                  src="https://maps.google.com/maps?q=Jl.%20Dr.%20Susanto%20No.92,%20Pati%20Lor,%20Kabupaten%20Pati,%20Jawa%20Tengah%2059111&amp;t=&amp;z=13&amp;ie=UTF8&amp;iwloc=&amp;output=embed"
                  frameborder="0"
                  scrolling="no"
                  style="width: 100%; height: 200px; border: 0"
                  allowfullscreen=""
                  loading="lazy"
                  class="rounded-md shadow-sm"
                ></iframe>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- end get in touch -->
@include('components.features')
@include('layouts.footer')