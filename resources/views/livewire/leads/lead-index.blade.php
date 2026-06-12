<div>
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-xl font-bold text-white">Leads</h1>
            <p class="text-sm text-gray-400">Manage and track all your potential clients</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('leads.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-brand-600 hover:bg-brand-700 text-white text-sm font-medium rounded-lg transition shadow-lg shadow-brand-600/20">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Add Lead
            </a>
        </div>
    </div>

    <!-- Filters -->
    <div class="glass rounded-xl p-4 mb-6">
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-3">
            <div class="lg:col-span-2">
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search school, contact, email..."
                       class="w-full bg-surface-800/50 border border-gray-700/50 rounded-lg px-4 py-2 text-sm text-gray-300 placeholder-gray-500 focus:outline-none focus:border-brand-500/50">
            </div>
            <select wire:model.live="statusFilter" class="bg-surface-800/50 border border-gray-700/50 rounded-lg px-3 py-2 text-sm text-gray-300 focus:outline-none focus:border-brand-500/50">
                <option value="">All Status</option>
                @foreach(['new','contacted','qualified','proposal_sent','negotiation','demo_scheduled','won','lost','on_hold'] as $s)
                <option value="{{ $s }}">{{ ucfirst(str_replace('_', ' ', $s)) }}</option>
                @endforeach
            </select>
            <select wire:model.live="stageFilter" class="bg-surface-800/50 border border-gray-700/50 rounded-lg px-3 py-2 text-sm text-gray-300 focus:outline-none focus:border-brand-500/50">
                <option value="">All Stages</option>
                @foreach($stages as $stage)
                <option value="{{ $stage->id }}">{{ $stage->name }}</option>
                @endforeach
            </select>
            <select wire:model.live="typeFilter" class="bg-surface-800/50 border border-gray-700/50 rounded-lg px-3 py-2 text-sm text-gray-300 focus:outline-none focus:border-brand-500/50">
                <option value="">All Types</option>
                <option value="school">School</option>
                <option value="college">College</option>
                <option value="coaching">Coaching</option>
                <option value="university">University</option>
            </select>
            <select wire:model.live="assignedFilter" class="bg-surface-800/50 border border-gray-700/50 rounded-lg px-3 py-2 text-sm text-gray-300 focus:outline-none focus:border-brand-500/50">
                <option value="">All Assignees</option>
                @foreach($teamMembers as $member)
                <option value="{{ $member->id }}">{{ $member->name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <!-- Leads Table -->
    <div class="glass rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-700/50">
                        <th class="text-left py-3 px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider cursor-pointer hover:text-brand-400" wire:click="sortBy('school_name')">
                            School/Institution
                            @if($sortField === 'school_name') <span class="text-brand-400">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span> @endif
                        </th>
                        <th class="text-left py-3 px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Contact</th>
                        <th class="text-left py-3 px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Location</th>
                        <th class="text-left py-3 px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Status</th>
                        <th class="text-left py-3 px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider cursor-pointer hover:text-brand-400" wire:click="sortBy('lead_score')">
                            Score
                            @if($sortField === 'lead_score') <span class="text-brand-400">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span> @endif
                        </th>
                        <th class="text-left py-3 px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Assigned</th>
                        <th class="text-left py-3 px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($leads as $lead)
                    <tr class="table-row border-b border-gray-800/50 last:border-0">
                        <td class="py-3 px-4">
                            <a href="{{ route('leads.show', $lead->id) }}" class="text-white font-medium hover:text-brand-400 transition">{{ $lead->school_name }}</a>
                            <div class="flex items-center gap-1 mt-1">
                                <span class="text-xs text-gray-500 capitalize">{{ $lead->type }}</span>
                                @if($lead->board)
                                <span class="text-xs text-gray-600">·</span>
                                <span class="text-xs text-gray-500">{{ $lead->board }}</span>
                                @endif
                            </div>
                            @if($lead->tags->count())
                            <div class="flex flex-wrap gap-1 mt-1">
                                @foreach($lead->tags->take(3) as $tag)
                                <span class="text-[10px] px-1.5 py-0.5 rounded-full text-white/80" style="background: {{ $tag->color }}40;">{{ $tag->name }}</span>
                                @endforeach
                            </div>
                            @endif
                        </td>
                        <td class="py-3 px-4">
                            <p class="text-gray-300 text-sm">{{ $lead->contact_person ?: '—' }}</p>
                            <p class="text-xs text-gray-500">{{ $lead->email ?: 'No email' }}</p>
                            <p class="text-xs text-gray-500">{{ $lead->phone ?: '' }}</p>
                        </td>
                        <td class="py-3 px-4">
                            <p class="text-gray-300 text-sm">{{ $lead->city ?: '—' }}</p>
                            <p class="text-xs text-gray-500">{{ $lead->state ?: '' }}</p>
                        </td>
                        <td class="py-3 px-4">
                            @php
                                $statusColor = match($lead->status) {
                                    'new' => 'bg-indigo-500/20 text-indigo-300',
                                    'contacted' => 'bg-blue-500/20 text-blue-300',
                                    'qualified' => 'bg-cyan-500/20 text-cyan-300',
                                    'won' => 'bg-emerald-500/20 text-emerald-300',
                                    'lost' => 'bg-red-500/20 text-red-300',
                                    default => 'bg-gray-500/20 text-gray-300',
                                };
                            @endphp
                            <span class="text-xs px-2 py-1 rounded-full font-medium {{ $statusColor }}">
                                {{ ucfirst(str_replace('_', ' ', $lead->status)) }}
                            </span>
                            @if($lead->follow_up_date)
                            <p class="text-[10px] mt-1 {{ $lead->follow_up_date->isPast() ? 'text-red-400' : 'text-gray-500' }}">
                                📅 {{ $lead->follow_up_date->format('M d') }}
                            </p>
                            @endif
                        </td>
                        <td class="py-3 px-4">
                            @php
                                $scoreClass = match(true) {
                                    $lead->lead_score >= 80 => 'score-hot',
                                    $lead->lead_score >= 50 => 'score-warm',
                                    $lead->lead_score >= 20 => 'score-cool',
                                    default => 'score-cold',
                                };
                            @endphp
                            <span class="text-xs px-2 py-1 rounded-full font-bold text-white {{ $scoreClass }}">
                                {{ $lead->lead_score }}
                            </span>
                        </td>
                        <td class="py-3 px-4">
                            @if($lead->assignedUser)
                            <div class="flex items-center gap-2">
                                <div class="w-6 h-6 rounded-full bg-brand-500/30 flex items-center justify-center text-[10px] font-bold text-brand-300">
                                    {{ substr($lead->assignedUser->name, 0, 1) }}
                                </div>
                                <span class="text-xs text-gray-400">{{ $lead->assignedUser->name }}</span>
                            </div>
                            @else
                            <span class="text-xs text-gray-600">Unassigned</span>
                            @endif
                        </td>
                        <td class="py-3 px-4">
                            <div class="flex items-center gap-1">
                                <a href="{{ route('leads.show', $lead->id) }}" class="p-1.5 rounded-lg hover:bg-surface-700 transition text-gray-400 hover:text-brand-400" title="View">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </a>
                                <a href="{{ route('leads.edit', $lead->id) }}" class="p-1.5 rounded-lg hover:bg-surface-700 transition text-gray-400 hover:text-amber-400" title="Edit">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                                <button wire:click="deleteLead({{ $lead->id }})" wire:confirm="Delete this lead?" class="p-1.5 rounded-lg hover:bg-surface-700 transition text-gray-400 hover:text-red-400" title="Delete">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-16">
                            <div class="flex flex-col items-center">
                                <svg class="w-12 h-12 text-gray-600 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                <p class="text-gray-500 text-sm">No leads found</p>
                                <a href="{{ route('leads.create') }}" class="mt-3 text-sm text-brand-400 hover:underline">Add your first lead →</a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-4 py-3 border-t border-gray-800/50">
            {{ $leads->links() }}
        </div>
    </div>
</div>
