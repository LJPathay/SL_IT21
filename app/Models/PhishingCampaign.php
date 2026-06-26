<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhishingCampaign extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'template_type',
        'target_audience',
        'status',
        'sent_at',
        'completed_at',
        'total_sent',
        'total_clicked',
        'total_reported',
        'description',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    // Relationships
    public function results()
    {
        return $this->hasMany(PhishingResult::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }
}
