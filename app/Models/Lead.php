<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lead extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'school_name', 'contact_person', 'email', 'phone', 'secondary_phone',
        'website', 'city', 'state', 'pincode', 'address', 'type',
        'source_id', 'stage_id', 'assigned_to', 'created_by', 'team_id',
        'lead_score', 'estimated_value', 'notes', 'ai_data', 'custom_fields',
        'status', 'follow_up_date', 'lost_reason',
        'student_count', 'board', 'medium',
    ];

    protected $casts = [
        'ai_data' => 'array',
        'custom_fields' => 'array',
        'follow_up_date' => 'date',
        'estimated_value' => 'decimal:2',
        'lead_score' => 'integer',
        'student_count' => 'integer',
    ];

    // Relationships
    public function source(): BelongsTo
    {
        return $this->belongsTo(LeadSource::class, 'source_id');
    }

    public function stage(): BelongsTo
    {
        return $this->belongsTo(LeadStage::class, 'stage_id');
    }

    public function assignedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function activities(): HasMany
    {
        return $this->hasMany(LeadActivity::class)->orderByDesc('created_at');
    }

    public function notes(): HasMany
    {
        return $this->hasMany(LeadNote::class)->orderByDesc('created_at');
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'lead_tags');
    }

    public function campaignEmails(): HasMany
    {
        return $this->hasMany(CampaignEmail::class);
    }

    public function aiEnrichments(): HasMany
    {
        return $this->hasMany(AiEnrichment::class);
    }

    // Scopes
    public function scopeByStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByStage($query, int $stageId)
    {
        return $query->where('stage_id', $stageId);
    }

    public function scopeByCity($query, string $city)
    {
        return $query->where('city', 'like', "%{$city}%");
    }

    public function scopeByState($query, string $state)
    {
        return $query->where('state', $state);
    }

    public function scopeAssignedTo($query, int $userId)
    {
        return $query->where('assigned_to', $userId);
    }

    public function scopeForTeam($query, int $teamId)
    {
        return $query->where('team_id', $teamId);
    }

    public function scopeHotLeads($query)
    {
        return $query->where('lead_score', '>=', 70);
    }

    public function scopeFollowUpToday($query)
    {
        return $query->whereDate('follow_up_date', today());
    }

    public function scopeFollowUpOverdue($query)
    {
        return $query->whereDate('follow_up_date', '<', today())
                     ->whereNotIn('status', ['won', 'lost']);
    }

    public function scopeSearch($query, string $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('school_name', 'like', "%{$search}%")
              ->orWhere('contact_person', 'like', "%{$search}%")
              ->orWhere('email', 'like', "%{$search}%")
              ->orWhere('phone', 'like', "%{$search}%")
              ->orWhere('city', 'like', "%{$search}%");
        });
    }

    // Accessors
    public function getScoreLabelAttribute(): string
    {
        if ($this->lead_score >= 80) return 'Hot';
        if ($this->lead_score >= 50) return 'Warm';
        if ($this->lead_score >= 20) return 'Cool';
        return 'Cold';
    }

    public function getScoreColorAttribute(): string
    {
        if ($this->lead_score >= 80) return '#ef4444';
        if ($this->lead_score >= 50) return '#f59e0b';
        if ($this->lead_score >= 20) return '#3b82f6';
        return '#6b7280';
    }
}
