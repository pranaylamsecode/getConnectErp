<?php

namespace App\Jobs;

use App\Models\CampaignEmail;
use App\Models\LeadActivity;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use App\Mail\CampaignMail;
use Illuminate\Support\Facades\Log;

class SendCampaignEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $backoff = 60;

    public function __construct(
        public CampaignEmail $campaignEmail
    ) {}

    public function handle(): void
    {
        $email = $this->campaignEmail;
        $campaign = $email->campaign;
        $lead = $email->lead;

        if (!$lead->email) {
            $email->update([
                'status' => 'failed',
                'error_message' => 'Lead has no email address',
            ]);
            $campaign->increment('failed_count');
            return;
        }

        try {
            // Render template with lead data
            $template = $campaign->template;
            $htmlContent = $template ? $template->renderForLead($lead) : '';

            Mail::to($lead->email)->send(
                new CampaignMail(
                    subject: $campaign->subject,
                    htmlContent: $htmlContent,
                    trackingId: $email->tracking_id,
                    fromName: $campaign->from_name ?? config('mail.from.name'),
                    fromEmail: $campaign->from_email ?? config('mail.from.address'),
                )
            );

            $email->update([
                'status' => 'sent',
                'sent_at' => now(),
            ]);

            $campaign->increment('sent_count');

            // Log activity
            LeadActivity::create([
                'lead_id' => $lead->id,
                'type' => 'email_sent',
                'description' => "Campaign email sent: {$campaign->name}",
                'metadata' => [
                    'campaign_id' => $campaign->id,
                    'subject' => $campaign->subject,
                ],
            ]);

            // Check if campaign is complete
            $this->checkCampaignCompletion($campaign);

        } catch (\Exception $e) {
            Log::error("Campaign Email Error: Lead #{$lead->id}, Campaign #{$campaign->id}: " . $e->getMessage());

            if ($this->attempts() >= $this->tries) {
                $email->update([
                    'status' => 'failed',
                    'error_message' => $e->getMessage(),
                ]);
                $campaign->increment('failed_count');
            }

            throw $e;
        }
    }

    protected function checkCampaignCompletion($campaign): void
    {
        $campaign->refresh();
        $processed = $campaign->sent_count + $campaign->failed_count + $campaign->bounced_count;

        if ($processed >= $campaign->total_recipients) {
            $campaign->update([
                'status' => 'sent',
                'completed_at' => now(),
            ]);
        }
    }
}
