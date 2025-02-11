@include('layouts.header')
@include('components.navbar')
<!-- about -->
<div class="bg-white flex flex-col items-center text-center relative z-0 ">
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
    <div class="flex items-center justify-center p-2 md:p-8 bg-[#f98b88] w-full max-w-screen-2xl rounded-lg shadow-lg">
      <div class="bg-white rounded-lg shadow-xl p-8 m-4 w-full">
        <h2 class="text-center text-2xl font-semibold text-[#f98b88] mb-8">Get In Touch</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <!-- Left Section: Form -->
          <div>
            <h3 class="text-lg font-medium mb-4">Leave us a message</h3>
            <form action="#" method="POST" class="space-y-4">
              <div>
                <label for="name" class="block font-medium">Name</label>
                <input type="text" id="name" name="name" placeholder="Your name" class="w-full mt-1 px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400" />
              </div>
              <div>
                <label for="email" class="block font-medium">Email Address</label>
                <input type="email" id="email" name="email" placeholder="Email Address" class="w-full mt-1 px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400" />
              </div>
              <div>
                <label for="message" class="block font-medium">Your Message</label>
                <textarea id="message" name="message" rows="4" placeholder="Your Message" class="w-full mt-1 px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400"></textarea>
              </div>
              <button
                type="submit"
                class="px-6 py-2 bg-[#f98b88] text-white font-semibold rounded-md shadow-md hover:bg-[#f77d7a] focus:ring-4 focus:outline-none focus:ring-pink-300 "
              >
                Send
              </button>
            </form>
          </div>

          <!-- Right Section: Contact Information -->
          <div>
            <h3 class="text-lg font-medium mb-4">Contact Information :</h3>
            <p class="flex items-center mb-2">
              <span class="mr-2">📍</span>
              Jl. Dr. Susanto No.92, Pati Lor, Kabupaten Pati, Jawa Tengah 59111
            </p>
            <p class="flex items-center mb-2"><span class="mr-2">📞</span> +62 899-0636-000</p>
            <p class="flex items-center mb-4"><span class="mr-2">📧</span> Buka : Jam 9 - 10 Malam</p>
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