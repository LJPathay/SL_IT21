<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhishingResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'campaign_id',
        'user_id',
        'clicked_link',
        'reported_phishing',
        'clicked_at',
        'reported_at',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'clicked_link' => 'boolean',
        'reported_phishing' => 'boolean',
        'clicked_at' => 'datetime',
        'reported_at' => 'datetime',
    ];

    // Relationships
    public function campaign()
    {
        return $this->belongsTo(PhishingCampaign::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
