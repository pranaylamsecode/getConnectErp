<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmailTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'subject', 'html_content', 'text_content',
        'variables', 'category', 'is_ai_generated', 'created_by',
    ];

    protected $casts = [
        'variables' => 'array',
        'is_ai_generated' => 'boolean',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function campaigns()
    {
        return $this->hasMany(Campaign::class, 'template_id');
    }

    /**
     * Replace merge tags in template content with lead data
     */
    public function renderForLead(Lead $lead): string
    {
        $content = $this->html_content;

        $replacements = [
            '{{school_name}}' => $lead->school_name ?? '',
            '{{contact_person}}' => $lead->contact_person ?? '',
            '{{email}}' => $lead->email ?? '',
            '{{city}}' => $lead->city ?? '',
            '{{state}}' => $lead->state ?? '',
            '{{type}}' => ucfirst($lead->type ?? ''),
            '{{student_count}}' => $lead->student_count ?? 'N/A',
            '{{board}}' => $lead->board ?? 'N/A',
        ];

        return str_replace(array_keys($replacements), array_values($replacements), $content);
    }
}
