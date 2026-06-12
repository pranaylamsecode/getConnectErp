<div>
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-xl font-bold text-white">Email Campaigns</h1>
            <p class="text-sm text-gray-400">Create and manage email campaigns for your leads</p>
        </div>
        <a href="{{ route('campaigns.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-brand-600 hover:bg-brand-700 text-white text-sm font-medium rounded-lg transition shadow-lg shadow-brand-600/20">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            New Campaign
        </a>
    </div>

    <!-- Filters -->
    <div class="flex gap-3 mb-6">
        <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search campaigns..." class="w-64 bg-surface-800/50 border border-gray-700/50 rounded-lg px-4 py-2 text-sm text-gray-300 placeholder-gray-500 focus:outline-none focus:border-brand-500/50">
        <select wire:model.live="statusFilter" class="bg-surface-800/50 border border-gray-700/50 rounded-lg px-3 py-2 text-sm text-gray-300 focus:outline-none focus:border-brand-500/50">
            <option value="">All Status</option>
            <option value="draft">Draft</option>
            <option value="scheduled">Scheduled</option>
            <option value="sending">Sending</option>
            <option value="sent">Sent</option>
            <option value="paused">Paused</option>
        </select>
    </div>

    <!-- Campaigns Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @forelse($campaigns as $campaign)
        <div class="glass rounded-xl p-5 stat-card">
            <div class="flex items-start justify-between mb-3">
                <div>
                    <h3 class="text-sm font-semibold text-white">{{ $campaign->name }}</h3>
                    <p class="text-xs text-gray-500 mt-0.5">{{ $campaign->subject }}</p>
                </div>
                @php
                    $badgeColor = match($campaign->status) {
                        'draft' => 'bg-gray-500/20 text-gray-300',
                        'scheduled' => 'bg-amber-500/20 text-amber-300',
                        'sending' => 'bg-blue-500/20 text-blue-300',
                        'sent' => 'bg-emerald-500/20 text-emerald-300',
                        'paused' => 'bg-red-500/20 text-red-300',
                        default => 'bg-gray-500/20 text-gray-300',
                    };
                @endphp
                <span class="text-[10px] px-2 py-0.5 rounded-full font-medium {{ $badgeColor }}">{{ ucfirst($campaign->status) }}</span>
            </div>

            <!-- Stats -->
            <div class="grid grid-cols-3 gap-2 mb-3">
                <div class="text-center p-2 rounded-lg bg-surface-800/30">
                    <p class="text-sm font-bold text-white">{{ number_format($campaign->sent_count) }}</p>
                    <p class="text-[10px] text-gray-500">Sent</p>
                </div>
                <div class="text-center p-2 rounded-lg bg-surface-800/30">
                    <p class="text-sm font-bold text-emerald-400">{{ $campaign->open_rate }}%</p>
                    <p class="text-[10px] text-gray-500">Opened</p>
                </div>
                <div class="text-center p-2 rounded-lg bg-surface-800/30">
                    <p class="text-sm font-bold text-brand-400">{{ $campaign->click_rate }}%</p>
                    <p class="text-[10px] text-gray-500">Clicked</p>
                </div>
            </div>

            @if($campaign->status === 'sending')
            <div class="w-full bg-surface-800 rounded-full h-1.5 mb-3">
                <div class="bg-brand-500 h-1.5 rounded-full transition-all" style="width: {{ $campaign->progress_percent }}%"></div>
            </div>
            @endif

            <div class="flex items-center justify-between text-xs text-gray-500">
                <span>{{ $campaign->created_at->format('M d, Y') }}</span>
                <button wire:click="deleteCampaign({{ $campaign->id }})" wire:confirm="Delete this campaign?" class="text-red-400 hover:text-red-300 transition">Delete</button>
            </div>
        </div>
        @empty
        <div class="col-span-full text-center py-16">
            <svg class="w-12 h-12 text-gray-600 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
            <p class="text-gray-500 text-sm">No campaigns yet</p>
            <a href="{{ route('campaigns.create') }}" class="mt-2 text-sm text-brand-400 hover:underline">Create your first campaign →</a>
        </div>
        @endforelse
    </div>

    <div class="mt-6">{{ $campaigns->links() }}</div>
</div>
