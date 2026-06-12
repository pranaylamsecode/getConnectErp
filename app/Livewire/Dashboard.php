<?php

namespace App\Livewire;

use App\Models\Campaign;
use App\Models\Lead;
use App\Models\LeadActivity;
use App\Models\LeadStage;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.app')]
#[Title('Dashboard')]
class Dashboard extends Component
{
    public function render()
    {
        $user = Auth::user();
        $teamId = $user->team_id;

        // Build base query respecting team & role
        $leadsQuery = Lead::query();
        if ($teamId) {
            $leadsQuery->where('team_id', $teamId);
        }
        if ($user->isSales()) {
            $leadsQuery->where('assigned_to', $user->id);
        }

        $totalLeads = (clone $leadsQuery)->count();
        $newLeadsThisWeek = (clone $leadsQuery)->where('created_at', '>=', now()->startOfWeek())->count();
        $newLeadsThisMonth = (clone $leadsQuery)->where('created_at', '>=', now()->startOfMonth())->count();
        $hotLeads = (clone $leadsQuery)->where('lead_score', '>=', 70)->count();
        $followUpToday = (clone $leadsQuery)->whereDate('follow_up_date', today())->count();
        $overdueFollowUps = (clone $leadsQuery)->whereDate('follow_up_date', '<', today())
            ->whereNotIn('status', ['won', 'lost'])->count();

        // Status breakdown
        $statusCounts = (clone $leadsQuery)->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')->pluck('count', 'status')->toArray();

        // Pipeline data
        $stages = LeadStage::ordered()->withCount(['leads' => function ($q) use ($teamId, $user) {
            if ($teamId) $q->where('team_id', $teamId);
            if ($user->isSales()) $q->where('assigned_to', $user->id);
        }])->get();

        // Recent activities
        $recentActivities = LeadActivity::with(['lead', 'user'])
            ->whereHas('lead', function ($q) use ($teamId, $user) {
                if ($teamId) $q->where('team_id', $teamId);
                if ($user->isSales()) $q->where('assigned_to', $user->id);
            })
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        // Campaign stats
        $campaignStats = [
            'total' => Campaign::when($teamId, fn($q) => $q->where('team_id', $teamId))->count(),
            'active' => Campaign::when($teamId, fn($q) => $q->where('team_id', $teamId))->whereIn('status', ['sending', 'scheduled'])->count(),
            'total_sent' => Campaign::when($teamId, fn($q) => $q->where('team_id', $teamId))->sum('sent_count'),
            'total_opened' => Campaign::when($teamId, fn($q) => $q->where('team_id', $teamId))->sum('opened_count'),
        ];

        // Won deals value
        $wonValue = (clone $leadsQuery)->where('status', 'won')->sum('estimated_value');

        return view('livewire.dashboard', compact(
            'totalLeads', 'newLeadsThisWeek', 'newLeadsThisMonth',
            'hotLeads', 'followUpToday', 'overdueFollowUps',
            'statusCounts', 'stages', 'recentActivities',
            'campaignStats', 'wonValue'
        ));
    }
}
