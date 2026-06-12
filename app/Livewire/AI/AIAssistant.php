<?php

namespace App\Livewire\AI;

use App\Models\Lead;
use App\Models\LeadSource;
use App\Models\LeadStage;
use App\Services\AIService;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.app')]
#[Title('AI Assistant')]
class AIAssistant extends Component
{
    public string $city = '';
    public string $state = '';
    public string $type = 'school';
    public array $discoveredSchools = [];
    public bool $isLoading = false;
    public string $error = '';
    public array $selectedSchools = [];

    public function discover(): void
    {
        $this->validate([
            'city' => 'required|string|min:2',
            'type' => 'required|in:school,college,coaching,university',
        ]);

        $this->isLoading = true;
        $this->error = '';
        $this->discoveredSchools = [];

        try {
            $aiService = app(AIService::class);
            $results = $aiService->discoverSchools($this->city, $this->state, $this->type);
            $this->discoveredSchools = $results;
        } catch (\Exception $e) {
            $this->error = 'Failed to discover schools. ' . $e->getMessage();
        }

        $this->isLoading = false;
    }

    public function toggleSelect(int $index): void
    {
        if (in_array($index, $this->selectedSchools)) {
            $this->selectedSchools = array_diff($this->selectedSchools, [$index]);
        } else {
            $this->selectedSchools[] = $index;
        }
    }

    public function selectAll(): void
    {
        $this->selectedSchools = array_keys($this->discoveredSchools);
    }

    public function importSelected(): void
    {
        $user = Auth::user();
        $aiSource = LeadSource::where('name', 'AI Discovered')->first();
        $defaultStage = LeadStage::where('is_default', true)->first();
        $count = 0;

        foreach ($this->selectedSchools as $index) {
            if (!isset($this->discoveredSchools[$index])) continue;

            $school = $this->discoveredSchools[$index];

            // Skip if already exists
            $exists = Lead::where('school_name', $school['name'] ?? '')
                ->where('city', $this->city)
                ->exists();

            if ($exists) continue;

            Lead::create([
                'school_name' => $school['name'] ?? 'Unknown',
                'contact_person' => $school['contact_person'] ?? null,
                'email' => ($school['email'] ?? 'N/A') !== 'N/A' ? $school['email'] : null,
                'phone' => ($school['phone'] ?? 'N/A') !== 'N/A' ? $school['phone'] : null,
                'website' => ($school['website'] ?? 'N/A') !== 'N/A' ? $school['website'] : null,
                'address' => $school['address'] ?? null,
                'city' => $this->city,
                'state' => $this->state,
                'type' => $this->type,
                'board' => $school['board'] ?? null,
                'medium' => $school['medium'] ?? null,
                'student_count' => is_numeric($school['student_count'] ?? null) ? (int)$school['student_count'] : null,
                'source_id' => $aiSource?->id,
                'stage_id' => $defaultStage?->id,
                'assigned_to' => $user->id,
                'created_by' => $user->id,
                'team_id' => $user->team_id,
                'status' => 'new',
            ]);

            $count++;
        }

        session()->flash('message', "{$count} schools imported as leads successfully!");
        $this->selectedSchools = [];
    }

    public function render()
    {
        return view('livewire.ai.ai-assistant');
    }
}
