<?php

namespace App\Services;

use App\Models\Campaign;
use App\Models\CampaignEmail;
use App\Models\Lead;
use App\Jobs\SendCampaignEmailJob;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class EmailCampaignService
{
    /**
     * Build the recipient list based on segment filters
     */
    public function getSegmentedLeads(array $filters): \Illuminate\Database\Eloquent\Builder
    {
        $query = Lead::query()->whereNotNull('email')->where('email', '!=', '');

        if (!empty($filters['status'])) {
            $query->whereIn('status', (array) $filters['status']);
        }

        if (!empty($filters['type'])) {
            $query->whereIn('type', (array) $filters['type']);
        }

        if (!empty($filters['city'])) {
            $query->where('city', 'like', "%{$filters['city']}%");
        }

        if (!empty($filters['state'])) {
            $query->where('state', $filters['state']);
        }

        if (!empty($filters['stage_id'])) {
            $query->where('stage_id', $filters['stage_id']);
        }

        if (!empty($filters['min_score'])) {
            $query->where('lead_score', '>=', $filters['min_score']);
        }

        if (!empty($filters['max_score'])) {
            $query->where('lead_score', '<=', $filters['max_score']);
        }

        if (!empty($filters['tags'])) {
            $query->whereHas('tags', function ($q) use ($filters) {
                $q->whereIn('tags.id', (array) $filters['tags']);
            });
        }

        if (!empty($filters['assigned_to'])) {
            $query->where('assigned_to', $filters['assigned_to']);
        }

        if (!empty($filters['team_id'])) {
            $query->where('team_id', $filters['team_id']);
        }

        if (!empty($filters['board'])) {
            $query->where('board', $filters['board']);
        }

        return $query;
    }

    /**
     * Prepare and queue a campaign for sending
     */
    public function prepareCampaign(Campaign $campaign): int
    {
        $filters = $campaign->segment_filters ?? [];
        $leads = $this->getSegmentedLeads($filters)->get();

        $count = 0;
        foreach ($leads as $lead) {
            // Avoid sending duplicate emails in same campaign
            $exists = CampaignEmail::where('campaign_id', $campaign->id)
                ->where('lead_id', $lead->id)
                ->exists();

            if (!$exists) {
                CampaignEmail::create([
                    'campaign_id' => $campaign->id,
                    'lead_id' => $lead->id,
                    'tracking_id' => Str::uuid()->toString(),
                    'status' => 'pending',
                ]);
                $count++;
            }
        }

        $campaign->update([
            'total_recipients' => $count,
            'status' => 'scheduled',
        ]);

        return $count;
    }

    /**
     * Dispatch campaign emails to the queue
     */
    public function sendCampaign(Campaign $campaign): void
    {
        $campaign->update([
            'status' => 'sending',
            'started_at' => now(),
        ]);

        $pendingEmails = $campaign->emails()->where('status', 'pending')->get();

        foreach ($pendingEmails as $email) {
            SendCampaignEmailJob::dispatch($email)->onQueue('emails');
        }

        Log::info("Campaign #{$campaign->id}: Dispatched {$pendingEmails->count()} emails to queue");
    }

    /**
     * Record an email open event
     */
    public function recordOpen(string $trackingId): void
    {
        $email = CampaignEmail::where('tracking_id', $trackingId)->first();
        if ($email && !$email->opened_at) {
            $email->update([
                'status' => 'opened',
                'opened_at' => now(),
            ]);

            $email->campaign->increment('opened_count');
        }
    }

    /**
     * Record an email click event
     */
    public function recordClick(string $trackingId): void
    {
        $email = CampaignEmail::where('tracking_id', $trackingId)->first();
        if ($email && !$email->clicked_at) {
            $email->update([
                'status' => 'clicked',
                'clicked_at' => now(),
            ]);

            $email->campaign->increment('clicked_count');

            // Also record as opened if not already
            if (!$email->opened_at) {
                $email->update(['opened_at' => now()]);
                $email->campaign->increment('opened_count');
            }
        }
    }
}
