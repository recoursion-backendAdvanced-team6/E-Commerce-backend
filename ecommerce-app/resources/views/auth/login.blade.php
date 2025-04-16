<x-front-layout>
    <x-guest-layout>
        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Email Address -->
            <div>
                <label for="email" class="block font-medium text-sm text-gray-700">{{ __('Email') }}</label>
                <input id="email" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" type="email"
                    name="email" :value="old('email')" required autofocus autocomplete="username" />
                @error('email')
                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                @enderror
            </div>

            <!-- Password -->
            <div class="mt-4">
                <label for="password" class="block font-medium text-sm text-gray-700">{{ __('Password') }}</label>
                <input id="password" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" type="password"
                    name="password" required autocomplete="current-password" />
                @error('password')
                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                @enderror
            </div>

            <!-- Remember Me -->
            <div class="block mt-4">
                <label for="remember_me" class="inline-flex items-center">
                    <input id="remember_me" type="checkbox"
                        class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                    <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                </label>
            </div>

            <div class="flex items-center justify-end mt-4">
                <a href="{{ route('register') }}" class="text-sm text-blue-500 hover:underline">
                    {{ __('Register') }}
                </a>

                @if (Route::has('password.request'))
                    <a class="text-sm text-gray-600 hover:underline ms-4" href="{{ route('password.request') }}">
                        {{ __('Forgot your password?') }}
                    </a>
                @endif
                <x-primary-button class="ms-4">
                    {{ __('Login') }}
                </x-primary-button>

            </div>
        </form>
    </x-guest-layout>
</x-front-layout>
