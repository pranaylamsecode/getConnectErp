<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AiEnrichment extends Model
{
    use HasFactory;

    protected $fillable = [
        'lead_id', 'provider', 'query', 'raw_response',
        'parsed_data', 'status', 'error_message', 'tokens_used',
    ];

    protected $casts = [
        'raw_response' => 'array',
        'parsed_data' => 'array',
        'tokens_used' => 'integer',
    ];

    public function lead(): BelongsTo
    {
        return $this->belongsTo(Lead::class);
    }
}
