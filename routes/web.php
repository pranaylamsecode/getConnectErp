<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TrackingController;
use App\Livewire\AI\AIAssistant;
use App\Livewire\Campaigns\CampaignBuilder;
use App\Livewire\Campaigns\CampaignIndex;
use App\Livewire\Dashboard;
use App\Livewire\Leads\LeadDetail;
use App\Livewire\Leads\LeadForm;
use App\Livewire\Leads\LeadIndex;
use App\Livewire\Teams\TeamManager;
use App\Livewire\Templates\TemplateIndex;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
});

// Email tracking (no auth required)
Route::get('/track/open/{trackingId}', [TrackingController::class, 'open'])->name('tracking.open');
Route::get('/track/click/{trackingId}', [TrackingController::class, 'click'])->name('tracking.click');

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard
    Route::get('/dashboard', Dashboard::class)->name('dashboard');

    // Leads
    Route::get('/leads', LeadIndex::class)->name('leads.index');
    Route::get('/leads/create', LeadForm::class)->name('leads.create');
    Route::get('/leads/{leadId}', LeadDetail::class)->name('leads.show');
    Route::get('/leads/{leadId}/edit', LeadForm::class)->name('leads.edit');

    // Campaigns
    Route::get('/campaigns', CampaignIndex::class)->name('campaigns.index');
    Route::get('/campaigns/create', CampaignBuilder::class)->name('campaigns.create');

    // Email Templates
    Route::get('/templates', TemplateIndex::class)->name('templates.index');

    // AI Assistant
    Route::get('/ai/assistant', AIAssistant::class)->name('ai.assistant');

    // Team Management (admin/manager only)
    Route::middleware(['role:admin,manager'])->group(function () {
        Route::get('/team', TeamManager::class)->name('teams.index');
    });

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| Auth Routes (Breeze)
|--------------------------------------------------------------------------
*/

require __DIR__.'/auth.php';
