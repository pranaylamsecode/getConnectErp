<?php

namespace App\Livewire\Leads;

use App\Models\Lead;
use App\Models\LeadActivity;
use App\Models\LeadNote;
use App\Models\LeadStage;
use App\Jobs\AIEnrichLeadJob;
use App\Services\AIService;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.app')]
#[Title('Lead Detail')]
class LeadDetail extends Component
{
    public Lead $lead;
    public string $newNote = '';
    public string $newStatus = '';
    public ?int $newStageId = null;

    public function mount(int $leadId): void
    {
        $this->lead = Lead::with(['source', 'stage', 'assignedUser', 'tags', 'activities.user', 'notes.user', 'aiEnrichments'])
            ->findOrFail($leadId);
        $this->newStatus = $this->lead->status;
        $this->newStageId = $this->lead->stage_id;
    }

    public function addNote(): void
    {
        $this->validate(['newNote' => 'required|string|min:3']);

        LeadNote::create([
            'lead_id' => $this->lead->id,
            'user_id' => Auth::id(),
            'content' => $this->newNote,
        ]);

        LeadActivity::create([
            'lead_id' => $this->lead->id,
            'user_id' => Auth::id(),
            'type' => 'note_added',
            'description' => 'Added a note',
        ]);

        $this->newNote = '';
        $this->lead->refresh();
    }

    public function updateStatus(): void
    {
        $oldStatus = $this->lead->status;
        if ($oldStatus === $this->newStatus) return;

        $this->lead->update(['status' => $this->newStatus]);

        LeadActivity::create([
            'lead_id' => $this->lead->id,
            'user_id' => Auth::id(),
            'type' => 'status_change',
            'description' => "Status changed from {$oldStatus} to {$this->newStatus}",
        ]);

        $this->lead->refresh();
    }

    public function updateStage(): void
    {
        $oldStage = $this->lead->stage?->name ?? 'None';
        $this->lead->update(['stage_id' => $this->newStageId]);
        $newStage = LeadStage::find($this->newStageId)?->name ?? 'None';

        LeadActivity::create([
            'lead_id' => $this->lead->id,
            'user_id' => Auth::id(),
            'type' => 'stage_change',
            'description' => "Pipeline stage changed from {$oldStage} to {$newStage}",
        ]);

        $this->lead->refresh();
    }

    public function enrichWithAI(): void
    {
        AIEnrichLeadJob::dispatch($this->lead);
        session()->flash('message', 'AI enrichment started! Data will appear shortly.');
    }

    public function generateEmail(string $purpose = 'introduction'): void
    {
        $aiService = app(AIService::class);
        $emailData = $aiService->generateEmail($this->lead, $purpose);
        
        $this->dispatch('email-generated', data: $emailData);
    }

    public function scoreWithAI(): void
    {
        $aiService = app(AIService::class);
        $score = $aiService->scoreLead($this->lead);
        $this->lead->refresh();
        session()->flash('message', "Lead scored: {$score}/100");
    }

    public function render()
    {
        $stages = LeadStage::ordered()->get();

        $statusOptions = [
            'new' => 'New',
            'contacted' => 'Contacted',
            'qualified' => 'Qualified',
            'proposal_sent' => 'Proposal Sent',
            'negotiation' => 'Negotiation',
            'demo_scheduled' => 'Demo Scheduled',
            'won' => 'Won',
            'lost' => 'Lost',
            'on_hold' => 'On Hold',
        ];

        return view('livewire.leads.lead-detail', compact('stages', 'statusOptions'));
    }
}
