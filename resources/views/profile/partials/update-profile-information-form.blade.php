<section>
    <header>
        <h2 class="text-lg font-medium text-white">
            Profile Information
        </h2>
        <p class="mt-1 text-sm text-gray-400">
            Update your account's profile information and email address.
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div>
            <label for="name" class="block text-sm font-medium text-gray-300 mb-1">Name</label>
            <input id="name" name="name" type="text" class="w-full bg-surface-800/50 border border-gray-700/50 rounded-lg px-4 py-2.5 text-sm text-gray-200 focus:outline-none focus:border-brand-500/50 focus:ring-1 focus:ring-brand-500/30" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name" />
            @error('name')
                <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="email" class="block text-sm font-medium text-gray-300 mb-1">Email</label>
            <input id="email" name="email" type="email" class="w-full bg-surface-800/50 border border-gray-700/50 rounded-lg px-4 py-2.5 text-sm text-gray-200 focus:outline-none focus:border-brand-500/50 focus:ring-1 focus:ring-brand-500/30" value="{{ old('email', $user->email) }}" required autocomplete="username" />
            @error('email')
                <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
            @enderror

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-400">
                        Your email address is unverified.
                        <button form="send-verification" class="underline text-sm text-brand-400 hover:text-brand-300 rounded-md focus:outline-none">
                            Click here to re-send the verification email.
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-emerald-400">
                            A new verification link has been sent to your email address.
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4">
            <button type="submit" class="px-6 py-2 bg-brand-600 hover:bg-brand-700 text-white text-sm font-medium rounded-lg transition">
                Save Changes
            </button>

            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)" class="text-sm text-emerald-400">
                    Saved successfully.
                </p>
            @endif
        </div>
    </form>
</section>
