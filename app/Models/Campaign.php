<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Campaign extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'subject', 'template_id', 'created_by', 'team_id',
        'status', 'segment_filters', 'from_name', 'from_email', 'reply_to',
        'total_recipients', 'sent_count', 'opened_count', 'clicked_count',
        'bounced_count', 'failed_count', 'scheduled_at', 'started_at', 'completed_at',
    ];

    protected $casts = [
        'segment_filters' => 'array',
        'scheduled_at' => 'datetime',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function template(): BelongsTo
    {
        return $this->belongsTo(EmailTemplate::class, 'template_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function emails(): HasMany
    {
        return $this->hasMany(CampaignEmail::class);
    }

    // Computed attributes
    public function getOpenRateAttribute(): float
    {
        if ($this->sent_count === 0) return 0;
        return round(($this->opened_count / $this->sent_count) * 100, 1);
    }

    public function getClickRateAttribute(): float
    {
        if ($this->sent_count === 0) return 0;
        return round(($this->clicked_count / $this->sent_count) * 100, 1);
    }

    public function getBounceRateAttribute(): float
    {
        if ($this->total_recipients === 0) return 0;
        return round(($this->bounced_count / $this->total_recipients) * 100, 1);
    }

    public function getProgressPercentAttribute(): float
    {
        if ($this->total_recipients === 0) return 0;
        return round(($this->sent_count / $this->total_recipients) * 100, 1);
    }
}
