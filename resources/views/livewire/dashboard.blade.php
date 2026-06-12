<div>
    @section('title', 'Dashboard')

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <!-- Total Leads -->
        <div class="stat-card glass rounded-xl p-5">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 rounded-lg bg-brand-500/20 flex items-center justify-center">
                    <svg class="w-5 h-5 text-brand-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
                <span class="text-xs text-emerald-400 font-medium bg-emerald-400/10 px-2 py-0.5 rounded-full">+{{ $newLeadsThisWeek }} this week</span>
            </div>
            <p class="text-2xl font-bold text-white">{{ number_format($totalLeads) }}</p>
            <p class="text-xs text-gray-500 mt-1">Total Leads</p>
        </div>

        <!-- Hot Leads -->
        <div class="stat-card glass rounded-xl p-5">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 rounded-lg bg-red-500/20 flex items-center justify-center">
                    <svg class="w-5 h-5 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"/></svg>
                </div>
                <span class="pulse-dot w-2 h-2 rounded-full bg-red-500"></span>
            </div>
            <p class="text-2xl font-bold text-white">{{ $hotLeads }}</p>
            <p class="text-xs text-gray-500 mt-1">Hot Leads (Score ≥70)</p>
        </div>

        <!-- Follow-ups Today -->
        <div class="stat-card glass rounded-xl p-5">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 rounded-lg bg-amber-500/20 flex items-center justify-center">
                    <svg class="w-5 h-5 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
                @if($overdueFollowUps > 0)
                <span class="text-xs text-red-400 font-medium bg-red-400/10 px-2 py-0.5 rounded-full">{{ $overdueFollowUps }} overdue</span>
                @endif
            </div>
            <p class="text-2xl font-bold text-white">{{ $followUpToday }}</p>
            <p class="text-xs text-gray-500 mt-1">Follow-ups Today</p>
        </div>

        <!-- Won Value -->
        <div class="stat-card glass rounded-xl p-5">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 rounded-lg bg-emerald-500/20 flex items-center justify-center">
                    <svg class="w-5 h-5 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <p class="text-2xl font-bold text-white">₹{{ number_format($wonValue) }}</p>
            <p class="text-xs text-gray-500 mt-1">Won Pipeline Value</p>
        </div>
    </div>

    <!-- Pipeline & Campaign Row -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-6">
        <!-- Pipeline Stages -->
        <div class="lg:col-span-2 glass rounded-xl p-5">
            <h3 class="text-sm font-semibold text-white mb-4 flex items-center gap-2">
                <svg class="w-4 h-4 text-brand-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                Sales Pipeline
            </h3>
            <div class="flex gap-2 overflow-x-auto pb-2">
                @foreach($stages as $stage)
                <div class="flex-shrink-0 w-36 rounded-lg p-3 border border-gray-700/50 hover:border-brand-500/30 transition" style="background: {{ $stage->color }}10;">
                    <div class="flex items-center gap-2 mb-2">
                        <div class="w-2 h-2 rounded-full" style="background: {{ $stage->color }};"></div>
                        <span class="text-xs font-medium text-gray-300 truncate">{{ $stage->name }}</span>
                    </div>
                    <p class="text-xl font-bold text-white">{{ $stage->leads_count }}</p>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Campaign Stats -->
        <div class="glass rounded-xl p-5">
            <h3 class="text-sm font-semibold text-white mb-4 flex items-center gap-2">
                <svg class="w-4 h-4 text-brand-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                Email Campaigns
            </h3>
            <div class="space-y-3">
                <div class="flex justify-between items-center">
                    <span class="text-xs text-gray-400">Total Campaigns</span>
                    <span class="text-sm font-bold text-white">{{ $campaignStats['total'] }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-xs text-gray-400">Active</span>
                    <span class="text-sm font-bold text-emerald-400">{{ $campaignStats['active'] }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-xs text-gray-400">Emails Sent</span>
                    <span class="text-sm font-bold text-white">{{ number_format($campaignStats['total_sent']) }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-xs text-gray-400">Emails Opened</span>
                    <span class="text-sm font-bold text-brand-400">{{ number_format($campaignStats['total_opened']) }}</span>
                </div>
                @if($campaignStats['total_sent'] > 0)
                <div class="pt-2 border-t border-gray-700/50">
                    <div class="flex justify-between items-center">
                        <span class="text-xs text-gray-400">Open Rate</span>
                        <span class="text-sm font-bold text-brand-400">{{ round(($campaignStats['total_opened'] / max(1, $campaignStats['total_sent'])) * 100, 1) }}%</span>
                    </div>
                </div>
                @endif
            </div>

            <a href="{{ route('campaigns.create') }}" class="mt-4 w-full flex items-center justify-center gap-2 px-4 py-2 bg-brand-600/20 border border-brand-500/30 rounded-lg text-sm text-brand-400 hover:bg-brand-600/30 transition">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                New Campaign
            </a>
        </div>
    </div>

    <!-- Quick Actions & Recent Activity -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
        <!-- Quick Actions -->
        <div class="glass rounded-xl p-5">
            <h3 class="text-sm font-semibold text-white mb-4">Quick Actions</h3>
            <div class="space-y-2">
                <a href="{{ route('leads.create') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg bg-brand-600/10 border border-brand-500/20 hover:bg-brand-600/20 transition">
                    <div class="w-8 h-8 rounded-lg bg-brand-500/20 flex items-center justify-center">
                        <svg class="w-4 h-4 text-brand-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-200">Add New Lead</p>
                        <p class="text-xs text-gray-500">Manually add a lead</p>
                    </div>
                </a>

                <a href="{{ route('ai.assistant') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg bg-purple-600/10 border border-purple-500/20 hover:bg-purple-600/20 transition">
                    <div class="w-8 h-8 rounded-lg bg-purple-500/20 flex items-center justify-center">
                        <svg class="w-4 h-4 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-200">AI Discover Schools</p>
                        <p class="text-xs text-gray-500">Find schools in any city</p>
                    </div>
                </a>

                <a href="{{ route('campaigns.create') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg bg-emerald-600/10 border border-emerald-500/20 hover:bg-emerald-600/20 transition">
                    <div class="w-8 h-8 rounded-lg bg-emerald-500/20 flex items-center justify-center">
                        <svg class="w-4 h-4 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-200">Start Campaign</p>
                        <p class="text-xs text-gray-500">Email your leads</p>
                    </div>
                </a>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="lg:col-span-2 glass rounded-xl p-5">
            <h3 class="text-sm font-semibold text-white mb-4 flex items-center gap-2">
                <svg class="w-4 h-4 text-brand-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Recent Activity
            </h3>
            <div class="space-y-3 max-h-64 overflow-y-auto">
                @forelse($recentActivities as $activity)
                <div class="flex items-start gap-3 py-2 border-b border-gray-800/50 last:border-0">
                    <span class="text-lg flex-shrink-0 mt-0.5">{{ $activity->icon }}</span>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm text-gray-300">
                            <a href="{{ route('leads.show', $activity->lead_id) }}" class="font-medium text-brand-400 hover:underline">{{ $activity->lead?->school_name ?? 'Unknown' }}</a>
                            — {{ $activity->description }}
                        </p>
                        <p class="text-xs text-gray-500 mt-0.5">
                            {{ $activity->user?->name ?? 'System' }} · {{ $activity->created_at->diffForHumans() }}
                        </p>
                    </div>
                </div>
                @empty
                <p class="text-sm text-gray-500 text-center py-8">No recent activity. Start by adding some leads!</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Status Breakdown Chart -->
    <div class="mt-6 glass rounded-xl p-5">
        <h3 class="text-sm font-semibold text-white mb-4">Lead Status Breakdown</h3>
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 lg:grid-cols-9 gap-2">
            @php
                $statusColors = [
                    'new' => '#6366f1', 'contacted' => '#3b82f6', 'qualified' => '#06b6d4',
                    'proposal_sent' => '#f59e0b', 'negotiation' => '#f97316', 'demo_scheduled' => '#8b5cf6',
                    'won' => '#10b981', 'lost' => '#ef4444', 'on_hold' => '#6b7280',
                ];
            @endphp
            @foreach($statusColors as $status => $color)
            <div class="text-center p-3 rounded-lg border border-gray-700/50" style="background: {{ $color }}10;">
                <p class="text-lg font-bold text-white">{{ $statusCounts[$status] ?? 0 }}</p>
                <p class="text-xs text-gray-400 capitalize mt-1">{{ str_replace('_', ' ', $status) }}</p>
            </div>
            @endforeach
        </div>
    </div>
</div>
