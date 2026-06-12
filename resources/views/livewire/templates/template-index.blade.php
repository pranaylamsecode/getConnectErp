<div>
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-xl font-bold text-white">Email Templates</h1>
            <p class="text-sm text-gray-400">Create and manage reusable email templates</p>
        </div>
        <button onclick="document.getElementById('create-modal').classList.remove('hidden')" class="inline-flex items-center gap-2 px-4 py-2 bg-brand-600 hover:bg-brand-700 text-white text-sm font-medium rounded-lg transition">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            New Template
        </button>
    </div>

    <!-- Template Create Form -->
    <div class="glass rounded-xl p-6 mb-6">
        <form wire:submit="save" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-gray-400 mb-1">Template Name *</label>
                    <input wire:model="name" type="text" class="w-full bg-surface-800/50 border border-gray-700/50 rounded-lg px-4 py-2.5 text-sm text-gray-200 focus:outline-none focus:border-brand-500/50" placeholder="e.g. Introduction Email">
                    @error('name') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-400 mb-1">Subject *</label>
                    <input wire:model="subject" type="text" class="w-full bg-surface-800/50 border border-gray-700/50 rounded-lg px-4 py-2.5 text-sm text-gray-200 focus:outline-none focus:border-brand-500/50" placeholder="e.g. Transform {{school_name}} with Smart ERP">
                    @error('subject') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-400 mb-1">HTML Content *</label>
                <textarea wire:model="html_content" rows="10" class="w-full bg-surface-800/50 border border-gray-700/50 rounded-lg px-4 py-2.5 text-sm text-gray-200 font-mono focus:outline-none focus:border-brand-500/50" placeholder="<h1>Hello {{contact_person}}</h1>..."></textarea>
                @error('html_content') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
                <p class="text-xs text-gray-500 mt-1">Available merge tags: <code class="text-brand-400">{{school_name}}</code> <code class="text-brand-400">{{contact_person}}</code> <code class="text-brand-400">{{email}}</code> <code class="text-brand-400">{{city}}</code> <code class="text-brand-400">{{state}}</code> <code class="text-brand-400">{{type}}</code> <code class="text-brand-400">{{student_count}}</code> <code class="text-brand-400">{{board}}</code></p>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-400 mb-1">Category</label>
                <select wire:model="category" class="bg-surface-800/50 border border-gray-700/50 rounded-lg px-4 py-2.5 text-sm text-gray-200 focus:outline-none focus:border-brand-500/50">
                    <option value="introduction">Introduction</option>
                    <option value="follow_up">Follow Up</option>
                    <option value="demo_invite">Demo Invite</option>
                    <option value="proposal">Proposal</option>
                    <option value="general">General</option>
                </select>
            </div>
            <div class="flex justify-end gap-2">
                <button type="submit" class="px-6 py-2.5 bg-brand-600 hover:bg-brand-700 text-white text-sm font-medium rounded-lg transition">Save Template</button>
            </div>
        </form>
    </div>

    <!-- Templates List -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @forelse($templates as $template)
        <div class="glass rounded-xl p-5 stat-card">
            <div class="flex items-start justify-between mb-2">
                <h3 class="text-sm font-semibold text-white">{{ $template->name }}</h3>
                @if($template->is_ai_generated)
                <span class="text-[10px] px-2 py-0.5 rounded-full bg-purple-500/20 text-purple-300">AI Generated</span>
                @endif
            </div>
            <p class="text-xs text-gray-400 mb-3">{{ $template->subject }}</p>
            <p class="text-xs text-gray-500 capitalize">{{ str_replace('_', ' ', $template->category) }} · {{ $template->created_at->diffForHumans() }}</p>
            <div class="flex gap-2 mt-3">
                <button wire:click="editTemplate({{ $template->id }})" class="text-xs text-brand-400 hover:underline">Edit</button>
                <button wire:click="deleteTemplate({{ $template->id }})" wire:confirm="Delete this template?" class="text-xs text-red-400 hover:underline">Delete</button>
            </div>
        </div>
        @empty
        <div class="col-span-full text-center py-16">
            <p class="text-gray-500 text-sm">No templates yet. Create your first email template above!</p>
        </div>
        @endforelse
    </div>
</div>
