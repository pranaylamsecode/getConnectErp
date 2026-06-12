<div>
    <h1 class="text-xl font-bold text-white mb-6">Team Management</h1>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Team Info -->
        <div class="glass rounded-xl p-6">
            <h3 class="text-sm font-semibold text-white mb-4">Your Team</h3>
            @if($team)
            <div class="space-y-3">
                <div>
                    <span class="text-xs text-gray-400">Team Name</span>
                    <p class="text-sm text-white font-medium">{{ $team->name }}</p>
                </div>
                <div>
                    <span class="text-xs text-gray-400">Owner</span>
                    <p class="text-sm text-gray-300">{{ $team->owner->name }}</p>
                </div>
                <div>
                    <span class="text-xs text-gray-400">Members</span>
                    <p class="text-sm text-gray-300">{{ $team->members->count() }}</p>
                </div>
            </div>
            @else
            <p class="text-sm text-gray-500">No team configured.</p>
            @endif
        </div>

        <!-- Members List -->
        <div class="lg:col-span-2 glass rounded-xl p-6">
            <h3 class="text-sm font-semibold text-white mb-4">Team Members</h3>

            <!-- Add Member -->
            <div class="flex gap-2 mb-4">
                <input wire:model="newMemberEmail" type="email" placeholder="Email address" class="flex-1 bg-surface-800/50 border border-gray-700/50 rounded-lg px-4 py-2 text-sm text-gray-200 focus:outline-none focus:border-brand-500/50">
                <select wire:model="newMemberRole" class="bg-surface-800/50 border border-gray-700/50 rounded-lg px-3 py-2 text-sm text-gray-200 focus:outline-none focus:border-brand-500/50">
                    <option value="sales">Sales</option>
                    <option value="manager">Manager</option>
                </select>
                <button wire:click="addMember" class="px-4 py-2 bg-brand-600 hover:bg-brand-700 text-white text-sm rounded-lg transition">Add</button>
            </div>

            <!-- Members Table -->
            <div class="space-y-2">
                @foreach($members as $member)
                <div class="flex items-center justify-between p-3 rounded-lg bg-surface-800/30 border border-gray-700/30">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-brand-500 to-purple-600 flex items-center justify-center text-sm font-bold text-white">
                            {{ substr($member->name, 0, 1) }}
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-200">{{ $member->name }}</p>
                            <p class="text-xs text-gray-500">{{ $member->email }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="text-xs px-2 py-0.5 rounded-full {{ $member->role === 'admin' ? 'bg-amber-500/20 text-amber-300' : ($member->role === 'manager' ? 'bg-brand-500/20 text-brand-300' : 'bg-gray-500/20 text-gray-300') }} capitalize">{{ $member->role }}</span>
                        <span class="text-xs {{ $member->is_active ? 'text-emerald-400' : 'text-red-400' }}">{{ $member->is_active ? 'Active' : 'Inactive' }}</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
