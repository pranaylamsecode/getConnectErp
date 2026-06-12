<section class="space-y-6">
    <header>
        <h2 class="text-lg font-medium text-red-400">
            Delete Account
        </h2>
        <p class="mt-1 text-sm text-gray-400">
            Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.
        </p>
    </header>

    <button x-data="" x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')" class="px-6 py-2 bg-red-600/20 hover:bg-red-600/30 border border-red-500/50 text-red-400 text-sm font-medium rounded-lg transition">
        Delete Account
    </button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6 bg-surface-900 border border-gray-700/50 shadow-2xl rounded-2xl text-gray-200">
            @csrf
            @method('delete')

            <h2 class="text-lg font-medium text-white">
                Are you sure you want to delete your account?
            </h2>

            <p class="mt-1 text-sm text-gray-400">
                Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.
            </p>

            <div class="mt-6">
                <label for="password" class="sr-only">Password</label>
                <input id="password" name="password" type="password" class="w-full bg-surface-800/50 border border-gray-700/50 rounded-lg px-4 py-2.5 text-sm text-gray-200 focus:outline-none focus:border-red-500/50 focus:ring-1 focus:ring-red-500/30" placeholder="Password" />
                @error('password', 'userDeletion')
                    <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <button type="button" x-on:click="$dispatch('close')" class="px-4 py-2 bg-surface-800 hover:bg-surface-700 text-gray-300 text-sm font-medium rounded-lg transition">
                    Cancel
                </button>

                <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition">
                    Delete Account
                </button>
            </div>
        </form>
    </x-modal>
</section>
