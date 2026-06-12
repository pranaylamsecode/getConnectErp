<section>
    <header>
        <h2 class="text-lg font-medium text-white">
            Update Password
        </h2>
        <p class="mt-1 text-sm text-gray-400">
            Ensure your account is using a long, random password to stay secure.
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('put')

        <div>
            <label for="update_password_current_password" class="block text-sm font-medium text-gray-300 mb-1">Current Password</label>
            <input id="update_password_current_password" name="current_password" type="password" class="w-full bg-surface-800/50 border border-gray-700/50 rounded-lg px-4 py-2.5 text-sm text-gray-200 focus:outline-none focus:border-brand-500/50 focus:ring-1 focus:ring-brand-500/30" autocomplete="current-password" />
            @error('current_password', 'updatePassword')
                <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="update_password_password" class="block text-sm font-medium text-gray-300 mb-1">New Password</label>
            <input id="update_password_password" name="password" type="password" class="w-full bg-surface-800/50 border border-gray-700/50 rounded-lg px-4 py-2.5 text-sm text-gray-200 focus:outline-none focus:border-brand-500/50 focus:ring-1 focus:ring-brand-500/30" autocomplete="new-password" />
            @error('password', 'updatePassword')
                <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="update_password_password_confirmation" class="block text-sm font-medium text-gray-300 mb-1">Confirm Password</label>
            <input id="update_password_password_confirmation" name="password_confirmation" type="password" class="w-full bg-surface-800/50 border border-gray-700/50 rounded-lg px-4 py-2.5 text-sm text-gray-200 focus:outline-none focus:border-brand-500/50 focus:ring-1 focus:ring-brand-500/30" autocomplete="new-password" />
            @error('password_confirmation', 'updatePassword')
                <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-center gap-4">
            <button type="submit" class="px-6 py-2 bg-brand-600 hover:bg-brand-700 text-white text-sm font-medium rounded-lg transition">
                Update Password
            </button>

            @if (session('status') === 'password-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)" class="text-sm text-emerald-400">
                    Saved successfully.
                </p>
            @endif
        </div>
    </form>
</section>
