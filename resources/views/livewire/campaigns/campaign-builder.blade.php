<div class="max-w-3xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('campaigns.index') }}" class="text-sm text-gray-400 hover:text-brand-400 transition">← Back to Campaigns</a>
        <h1 class="text-xl font-bold text-white mt-2">Create Campaign</h1>
    </div>

    <!-- Step Indicator -->
    <div class="flex items-center gap-4 mb-8">
        @foreach([1 => 'Campaign Info', 2 => 'Select Audience', 3 => 'Preview & Send'] as $num => $label)
        <div class="flex items-center gap-2 {{ $step >= $num ? 'text-brand-400' : 'text-gray-600' }}">
            <div class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold {{ $step >= $num ? 'bg-brand-600 text-white' : 'bg-surface-700 text-gray-500' }}">{{ $num }}</div>
            <span class="text-sm font-medium hidden sm:inline">{{ $label }}</span>
        </div>
        @if($num < 3)
        <div class="flex-1 h-px {{ $step > $num ? 'bg-brand-500' : 'bg-gray-700' }}"></div>
        @endif
        @endforeach
    </div>

    <!-- Step 1: Campaign Info -->
    @if($step === 1)
    <div class="glass rounded-xl p-6 space-y-4 fade-in">
        <div>
            <label class="block text-xs font-medium text-gray-400 mb-1">Campaign Name *</label>
            <input wire:model="name" type="text" class="w-full bg-surface-800/50 border border-gray-700/50 rounded-lg px-4 py-2.5 text-sm text-gray-200 focus:outline-none focus:border-brand-500/50" placeholder="e.g. Summer Outreach - Mumbai Schools">
            @error('name') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-400 mb-1">Email Subject *</label>
            <input wire:model="subject" type="text" class="w-full bg-surface-800/50 border border-gray-700/50 rounded-lg px-4 py-2.5 text-sm text-gray-200 focus:outline-none focus:border-brand-500/50" placeholder="e.g. Transform Your School with Smart ERP">
            @error('subject') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-400 mb-1">Email Template *</label>
            <select wire:model="template_id" class="w-full bg-surface-800/50 border border-gray-700/50 rounded-lg px-4 py-2.5 text-sm text-gray-200 focus:outline-none focus:border-brand-500/50">
                <option value="">Select a template</option>
                @foreach($templates as $t)
                <option value="{{ $t->id }}">{{ $t->name }}</option>
                @endforeach
            </select>
            @error('template_id') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-medium text-gray-400 mb-1">From Name</label>
                <input wire:model="from_name" type="text" class="w-full bg-surface-800/50 border border-gray-700/50 rounded-lg px-4 py-2.5 text-sm text-gray-200 focus:outline-none focus:border-brand-500/50" placeholder="GetConnect Team">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-400 mb-1">From Email</label>
                <input wire:model="from_email" type="email" class="w-full bg-surface-800/50 border border-gray-700/50 rounded-lg px-4 py-2.5 text-sm text-gray-200 focus:outline-none focus:border-brand-500/50" placeholder="sales@getconnect.com">
            </div>
        </div>
    </div>
    @endif

    <!-- Step 2: Audience Selection -->
    @if($step === 2)
    <div class="glass rounded-xl p-6 space-y-4 fade-in">
        <h3 class="text-sm font-semibold text-white">Filter Your Audience</h3>
        <p class="text-xs text-gray-400">Select which leads should receive this campaign</p>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-medium text-gray-400 mb-1">Lead Status</label>
                <select wire:model="statusFilters" multiple class="w-full bg-surface-800/50 border border-gray-700/50 rounded-lg px-4 py-2 text-sm text-gray-200 focus:outline-none focus:border-brand-500/50" size="4">
                    @foreach(['new','contacted','qualified','proposal_sent','negotiation','demo_scheduled'] as $s)
                    <option value="{{ $s }}">{{ ucfirst(str_replace('_', ' ', $s)) }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-400 mb-1">Institution Type</label>
                <select wire:model="typeFilters" multiple class="w-full bg-surface-800/50 border border-gray-700/50 rounded-lg px-4 py-2 text-sm text-gray-200 focus:outline-none focus:border-brand-500/50" size="4">
                    <option value="school">School</option>
                    <option value="college">College</option>
                    <option value="coaching">Coaching</option>
                    <option value="university">University</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-400 mb-1">City</label>
                <input wire:model="cityFilter" type="text" class="w-full bg-surface-800/50 border border-gray-700/50 rounded-lg px-4 py-2.5 text-sm text-gray-200 focus:outline-none focus:border-brand-500/50" placeholder="Filter by city">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-400 mb-1">Pipeline Stage</label>
                <select wire:model="stageFilter" class="w-full bg-surface-800/50 border border-gray-700/50 rounded-lg px-4 py-2.5 text-sm text-gray-200 focus:outline-none focus:border-brand-500/50">
                    <option value="">All Stages</option>
                    @foreach($stages as $stage)
                    <option value="{{ $stage->id }}">{{ $stage->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-400 mb-1">Minimum Lead Score</label>
                <input wire:model="minScore" type="number" min="0" max="100" class="w-full bg-surface-800/50 border border-gray-700/50 rounded-lg px-4 py-2.5 text-sm text-gray-200 focus:outline-none focus:border-brand-500/50" placeholder="0">
            </div>
        </div>

        <button wire:click="calculateRecipients" class="px-4 py-2 bg-surface-700 hover:bg-surface-600 text-sm text-gray-300 rounded-lg transition">
            🔢 Calculate Recipients
        </button>

        @if($recipientCount > 0)
        <div class="p-3 rounded-lg bg-brand-500/10 border border-brand-500/20 text-sm text-brand-300">
            📧 This campaign will be sent to <strong>{{ $recipientCount }}</strong> leads
        </div>
        @endif
    </div>
    @endif

    <!-- Step 3: Preview & Send -->
    @if($step === 3)
    <div class="glass rounded-xl p-6 space-y-4 fade-in">
        <h3 class="text-sm font-semibold text-white">Campaign Summary</h3>
        <div class="space-y-2 text-sm">
            <div class="flex justify-between py-2 border-b border-gray-800/50">
                <span class="text-gray-400">Name</span>
                <span class="text-white">{{ $name }}</span>
            </div>
            <div class="flex justify-between py-2 border-b border-gray-800/50">
                <span class="text-gray-400">Subject</span>
                <span class="text-white">{{ $subject }}</span>
            </div>
            <div class="flex justify-between py-2 border-b border-gray-800/50">
                <span class="text-gray-400">Recipients</span>
                <span class="text-brand-400 font-bold">{{ $recipientCount }} leads</span>
            </div>
        </div>

        <div class="flex gap-3 pt-4">
            <button wire:click="saveDraft" class="px-6 py-2.5 bg-surface-700 hover:bg-surface-600 text-gray-300 text-sm rounded-lg transition">
                Save as Draft
            </button>
            <button wire:click="scheduleNow" class="px-6 py-2.5 bg-brand-600 hover:bg-brand-700 text-white text-sm font-medium rounded-lg transition shadow-lg shadow-brand-600/20">
                🚀 Send Now
            </button>
        </div>
    </div>
    @endif

    <!-- Navigation -->
    <div class="flex justify-between mt-6">
        @if($step > 1)
        <button wire:click="previousStep" class="px-4 py-2 text-sm text-gray-400 hover:text-gray-200 transition">← Previous</button>
        @else
        <div></div>
        @endif

        @if($step < 3)
        <button wire:click="nextStep" class="px-6 py-2.5 bg-brand-600 hover:bg-brand-700 text-white text-sm font-medium rounded-lg transition">
            Next →
        </button>
        @endif
    </div>
</div>
