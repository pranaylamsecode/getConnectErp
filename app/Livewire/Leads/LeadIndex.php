<?php

namespace App\Livewire\Leads;

use App\Models\Lead;
use App\Models\LeadSource;
use App\Models\LeadStage;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.app')]
#[Title('Leads')]
class LeadIndex extends Component
{
    use WithPagination;

    public string $search = '';
    public string $statusFilter = '';
    public string $stageFilter = '';
    public string $typeFilter = '';
    public string $sourceFilter = '';
    public string $cityFilter = '';
    public string $assignedFilter = '';
    public string $sortField = 'created_at';
    public string $sortDirection = 'desc';
    public string $view = 'table'; // table or kanban

    protected $queryString = [
        'search' => ['except' => ''],
        'statusFilter' => ['except' => ''],
        'stageFilter' => ['except' => ''],
        'typeFilter' => ['except' => ''],
        'view' => ['except' => 'table'],
    ];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function sortBy(string $field): void
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function deleteLead(int $id): void
    {
        $lead = Lead::findOrFail($id);
        $lead->delete();
        session()->flash('message', 'Lead deleted successfully.');
    }

    public function updateStatus(int $id, string $status): void
    {
        $lead = Lead::findOrFail($id);
        $oldStatus = $lead->status;
        $lead->update(['status' => $status]);

        $lead->activities()->create([
            'user_id' => Auth::id(),
            'type' => 'status_change',
            'description' => "Status changed from {$oldStatus} to {$status}",
        ]);
    }

    public function render()
    {
        $user = Auth::user();

        $query = Lead::with(['source', 'stage', 'assignedUser', 'tags'])
            ->when($user->team_id, fn($q) => $q->where('team_id', $user->team_id))
            ->when($user->isSales(), fn($q) => $q->where('assigned_to', $user->id))
            ->when($this->search, fn($q) => $q->search($this->search))
            ->when($this->statusFilter, fn($q) => $q->where('status', $this->statusFilter))
            ->when($this->stageFilter, fn($q) => $q->where('stage_id', $this->stageFilter))
            ->when($this->typeFilter, fn($q) => $q->where('type', $this->typeFilter))
            ->when($this->sourceFilter, fn($q) => $q->where('source_id', $this->sourceFilter))
            ->when($this->cityFilter, fn($q) => $q->byCity($this->cityFilter))
            ->when($this->assignedFilter, fn($q) => $q->where('assigned_to', $this->assignedFilter))
            ->orderBy($this->sortField, $this->sortDirection);

        $leads = $query->paginate(20);
        $stages = LeadStage::ordered()->get();
        $sources = LeadSource::where('is_active', true)->get();
        $teamMembers = User::where('team_id', $user->team_id)->where('is_active', true)->get();
        $tags = Tag::all();

        return view('livewire.leads.lead-index', compact('leads', 'stages', 'sources', 'teamMembers', 'tags'));
    }
}
