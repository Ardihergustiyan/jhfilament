 @include('layouts.header')
<div class="flex justify-center">
    <img src="{{ asset('img/component/LogoJH.png') }}" alt="Jims Honey Logo" class="w-40 h-auto object-contain" />
</div>
<div class="flex items-center justify-center w-full max-w-screen-xl mx-auto mb-4">
    <div class=" bg-white shadow-xl rounded-2xl p-10 md:w-2/3 lg:w-1/3">
        <h2 class="text-2xl font-bold mb-6 text-pink-300 text-center">Register</h2>
        
        <!-- Tampilkan error -->
        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        <!-- Form Register -->
        <form method="POST" action="{{ route('register') }}" class="space-y-4">
            @csrf
            <div>
                <label for="first_name" class="block text-gray-600 mb-1">first name</label>
                <input 
                    type="text" 
                    name="first_name" 
                    id="first_name" 
                    value="{{ old('first_name') }}" 
                    class="form-input w-full border-gray-300 p-2 rounded-lg focus:border-pink-400 focus:ring-pink-400"
                    required
                >
            </div>
            <div>
                <label for="last_name" class="block text-gray-600 mb-1">last name</label>
                <input 
                    type="text" 
                    name="last_name" 
                    id="last_name" 
                    value="{{ old('last_name') }}" 
                    class="form-input w-full border-gray-300 p-2 rounded-lg focus:border-pink-400 focus:ring-pink-400"
                    required
                >
            </div>
            <div>
                <label for="email" class="block text-gray-600 mb-1">Email</label>
                <input 
                    type="email" 
                    name="email" 
                    id="email" 
                    value="{{ old('email') }}" 
                    class="form-input w-full border-gray-300 p-2 rounded-lg focus:border-pink-400 focus:ring-pink-400"
                    required
                >
            </div>
            <div>
                <label for="password" class="block text-gray-600 mb-1">Password</label>
                <input 
                    type="password" 
                    name="password" 
                    id="password" 
                    class="form-input w-full border-gray-300 p-2 rounded-lg focus:border-pink-400 focus:ring-pink-400"
                    required
                >
            </div>
            <div>
                <label for="password_confirmation" class="block text-gray-600 mb-1">Confirm Password</label>
                <input 
                    type="password" 
                    name="password_confirmation" 
                    id="password_confirmation" 
                    class="form-input w-full border-gray-300 p-2 rounded-lg focus:border-pink-400 focus:ring-pink-400"
                    required
                >
            </div>
            <button type="submit" class="w-full py-2 px-4 bg-pink-500 text-white font-bold rounded-lg hover:bg-pink-600 transition">
                Register
            </button>
        </form>

        <p class="text-center text-gray-600 text-sm mt-4">
            Already have an account? 
            <a href="{{ route('login') }}" class="text-pink-500 hover:underline">Login</a>
        </p>
    </div>
</div>