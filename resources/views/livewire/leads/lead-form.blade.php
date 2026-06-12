<div class="max-w-4xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('leads.index') }}" class="text-sm text-gray-400 hover:text-brand-400 transition">← Back to Leads</a>
        <h1 class="text-xl font-bold text-white mt-2">{{ $isEdit ? 'Edit Lead' : 'Add New Lead' }}</h1>
    </div>

    <form wire:submit="save" class="space-y-6">
        <!-- Basic Info -->
        <div class="glass rounded-xl p-6">
            <h2 class="text-sm font-semibold text-white mb-4 flex items-center gap-2">
                <svg class="w-4 h-4 text-brand-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                Institution Details
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="md:col-span-2">
                    <label class="block text-xs font-medium text-gray-400 mb-1">School/Institution Name *</label>
                    <input wire:model="school_name" type="text" class="w-full bg-surface-800/50 border border-gray-700/50 rounded-lg px-4 py-2.5 text-sm text-gray-200 focus:outline-none focus:border-brand-500/50 focus:ring-1 focus:ring-brand-500/30" placeholder="e.g. Delhi Public School">
                    @error('school_name') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-400 mb-1">Type</label>
                    <select wire:model="type" class="w-full bg-surface-800/50 border border-gray-700/50 rounded-lg px-4 py-2.5 text-sm text-gray-200 focus:outline-none focus:border-brand-500/50">
                        <option value="school">School</option>
                        <option value="college">College</option>
                        <option value="coaching">Coaching Center</option>
                        <option value="university">University</option>
                        <option value="other">Other</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-400 mb-1">Board</label>
                    <select wire:model="board" class="w-full bg-surface-800/50 border border-gray-700/50 rounded-lg px-4 py-2.5 text-sm text-gray-200 focus:outline-none focus:border-brand-500/50">
                        <option value="">Select Board</option>
                        <option value="CBSE">CBSE</option>
                        <option value="ICSE">ICSE</option>
                        <option value="State Board">State Board</option>
                        <option value="IB">IB</option>
                        <option value="Cambridge">Cambridge</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-400 mb-1">Medium</label>
                    <input wire:model="medium" type="text" class="w-full bg-surface-800/50 border border-gray-700/50 rounded-lg px-4 py-2.5 text-sm text-gray-200 focus:outline-none focus:border-brand-500/50" placeholder="English, Hindi, etc.">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-400 mb-1">Student Count</label>
                    <input wire:model="student_count" type="number" class="w-full bg-surface-800/50 border border-gray-700/50 rounded-lg px-4 py-2.5 text-sm text-gray-200 focus:outline-none focus:border-brand-500/50" placeholder="Approximate students">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-400 mb-1">Website</label>
                    <input wire:model="website" type="url" class="w-full bg-surface-800/50 border border-gray-700/50 rounded-lg px-4 py-2.5 text-sm text-gray-200 focus:outline-none focus:border-brand-500/50" placeholder="https://...">
                </div>
            </div>
        </div>

        <!-- Contact Info -->
        <div class="glass rounded-xl p-6">
            <h2 class="text-sm font-semibold text-white mb-4 flex items-center gap-2">
                <svg class="w-4 h-4 text-brand-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                Contact Information
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-gray-400 mb-1">Contact Person</label>
                    <input wire:model="contact_person" type="text" class="w-full bg-surface-800/50 border border-gray-700/50 rounded-lg px-4 py-2.5 text-sm text-gray-200 focus:outline-none focus:border-brand-500/50" placeholder="Principal/Director name">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-400 mb-1">Email</label>
                    <input wire:model="email" type="email" class="w-full bg-surface-800/50 border border-gray-700/50 rounded-lg px-4 py-2.5 text-sm text-gray-200 focus:outline-none focus:border-brand-500/50" placeholder="email@school.com">
                    @error('email') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-400 mb-1">Phone</label>
                    <input wire:model="phone" type="text" class="w-full bg-surface-800/50 border border-gray-700/50 rounded-lg px-4 py-2.5 text-sm text-gray-200 focus:outline-none focus:border-brand-500/50" placeholder="+91 ...">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-400 mb-1">Secondary Phone</label>
                    <input wire:model="secondary_phone" type="text" class="w-full bg-surface-800/50 border border-gray-700/50 rounded-lg px-4 py-2.5 text-sm text-gray-200 focus:outline-none focus:border-brand-500/50">
                </div>
            </div>
        </div>

        <!-- Location -->
        <div class="glass rounded-xl p-6">
            <h2 class="text-sm font-semibold text-white mb-4 flex items-center gap-2">
                <svg class="w-4 h-4 text-brand-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                Location
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-medium text-gray-400 mb-1">City</label>
                    <input wire:model="city" type="text" class="w-full bg-surface-800/50 border border-gray-700/50 rounded-lg px-4 py-2.5 text-sm text-gray-200 focus:outline-none focus:border-brand-500/50" placeholder="Mumbai">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-400 mb-1">State</label>
                    <select wire:model="state" class="w-full bg-surface-800/50 border border-gray-700/50 rounded-lg px-4 py-2.5 text-sm text-gray-200 focus:outline-none focus:border-brand-500/50">
                        <option value="">Select State</option>
                        @foreach($indianStates as $st)
                        <option value="{{ $st }}">{{ $st }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-400 mb-1">Pincode</label>
                    <input wire:model="pincode" type="text" class="w-full bg-surface-800/50 border border-gray-700/50 rounded-lg px-4 py-2.5 text-sm text-gray-200 focus:outline-none focus:border-brand-500/50" placeholder="400001">
                </div>
                <div class="md:col-span-3">
                    <label class="block text-xs font-medium text-gray-400 mb-1">Full Address</label>
                    <textarea wire:model="address" rows="2" class="w-full bg-surface-800/50 border border-gray-700/50 rounded-lg px-4 py-2.5 text-sm text-gray-200 focus:outline-none focus:border-brand-500/50" placeholder="Full address..."></textarea>
                </div>
            </div>
        </div>

        <!-- Sales Info -->
        <div class="glass rounded-xl p-6">
            <h2 class="text-sm font-semibold text-white mb-4 flex items-center gap-2">
                <svg class="w-4 h-4 text-brand-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                Sales Information
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div>
                    <label class="block text-xs font-medium text-gray-400 mb-1">Lead Source</label>
                    <select wire:model="source_id" class="w-full bg-surface-800/50 border border-gray-700/50 rounded-lg px-4 py-2.5 text-sm text-gray-200 focus:outline-none focus:border-brand-500/50">
                        <option value="">Select Source</option>
                        @foreach($sources as $source)
                        <option value="{{ $source->id }}">{{ $source->icon }} {{ $source->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-400 mb-1">Pipeline Stage</label>
                    <select wire:model="stage_id" class="w-full bg-surface-800/50 border border-gray-700/50 rounded-lg px-4 py-2.5 text-sm text-gray-200 focus:outline-none focus:border-brand-500/50">
                        <option value="">Select Stage</option>
                        @foreach($stages as $stage)
                        <option value="{{ $stage->id }}">{{ $stage->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-400 mb-1">Assigned To</label>
                    <select wire:model="assigned_to" class="w-full bg-surface-800/50 border border-gray-700/50 rounded-lg px-4 py-2.5 text-sm text-gray-200 focus:outline-none focus:border-brand-500/50">
                        <option value="">Unassigned</option>
                        @foreach($teamMembers as $member)
                        <option value="{{ $member->id }}">{{ $member->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-400 mb-1">Estimated Value (₹)</label>
                    <input wire:model="estimated_value" type="number" class="w-full bg-surface-800/50 border border-gray-700/50 rounded-lg px-4 py-2.5 text-sm text-gray-200 focus:outline-none focus:border-brand-500/50" placeholder="50000">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-400 mb-1">Follow-up Date</label>
                    <input wire:model="follow_up_date" type="date" class="w-full bg-surface-800/50 border border-gray-700/50 rounded-lg px-4 py-2.5 text-sm text-gray-200 focus:outline-none focus:border-brand-500/50">
                </div>
            </div>

            <!-- Tags -->
            <div class="mt-4">
                <label class="block text-xs font-medium text-gray-400 mb-2">Tags</label>
                <div class="flex flex-wrap gap-2">
                    @foreach($tags as $tag)
                    <label class="flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs cursor-pointer transition border {{ in_array($tag->id, $selectedTags) ? 'border-brand-500/50 bg-brand-500/10 text-brand-300' : 'border-gray-700/50 bg-surface-800/30 text-gray-400 hover:border-gray-600' }}">
                        <input type="checkbox" wire:model="selectedTags" value="{{ $tag->id }}" class="hidden">
                        <span class="w-2 h-2 rounded-full" style="background: {{ $tag->color }};"></span>
                        {{ $tag->name }}
                    </label>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Notes -->
        <div class="glass rounded-xl p-6">
            <label class="block text-xs font-medium text-gray-400 mb-1">Notes</label>
            <textarea wire:model="notes" rows="3" class="w-full bg-surface-800/50 border border-gray-700/50 rounded-lg px-4 py-2.5 text-sm text-gray-200 focus:outline-none focus:border-brand-500/50" placeholder="Any additional notes about this lead..."></textarea>
        </div>

        <!-- Submit -->
        <div class="flex items-center justify-end gap-3">
            <a href="{{ route('leads.index') }}" class="px-6 py-2.5 text-sm text-gray-400 hover:text-gray-200 transition">Cancel</a>
            <button type="submit" class="px-6 py-2.5 bg-brand-600 hover:bg-brand-700 text-white text-sm font-medium rounded-lg transition shadow-lg shadow-brand-600/20">
                {{ $isEdit ? 'Update Lead' : 'Create Lead' }}
            </button>
        </div>
    </form>
</div>
