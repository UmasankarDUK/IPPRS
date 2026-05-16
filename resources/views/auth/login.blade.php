<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="mb-8">
        <h2 class="text-3xl font-bold text-gray-900 mb-2">Welcome Back</h2>
        <p class="text-gray-600">Please sign in to access the IPPRS dashboard.</p>
    </div>

    <form method="POST" action="{{ route('login') }}" class="space-y-6">
        @csrf

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
            <div class="mt-1">
                <input id="email" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-kerala-primary focus:ring-kerala-primary sm:text-sm px-4 py-3" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="admin@keralahealth.gov.in" />
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-600" />
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
            <div class="mt-1">
                <input id="password" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-kerala-primary focus:ring-kerala-primary sm:text-sm px-4 py-3" type="password" name="password" required autocomplete="current-password" placeholder="••••••••" />
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-600" />
        </div>

        <!-- Remember Me & Forgot Password -->
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <input id="remember_me" name="remember" type="checkbox" class="h-4 w-4 rounded border-gray-300 text-kerala-600 focus:ring-kerala-primary">
                <label for="remember_me" class="ml-2 block text-sm text-gray-900">
                    Remember me
                </label>
            </div>

            @if (Route::has('password.request'))
                <div class="text-sm">
                    <a href="{{ route('password.request') }}" class="font-medium text-kerala-600 hover:text-kerala-500">
                        Forgot your password?
                    </a>
                </div>
            @endif
        </div>

        <div>
            <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-kerala-primary hover:bg-kerala-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-kerala-primary transition duration-150 ease-in-out">
                Sign In
            </button>
        </div>
    </form>
    
    <div class="mt-8 text-center text-sm text-gray-500">
        <p>&copy; {{ date('Y') }} Department of Health and Family Welfare, Government of Kerala.</p>
        <p>All rights reserved.</p>
    </div>
</x-guest-layout>
