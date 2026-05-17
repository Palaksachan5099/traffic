<x-guest-layout :background-image="'images/image2.avif'">
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" class="text-white font-bold" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" class="text-white font-bold" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" class="text-white font-bold" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" class="text-white font-bold" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <!-- Role Selection -->
        <div class="mt-4">
            <x-input-label :value="__('Select Account Type')" class="text-white font-bold" />
            <div class="mt-2 space-y-2">
                <div class="flex items-center">
                    <input id="role_user" type="radio" name="role" value="user" {{ old('role', 'user') === 'user' ? 'checked' : '' }} class="w-4 h-4 text-indigo-600 bg-gray-100 border-gray-300 focus:ring-2 focus:ring-indigo-500" required>
                    <label for="role_user" class="ms-2 text-sm font-bold text-white">{{ __('Regular User - Report Problems') }}</label>
                </div>
                <div class="flex items-center">
                    <input id="role_admin" type="radio" name="role" value="admin" {{ old('role') === 'admin' ? 'checked' : '' }} class="w-4 h-4 text-indigo-600 bg-gray-100 border-gray-300 focus:ring-2 focus:ring-indigo-500" required>
                    <label for="role_admin" class="ms-2 text-sm font-bold text-white">{{ __('Administrator - Manage Reports') }}</label>
                </div>
                <div class="flex items-center">
                    <input id="role_officer" type="radio" name="role" value="officer" {{ old('role') === 'officer' ? 'checked' : '' }} class="w-4 h-4 text-indigo-600 bg-gray-100 border-gray-300 focus:ring-2 focus:ring-indigo-500" required>
                    <label for="role_officer" class="ms-2 text-sm font-bold text-white">{{ __('Officer - Handle Assigned Cases') }}</label>
                </div>
            </div>
            <x-input-error :messages="$errors->get('role')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-white font-bold hover:text-gray-200 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
