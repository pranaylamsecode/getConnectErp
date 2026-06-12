<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LeadStage extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'order', 'color', 'icon', 'is_default', 'is_won', 'is_lost'];

    protected $casts = [
        'is_default' => 'boolean',
        'is_won' => 'boolean',
        'is_lost' => 'boolean',
        'order' => 'integer',
    ];

    public function leads(): HasMany
    {
        return $this->hasMany(Lead::class, 'stage_id');
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }
}
