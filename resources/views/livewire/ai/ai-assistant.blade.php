<div>
    <div class="mb-6">
        <h1 class="text-xl font-bold text-white flex items-center gap-2">🤖 AI School Discovery</h1>
        <p class="text-sm text-gray-400 mt-1">Use AI to find schools, colleges, and coaching centers in any city and import them as leads</p>
    </div>

    <!-- Search Form -->
    <div class="glass rounded-xl p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="md:col-span-1">
                <label class="block text-xs font-medium text-gray-400 mb-1">City *</label>
                <input wire:model="city" type="text" class="w-full bg-surface-800/50 border border-gray-700/50 rounded-lg px-4 py-2.5 text-sm text-gray-200 focus:outline-none focus:border-brand-500/50" placeholder="e.g. Mumbai, Delhi, Pune">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-400 mb-1">State</label>
                <input wire:model="state" type="text" class="w-full bg-surface-800/50 border border-gray-700/50 rounded-lg px-4 py-2.5 text-sm text-gray-200 focus:outline-none focus:border-brand-500/50" placeholder="Maharashtra">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-400 mb-1">Type</label>
                <select wire:model="type" class="w-full bg-surface-800/50 border border-gray-700/50 rounded-lg px-4 py-2.5 text-sm text-gray-200 focus:outline-none focus:border-brand-500/50">
                    <option value="school">Schools</option>
                    <option value="college">Colleges</option>
                    <option value="coaching">Coaching Centers</option>
                    <option value="university">Universities</option>
                </select>
            </div>
            <div class="flex items-end">
                <button wire:click="discover" wire:loading.attr="disabled" class="w-full px-6 py-2.5 bg-gradient-to-r from-brand-600 to-purple-600 hover:from-brand-700 hover:to-purple-700 text-white text-sm font-medium rounded-lg transition shadow-lg shadow-brand-600/20 disabled:opacity-50">
                    <span wire:loading.remove wire:target="discover">🔍 Discover with AI</span>
                    <span wire:loading wire:target="discover" class="flex items-center gap-2">
                        <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                        AI is searching...
                    </span>
                </button>
            </div>
        </div>
    </div>

    <!-- Error -->
    @if($error)
    <div class="mb-4 p-4 rounded-lg bg-red-500/10 border border-red-500/20 text-sm text-red-400">
        ⚠️ {{ $error }}
    </div>
    @endif

    <!-- Results -->
    @if(count($discoveredSchools) > 0)
    <div class="glass rounded-xl p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-semibold text-white">Found {{ count($discoveredSchools) }} institutions in {{ $city }}</h3>
            <div class="flex gap-2">
                <button wire:click="selectAll" class="px-3 py-1.5 text-xs bg-surface-700 hover:bg-surface-600 text-gray-300 rounded-lg transition">Select All</button>
                @if(count($selectedSchools) > 0)
                <button wire:click="importSelected" class="px-4 py-1.5 text-xs bg-brand-600 hover:bg-brand-700 text-white rounded-lg transition shadow-lg shadow-brand-600/20">
                    Import {{ count($selectedSchools) }} as Leads
                </button>
                @endif
            </div>
        </div>

        <div class="space-y-3">
            @foreach($discoveredSchools as $index => $school)
            <div class="p-4 rounded-lg border transition cursor-pointer {{ in_array($index, $selectedSchools) ? 'border-brand-500/50 bg-brand-500/5' : 'border-gray-700/50 bg-surface-800/30 hover:border-gray-600' }}"
                 wire:click="toggleSelect({{ $index }})">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <div class="flex items-center gap-3">
                            <input type="checkbox" {{ in_array($index, $selectedSchools) ? 'checked' : '' }} class="rounded border-gray-600 text-brand-600 focus:ring-brand-500">
                            <h4 class="text-sm font-semibold text-white">{{ $school['name'] ?? 'Unknown' }}</h4>
                        </div>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-2 mt-2 ml-7">
                            @if(($school['contact_person'] ?? 'N/A') !== 'N/A')
                            <p class="text-xs text-gray-400">👤 {{ $school['contact_person'] }}</p>
                            @endif
                            @if(($school['email'] ?? 'N/A') !== 'N/A')
                            <p class="text-xs text-gray-400">✉️ {{ $school['email'] }}</p>
                            @endif
                            @if(($school['phone'] ?? 'N/A') !== 'N/A')
                            <p class="text-xs text-gray-400">📞 {{ $school['phone'] }}</p>
                            @endif
                            @if(($school['board'] ?? '') !== '')
                            <p class="text-xs text-gray-400">📚 {{ $school['board'] }}</p>
                            @endif
                            @if(is_numeric($school['student_count'] ?? null) && $school['student_count'] > 0)
                            <p class="text-xs text-gray-400">👨‍🎓 {{ number_format($school['student_count']) }} students</p>
                            @endif
                            @if(($school['address'] ?? '') !== '')
                            <p class="text-xs text-gray-400 col-span-2">📍 {{ $school['address'] }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Empty State -->
    @if(!$isLoading && count($discoveredSchools) === 0 && !$error)
    <div class="glass rounded-xl p-16 text-center">
        <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-brand-500/20 to-purple-500/20 flex items-center justify-center mx-auto mb-4">
            <span class="text-3xl">🤖</span>
        </div>
        <h3 class="text-lg font-semibold text-white mb-2">AI-Powered School Discovery</h3>
        <p class="text-sm text-gray-400 max-w-md mx-auto">Enter a city name and type of institution, and our AI will find potential clients in that area. You can then import them directly as leads.</p>
        <div class="flex flex-wrap justify-center gap-2 mt-6">
            <span class="text-xs px-3 py-1.5 rounded-full bg-surface-700 text-gray-400">Try: Mumbai</span>
            <span class="text-xs px-3 py-1.5 rounded-full bg-surface-700 text-gray-400">Try: Delhi</span>
            <span class="text-xs px-3 py-1.5 rounded-full bg-surface-700 text-gray-400">Try: Bangalore</span>
            <span class="text-xs px-3 py-1.5 rounded-full bg-surface-700 text-gray-400">Try: Pune</span>
        </div>
    </div>
    @endif
</div>
