<?php

namespace App\Livewire\Campaigns;

use App\Models\Campaign;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.app')]
#[Title('Campaigns')]
class CampaignIndex extends Component
{
    use WithPagination;

    public string $search = '';
    public string $statusFilter = '';

    public function deleteCampaign(int $id): void
    {
        Campaign::findOrFail($id)->delete();
        session()->flash('message', 'Campaign deleted successfully.');
    }

    public function render()
    {
        $user = Auth::user();

        $campaigns = Campaign::with('template')
            ->when($user->team_id, fn($q) => $q->where('team_id', $user->team_id))
            ->when($this->search, fn($q) => $q->where('name', 'like', "%{$this->search}%"))
            ->when($this->statusFilter, fn($q) => $q->where('status', $this->statusFilter))
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('livewire.campaigns.campaign-index', compact('campaigns'));
    }
}
