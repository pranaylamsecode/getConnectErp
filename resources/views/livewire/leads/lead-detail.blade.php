<div>
    <div class="mb-4">
        <a href="{{ route('leads.index') }}" class="text-sm text-gray-400 hover:text-brand-400 transition">← Back to Leads</a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Info -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Header Card -->
            <div class="glass rounded-xl p-6">
                <div class="flex items-start justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-white">{{ $lead->school_name }}</h1>
                        <div class="flex items-center gap-3 mt-2">
                            <span class="text-xs px-2 py-1 rounded-full bg-brand-500/20 text-brand-300 capitalize">{{ $lead->type }}</span>
                            @if($lead->board)
                            <span class="text-xs text-gray-400">{{ $lead->board }}</span>
                            @endif
                            @if($lead->student_count)
                            <span class="text-xs text-gray-400">👨‍🎓 {{ number_format($lead->student_count) }} students</span>
                            @endif
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('leads.edit', $lead->id) }}" class="px-3 py-1.5 text-xs bg-surface-700 hover:bg-surface-600 text-gray-300 rounded-lg transition">Edit</a>
                        @php
                            $scoreClass = match(true) {
                                $lead->lead_score >= 80 => 'score-hot',
                                $lead->lead_score >= 50 => 'score-warm',
                                $lead->lead_score >= 20 => 'score-cool',
                                default => 'score-cold',
                            };
                        @endphp
                        <span class="text-sm px-3 py-1.5 rounded-lg font-bold text-white {{ $scoreClass }}">
                            Score: {{ $lead->lead_score }}/100
                        </span>
                    </div>
                </div>

                <!-- Contact & Location -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-6">
                    <div class="space-y-2">
                        <p class="text-xs font-semibold text-gray-400 uppercase">Contact</p>
                        @if($lead->contact_person)
                        <p class="text-sm text-gray-200">👤 {{ $lead->contact_person }}</p>
                        @endif
                        @if($lead->email)
                        <p class="text-sm text-gray-200">✉️ {{ $lead->email }}</p>
                        @endif
                        @if($lead->phone)
                        <p class="text-sm text-gray-200">📞 {{ $lead->phone }}</p>
                        @endif
                        @if($lead->website)
                        <p class="text-sm"><a href="{{ $lead->website }}" target="_blank" class="text-brand-400 hover:underline">🌐 {{ $lead->website }}</a></p>
                        @endif
                    </div>
                    <div class="space-y-2">
                        <p class="text-xs font-semibold text-gray-400 uppercase">Location</p>
                        @if($lead->address)
                        <p class="text-sm text-gray-200">📍 {{ $lead->address }}</p>
                        @endif
                        <p class="text-sm text-gray-200">{{ $lead->city }}{{ $lead->state ? ', ' . $lead->state : '' }} {{ $lead->pincode }}</p>
                    </div>
                </div>

                <!-- Tags -->
                @if($lead->tags->count())
                <div class="flex flex-wrap gap-2 mt-4 pt-4 border-t border-gray-700/50">
                    @foreach($lead->tags as $tag)
                    <span class="text-xs px-2 py-1 rounded-full text-white/80" style="background: {{ $tag->color }}40;">{{ $tag->name }}</span>
                    @endforeach
                </div>
                @endif
            </div>

            <!-- AI Data -->
            @if($lead->ai_data)
            <div class="glass rounded-xl p-6">
                <h3 class="text-sm font-semibold text-white mb-4 flex items-center gap-2">
                    🤖 AI Enriched Data
                </h3>
                <div class="grid grid-cols-2 gap-3 text-sm">
                    @foreach($lead->ai_data as $key => $value)
                        @if(!is_array($value))
                        <div>
                            <span class="text-xs text-gray-500 capitalize">{{ str_replace('_', ' ', $key) }}</span>
                            <p class="text-gray-300">{{ $value ?: 'N/A' }}</p>
                        </div>
                        @elseif($key === 'facilities' || $key === 'pain_points')
                        <div class="col-span-2">
                            <span class="text-xs text-gray-500 capitalize">{{ str_replace('_', ' ', $key) }}</span>
                            <div class="flex flex-wrap gap-1 mt-1">
                                @foreach($value as $item)
                                <span class="text-xs px-2 py-0.5 rounded bg-surface-700 text-gray-300">{{ $item }}</span>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Notes Section -->
            <div class="glass rounded-xl p-6">
                <h3 class="text-sm font-semibold text-white mb-4">Notes</h3>
                <div class="flex gap-2 mb-4">
                    <input wire:model="newNote" type="text" placeholder="Add a note..." class="flex-1 bg-surface-800/50 border border-gray-700/50 rounded-lg px-4 py-2 text-sm text-gray-200 focus:outline-none focus:border-brand-500/50" wire:keydown.enter="addNote">
                    <button wire:click="addNote" class="px-4 py-2 bg-brand-600 hover:bg-brand-700 text-white text-sm rounded-lg transition">Add</button>
                </div>
                <div class="space-y-3 max-h-64 overflow-y-auto">
                    @forelse($lead->notes as $note)
                    <div class="p-3 rounded-lg bg-surface-800/30 border border-gray-700/30">
                        <p class="text-sm text-gray-300">{{ $note->content }}</p>
                        <p class="text-xs text-gray-500 mt-1">{{ $note->user?->name ?? 'Unknown' }} · {{ $note->created_at->diffForHumans() }}</p>
                    </div>
                    @empty
                    <p class="text-sm text-gray-500 text-center py-4">No notes yet</p>
                    @endforelse
                </div>
            </div>

            <!-- Activity Timeline -->
            <div class="glass rounded-xl p-6">
                <h3 class="text-sm font-semibold text-white mb-4">Activity Timeline</h3>
                <div class="space-y-3 max-h-96 overflow-y-auto">
                    @forelse($lead->activities as $activity)
                    <div class="flex items-start gap-3 py-2 border-b border-gray-800/50 last:border-0">
                        <span class="text-lg flex-shrink-0">{{ $activity->icon }}</span>
                        <div>
                            <p class="text-sm text-gray-300">{{ $activity->description }}</p>
                            <p class="text-xs text-gray-500 mt-0.5">{{ $activity->user?->name ?? 'System' }} · {{ $activity->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                    @empty
                    <p class="text-sm text-gray-500 text-center py-4">No activity yet</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-4">
            <!-- Status & Stage -->
            <div class="glass rounded-xl p-5">
                <h3 class="text-sm font-semibold text-white mb-3">Status & Pipeline</h3>
                <div class="space-y-3">
                    <div>
                        <label class="text-xs text-gray-400 mb-1 block">Status</label>
                        <select wire:model="newStatus" wire:change="updateStatus" class="w-full bg-surface-800/50 border border-gray-700/50 rounded-lg px-3 py-2 text-sm text-gray-200 focus:outline-none focus:border-brand-500/50">
                            @foreach($statusOptions as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="text-xs text-gray-400 mb-1 block">Pipeline Stage</label>
                        <select wire:model="newStageId" wire:change="updateStage" class="w-full bg-surface-800/50 border border-gray-700/50 rounded-lg px-3 py-2 text-sm text-gray-200 focus:outline-none focus:border-brand-500/50">
                            @foreach($stages as $stage)
                            <option value="{{ $stage->id }}">{{ $stage->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <!-- Sales Info -->
            <div class="glass rounded-xl p-5">
                <h3 class="text-sm font-semibold text-white mb-3">Sales Info</h3>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-400">Value</span>
                        <span class="text-white font-medium">₹{{ number_format($lead->estimated_value) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-400">Source</span>
                        <span class="text-gray-300">{{ $lead->source?->name ?? 'Unknown' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-400">Assigned</span>
                        <span class="text-gray-300">{{ $lead->assignedUser?->name ?? 'Unassigned' }}</span>
                    </div>
                    @if($lead->follow_up_date)
                    <div class="flex justify-between">
                        <span class="text-gray-400">Follow-up</span>
                        <span class="{{ $lead->follow_up_date->isPast() ? 'text-red-400' : 'text-gray-300' }}">{{ $lead->follow_up_date->format('M d, Y') }}</span>
                    </div>
                    @endif
                    <div class="flex justify-between">
                        <span class="text-gray-400">Created</span>
                        <span class="text-gray-300">{{ $lead->created_at->format('M d, Y') }}</span>
                    </div>
                </div>
            </div>

            <!-- AI Actions -->
            <div class="glass rounded-xl p-5">
                <h3 class="text-sm font-semibold text-white mb-3 flex items-center gap-2">
                    🤖 AI Actions
                </h3>
                <div class="space-y-2">
                    <button wire:click="enrichWithAI" class="w-full flex items-center gap-2 px-3 py-2 bg-purple-600/10 border border-purple-500/20 rounded-lg text-sm text-purple-300 hover:bg-purple-600/20 transition">
                        <span>🔍</span> Enrich with AI
                    </button>
                    <button wire:click="scoreWithAI" class="w-full flex items-center gap-2 px-3 py-2 bg-amber-600/10 border border-amber-500/20 rounded-lg text-sm text-amber-300 hover:bg-amber-600/20 transition">
                        <span>⭐</span> AI Score Lead
                    </button>
                    <button wire:click="generateEmail('introduction')" class="w-full flex items-center gap-2 px-3 py-2 bg-emerald-600/10 border border-emerald-500/20 rounded-lg text-sm text-emerald-300 hover:bg-emerald-600/20 transition">
                        <span>✉️</span> Generate Intro Email
                    </button>
                    <button wire:click="generateEmail('follow_up')" class="w-full flex items-center gap-2 px-3 py-2 bg-blue-600/10 border border-blue-500/20 rounded-lg text-sm text-blue-300 hover:bg-blue-600/20 transition">
                        <span>📩</span> Generate Follow-up
                    </button>
                    <button wire:click="generateEmail('demo_invite')" class="w-full flex items-center gap-2 px-3 py-2 bg-cyan-600/10 border border-cyan-500/20 rounded-lg text-sm text-cyan-300 hover:bg-cyan-600/20 transition">
                        <span>💻</span> Generate Demo Invite
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
