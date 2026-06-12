<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CampaignMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $subject,
        public string $htmlContent,
        public string $trackingId,
        public string $fromName = '',
        public string $fromEmail = '',
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            from: new \Illuminate\Mail\Mailables\Address(
                $this->fromEmail ?: config('mail.from.address'),
                $this->fromName ?: config('mail.from.name')
            ),
            subject: $this->subject,
        );
    }

    public function content(): Content
    {
        return new Content(
            htmlString: $this->htmlContent . $this->trackingPixel(),
        );
    }

    protected function trackingPixel(): string
    {
        $url = url("/track/open/{$this->trackingId}");
        return "<img src=\"{$url}\" width=\"1\" height=\"1\" style=\"display:none\" alt=\"\" />";
    }
}
