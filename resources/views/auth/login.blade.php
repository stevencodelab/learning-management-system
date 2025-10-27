<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4 sm:mb-6" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-4 sm:space-y-6">
        @csrf

        <!-- Email Address -->
        <div class="{{ $errors->get('email') ? 'animate-shake' : '' }}">
            <x-input-label for="email" :value="__('Email Address')" class="text-xs sm:text-sm font-semibold {{ $errors->get('email') ? 'text-red-700' : 'text-blue-900' }} mb-2 block" />
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-2.5 sm:pl-3 flex items-center pointer-events-none">
                    <svg class="h-4 w-4 sm:h-5 sm:w-5 {{ $errors->get('email') ? 'text-red-400' : 'text-blue-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                    </svg>
                </div>
                        <x-text-input 
                            id="email" 
                            class="block w-full pl-8 sm:pl-10 pr-3 py-2.5 sm:py-3 border {{ $errors->get('email') ? 'border-red-300 bg-red-50' : 'border-blue-200 bg-blue-50' }} rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 focus:bg-white text-sm sm:text-base hover:border-blue-300 focus:shadow-lg" 
                            type="email" 
                            name="email" 
                            :value="old('email')" 
                            required 
                            autofocus 
                            autocomplete="username"
                            placeholder="Enter your email address" />
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-sm text-red-600" />
        </div>

        <!-- Password -->
        <div class="{{ $errors->get('password') ? 'animate-shake' : '' }}">
            <x-input-label for="password" :value="__('Password')" class="text-xs sm:text-sm font-semibold {{ $errors->get('password') ? 'text-red-700' : 'text-blue-900' }} mb-2 block" />
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-2.5 sm:pl-3 flex items-center pointer-events-none">
                    <svg class="h-4 w-4 sm:h-5 sm:w-5 {{ $errors->get('password') ? 'text-red-400' : 'text-blue-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                </div>
                        <x-text-input 
                            id="password" 
                            class="block w-full pl-8 sm:pl-10 pr-3 py-2.5 sm:py-3 border {{ $errors->get('password') ? 'border-red-300 bg-red-50' : 'border-blue-200 bg-blue-50' }} rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 focus:bg-white text-sm sm:text-base hover:border-blue-300 focus:shadow-lg"
                            type="password"
                            name="password"
                            required 
                            autocomplete="current-password"
                            placeholder="Enter your password" />
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-sm text-red-600" />
        </div>

        <!-- Remember Me & Forgot Password -->
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <input 
                    id="remember_me" 
                    type="checkbox" 
                    class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-blue-300 rounded transition-colors duration-200" 
                    name="remember">
                <label for="remember_me" class="ml-2 block text-xs sm:text-sm text-blue-700 font-medium">
                    {{ __('Remember me') }}
                </label>
            </div>

            @if (Route::has('password.request'))
                <a class="text-xs sm:text-sm font-medium text-blue-600 hover:text-blue-500 transition-colors duration-200" href="{{ route('password.request') }}">
                    {{ __('Forgot password?') }}
                </a>
            @endif
        </div>

        <!-- Login Button -->
        <div class="{{ $errors->any() ? 'animate-shake' : '' }}">
            <x-primary-button class="w-full flex justify-center items-center py-2.5 sm:py-3 px-4 border border-transparent rounded-xl shadow-sm text-xs sm:text-sm font-semibold text-white blue-gradient hover:from-blue-700 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 transform hover:scale-[1.02]">
                <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                </svg>
                {{ __('Sign In') }}
            </x-primary-button>
        </div>

                <!-- Demo Credentials -->
                <div class="mt-4 sm:mt-6 p-3 sm:p-4 bg-blue-50 rounded-xl border border-blue-200">
                    <h3 class="text-xs sm:text-sm font-semibold text-blue-900 mb-2 flex items-center">
                        <svg class="w-3 h-3 sm:w-4 sm:h-4 mr-1 sm:mr-2 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                        </svg>
                        Demo Credentials:
                    </h3>
                    <div class="space-y-1 text-xs text-blue-700">
                        <div><strong>Admin:</strong> admin@smartedu.com</div>
                        <div><strong>Instructor:</strong> instructor@smartedu.com</div>
                        <div><strong>Student:</strong> student@smartedu.com</div>
                        <div class="text-blue-600 mt-1 font-medium">Password: password</div>
                    </div>
                </div>
    </form>
</x-guest-layout>
