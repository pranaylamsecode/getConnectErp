<?php

namespace App\Livewire\Campaigns;

use App\Models\Campaign;
use App\Models\EmailTemplate;
use App\Models\LeadStage;
use App\Models\Tag;
use App\Services\EmailCampaignService;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.app')]
#[Title('Create Campaign')]
class CampaignBuilder extends Component
{
    public int $step = 1;

    // Step 1: Campaign Info
    public string $name = '';
    public string $subject = '';
    public ?int $template_id = null;
    public string $from_name = '';
    public string $from_email = '';

    // Step 2: Segment Filters
    public array $statusFilters = [];
    public array $typeFilters = [];
    public string $cityFilter = '';
    public string $stateFilter = '';
    public ?int $stageFilter = null;
    public int $minScore = 0;
    public array $tagFilters = [];

    // Step 3: Preview
    public int $recipientCount = 0;

    protected $rules = [
        'name' => 'required|string|max:255',
        'subject' => 'required|string|max:255',
        'template_id' => 'required|exists:email_templates,id',
    ];

    public function nextStep(): void
    {
        if ($this->step === 1) {
            $this->validate();
        }

        if ($this->step === 2) {
            $this->calculateRecipients();
        }

        $this->step = min(3, $this->step + 1);
    }

    public function previousStep(): void
    {
        $this->step = max(1, $this->step - 1);
    }

    public function calculateRecipients(): void
    {
        $service = app(EmailCampaignService::class);
        $filters = $this->buildFilters();
        $this->recipientCount = $service->getSegmentedLeads($filters)->count();
    }

    protected function buildFilters(): array
    {
        return array_filter([
            'status' => $this->statusFilters,
            'type' => $this->typeFilters,
            'city' => $this->cityFilter,
            'state' => $this->stateFilter,
            'stage_id' => $this->stageFilter,
            'min_score' => $this->minScore > 0 ? $this->minScore : null,
            'tags' => $this->tagFilters,
            'team_id' => Auth::user()->team_id,
        ]);
    }

    public function saveDraft(): void
    {
        $this->validate();
        $this->createCampaign('draft');
        session()->flash('message', 'Campaign saved as draft.');
        $this->redirect(route('campaigns.index'));
    }

    public function scheduleNow(): void
    {
        $this->validate();
        $campaign = $this->createCampaign('scheduled');

        $service = app(EmailCampaignService::class);
        $count = $service->prepareCampaign($campaign);
        $service->sendCampaign($campaign);

        session()->flash('message', "Campaign scheduled! Sending to {$count} recipients.");
        $this->redirect(route('campaigns.index'));
    }

    protected function createCampaign(string $status): Campaign
    {
        return Campaign::create([
            'name' => $this->name,
            'subject' => $this->subject,
            'template_id' => $this->template_id,
            'created_by' => Auth::id(),
            'team_id' => Auth::user()->team_id,
            'status' => $status,
            'segment_filters' => $this->buildFilters(),
            'from_name' => $this->from_name ?: config('mail.from.name'),
            'from_email' => $this->from_email ?: config('mail.from.address'),
        ]);
    }

    public function render()
    {
        $templates = EmailTemplate::orderByDesc('created_at')->get();
        $stages = LeadStage::ordered()->get();
        $tags = Tag::all();

        return view('livewire.campaigns.campaign-builder', compact('templates', 'stages', 'tags'));
    }
}
