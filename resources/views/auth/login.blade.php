@include('layouts.header')
    <!-- Logo -->
    <div class="flex justify-center">
      <img src="{{ asset('img/component/LogoJH.png') }}" alt="Jims Honey Logo" class="w-40 h-auto object-contain" />
    </div>
    <section class="flex items-center justify-center w-full max-w-screen-xl mx-auto mb-4">
      <div class="bg-white shadow-xl rounded-2xl p-10 pt-0 md:w-2/3 lg:w-1/3">
        <!-- Page Title -->
        <h2 class="text-2xl font-bold text-center text-pink-300 mb-6 mt-6">Welcome</h2>
        {{-- Tampilkan Pesan Error --}}
        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                {{ session('error') }}
            </div>
        @endif

        {{-- Tampilkan Validasi Error --}}
        @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                <ul class="list-disc pl-5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <!-- Login Form -->
        <form id="loginForm" class="space-y-6" method="POST" action="{{ route('login') }}">
            @csrf
            <!-- Email -->
            <div>
                <label for="email" class="block text-gray-600 mb-1">Email</label>
                <input
                    id="email"
                    name="email"
                    type="email"
                    value="{{ old('email') }}"
                    onblur="validateInput('email', 'emailWarning')"
                    class="form-input w-full border-gray-300 p-2 rounded-lg focus:border-pink-400 focus:ring-pink-400"
                    required
                />
                <p id="emailWarning" class="text-red-600 text-xs ml-2 mt-1 hidden">Form belum diisi</p>
                <p id="emailError" class="text-red-500 text-sm mt-1 hidden"></p>
                @error('email')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        
            <!-- Password -->
            <div>
                <label for="password" class="block text-gray-600 mb-1">Password</label>
                <div class="relative">
                    <input
                        id="password"
                        name="password"
                        type="password"
                        onblur="validateInput('password', 'passwordWarning')"
                        class="form-input w-full border-gray-300 p-2 rounded-lg focus:border-pink-400 focus:ring-pink-400"
                        required
                    />
                    <button
                        type="button"
                        class="absolute inset-y-0 right-3 flex items-center"
                        onclick="togglePasswordVisibility()"
                    >
                    </button>
                </div>
                <p id="passwordError" class="text-red-500 text-sm mt-1 hidden"></p>
                <p id="passwordWarning" class="text-red-600 text-xs ml-2 mt-1 hidden">Form belum diisi</p>
                @error('password')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        
            <!-- Submit Button -->
            <button
                type="submit"
                class="w-full py-3 px-4 mt-6 rounded-lg text-sm font-medium text-white bg-gradient-to-r from-pink-400 to-pink-500 inline-flex items-center justify-center hover:from-pink-500 hover:to-pink-600 hover:shadow-lg focus:outline-none focus:ring-4 focus:ring-pink-200 transition duration-150 ease-in-out"
            >
                Login
            </button>
        </form>
        
        

        <!-- Google Login Button -->
        <a
            href="/auth/redirect"
            class="w-full border text-gray-700 rounded-md py-2 mt-6 flex items-center justify-center hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-pink-500 active:bg-gray-200 active:border-gray-400 transition duration-300"
            >
            <img src="https://www.svgrepo.com/show/355037/google.svg" alt="Google" class="inline w-4 h-4 mr-3" />
            Login with Google
        </a>

        <!-- Forgot Password & Sign Up Links -->
        <div class="text-center mt-6 space-y-3">
          <p class="text-gray-500">
            Belum punya akun?
            <a href="/register" class="text-pink-500 font-semibold">Register</a>
          </p>
        </div>
      </div>
    </section>
    <footer class="w-full py-4 text-center text-gray-500 text-sm mb-4 mt-10">
        <p>
          &copy; 2016-{{ date('Y') }},
          <a href="#" class="text-pink-500 font-semibold hover:underline">Jims Honey Pati</a>
        </p>
    </footer>

    <script>
        function validateForm() {
            let isValid = true;
            const email = document.getElementById('email');
            const password = document.getElementById('password');
            const emailWarning = document.getElementById('emailWarning');
            const passwordWarning = document.getElementById('passwordWarning');

            if (!email.value) {
                emailWarning.classList.remove('hidden');
                isValid = false;
            }

            if (!password.value) {
                passwordWarning.classList.remove('hidden');
                isValid = false;
            }

            return isValid;
        }
    </script>
    </body>
</html>