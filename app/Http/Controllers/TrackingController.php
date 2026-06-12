<?php

namespace App\Http\Controllers;

use App\Services\EmailCampaignService;
use Illuminate\Http\Response;

class TrackingController extends Controller
{
    public function open(string $trackingId, EmailCampaignService $service): Response
    {
        $service->recordOpen($trackingId);

        // Return a 1x1 transparent pixel
        $pixel = base64_decode('R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7');

        return response($pixel, 200, [
            'Content-Type' => 'image/gif',
            'Cache-Control' => 'no-store, no-cache, must-revalidate',
        ]);
    }

    public function click(string $trackingId, EmailCampaignService $service)
    {
        $service->recordClick($trackingId);

        // Redirect to your website/landing page
        return redirect(config('app.url', '/'));
    }
}
