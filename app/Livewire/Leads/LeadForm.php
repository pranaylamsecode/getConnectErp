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

#[Layout('components.layouts.app')]
#[Title('Lead Form')]
class LeadForm extends Component
{
    public ?Lead $lead = null;
    public bool $isEdit = false;

    // Form fields
    public string $school_name = '';
    public string $contact_person = '';
    public string $email = '';
    public string $phone = '';
    public string $secondary_phone = '';
    public string $website = '';
    public string $city = '';
    public string $state = '';
    public string $pincode = '';
    public string $address = '';
    public string $type = 'school';
    public ?int $source_id = null;
    public ?int $stage_id = null;
    public ?int $assigned_to = null;
    public string $notes = '';
    public string $estimated_value = '0';
    public string $follow_up_date = '';
    public ?int $student_count = null;
    public string $board = '';
    public string $medium = '';
    public array $selectedTags = [];

    protected $rules = [
        'school_name' => 'required|string|max:255',
        'contact_person' => 'nullable|string|max:255',
        'email' => 'nullable|email|max:255',
        'phone' => 'nullable|string|max:20',
        'secondary_phone' => 'nullable|string|max:20',
        'website' => 'nullable|url|max:255',
        'city' => 'nullable|string|max:100',
        'state' => 'nullable|string|max:100',
        'pincode' => 'nullable|string|max:10',
        'address' => 'nullable|string|max:500',
        'type' => 'required|in:school,college,coaching,university,other',
        'source_id' => 'nullable|exists:lead_sources,id',
        'stage_id' => 'nullable|exists:lead_stages,id',
        'assigned_to' => 'nullable|exists:users,id',
        'notes' => 'nullable|string',
        'estimated_value' => 'nullable|numeric|min:0',
        'follow_up_date' => 'nullable|date',
        'student_count' => 'nullable|integer|min:0',
        'board' => 'nullable|string|max:50',
        'medium' => 'nullable|string|max:50',
    ];

    public function mount(?int $leadId = null): void
    {
        if ($leadId) {
            $this->lead = Lead::with('tags')->findOrFail($leadId);
            $this->isEdit = true;
            $this->fill($this->lead->toArray());
            $this->selectedTags = $this->lead->tags->pluck('id')->toArray();
            $this->follow_up_date = $this->lead->follow_up_date ? $this->lead->follow_up_date->format('Y-m-d') : '';
            $this->estimated_value = (string) $this->lead->estimated_value;
        } else {
            // Set defaults
            $defaultStage = LeadStage::where('is_default', true)->first();
            $this->stage_id = $defaultStage?->id;
            $this->assigned_to = Auth::id();
        }
    }

    public function save(): void
    {
        $this->validate();

        $data = [
            'school_name' => $this->school_name,
            'contact_person' => $this->contact_person,
            'email' => $this->email,
            'phone' => $this->phone,
            'secondary_phone' => $this->secondary_phone,
            'website' => $this->website,
            'city' => $this->city,
            'state' => $this->state,
            'pincode' => $this->pincode,
            'address' => $this->address,
            'type' => $this->type,
            'source_id' => $this->source_id,
            'stage_id' => $this->stage_id,
            'assigned_to' => $this->assigned_to,
            'notes' => $this->notes,
            'estimated_value' => $this->estimated_value ?: 0,
            'follow_up_date' => $this->follow_up_date ?: null,
            'student_count' => $this->student_count,
            'board' => $this->board,
            'medium' => $this->medium,
        ];

        if ($this->isEdit) {
            $this->lead->update($data);
            $this->lead->tags()->sync($this->selectedTags);
            session()->flash('message', 'Lead updated successfully.');
        } else {
            $data['created_by'] = Auth::id();
            $data['team_id'] = Auth::user()->team_id;
            $data['status'] = 'new';

            $lead = Lead::create($data);
            $lead->tags()->sync($this->selectedTags);

            $lead->activities()->create([
                'user_id' => Auth::id(),
                'type' => 'note_added',
                'description' => 'Lead created',
            ]);

            session()->flash('message', 'Lead created successfully.');
        }

        $this->redirect(route('leads.index'));
    }

    public function render()
    {
        $sources = LeadSource::where('is_active', true)->get();
        $stages = LeadStage::ordered()->get();
        $tags = Tag::all();
        $teamMembers = User::where('team_id', Auth::user()->team_id)
            ->where('is_active', true)->get();

        $indianStates = [
            'Andhra Pradesh', 'Arunachal Pradesh', 'Assam', 'Bihar', 'Chhattisgarh',
            'Goa', 'Gujarat', 'Haryana', 'Himachal Pradesh', 'Jharkhand', 'Karnataka',
            'Kerala', 'Madhya Pradesh', 'Maharashtra', 'Manipur', 'Meghalaya', 'Mizoram',
            'Nagaland', 'Odisha', 'Punjab', 'Rajasthan', 'Sikkim', 'Tamil Nadu',
            'Telangana', 'Tripura', 'Uttar Pradesh', 'Uttarakhand', 'West Bengal',
            'Delhi', 'Chandigarh', 'Jammu & Kashmir', 'Ladakh',
        ];

        return view('livewire.leads.lead-form', compact('sources', 'stages', 'tags', 'teamMembers', 'indianStates'));
    }
}
