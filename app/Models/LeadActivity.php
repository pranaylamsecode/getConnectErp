<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LeadActivity extends Model
{
    use HasFactory;

    protected $fillable = ['lead_id', 'user_id', 'type', 'description', 'metadata'];

    protected $casts = [
        'metadata' => 'array',
    ];

    public function lead(): BelongsTo
    {
        return $this->belongsTo(Lead::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getIconAttribute(): string
    {
        return match ($this->type) {
            'email_sent' => '✉️',
            'call_made', 'call_received' => '📞',
            'meeting' => '🤝',
            'note_added' => '📝',
            'status_change' => '🔄',
            'stage_change' => '📊',
            'score_update' => '⭐',
            'assigned' => '👤',
            'follow_up_set' => '📅',
            'ai_enriched' => '🤖',
            'tag_added' => '🏷️',
            'demo_scheduled' => '💻',
            'proposal_sent' => '📄',
            'won' => '🎉',
            'lost' => '😔',
            default => '📌',
        };
    }
}
