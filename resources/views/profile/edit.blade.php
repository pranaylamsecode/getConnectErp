<x-layouts.app title="Profile">
    <div class="max-w-4xl mx-auto space-y-6">
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-white">Profile Settings</h1>
            <p class="text-sm text-gray-400 mt-1">Manage your account settings and preferences.</p>
        </div>

        <div class="glass rounded-2xl p-6 sm:p-8">
            <div class="max-w-xl">
                @include('profile.partials.update-profile-information-form')
            </div>
        </div>

        <div class="glass rounded-2xl p-6 sm:p-8">
            <div class="max-w-xl">
                @include('profile.partials.update-password-form')
            </div>
        </div>

        <div class="glass rounded-2xl p-6 sm:p-8 border border-red-500/20">
            <div class="max-w-xl">
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>
</x-layouts.app>
