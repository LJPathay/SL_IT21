<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SecurityDetection extends Model
{
    protected $fillable = [
        'detection_type',
        'severity',
        'title',
        'description',
        'details',
        'source',
        'source_id',
        'is_resolved',
        'resolved_at',
        'resolved_by',
        'mitigation_steps',
    ];

    protected $casts = [
        'is_resolved' => 'boolean',
        'resolved_at' => 'datetime',
    ];

    // Detection types
    const TYPE_PHISHING = 'phishing';
    const TYPE_SOCIAL_ENGINEERING = 'social_engineering';
    const TYPE_PASSWORD = 'password';
    const TYPE_MALWARE = 'malware';
    const TYPE_ONLINE_ACTIVITY = 'online_activity';

    // Severity levels
    const SEVERITY_LOW = 'low';
    const SEVERITY_MEDIUM = 'medium';
    const SEVERITY_HIGH = 'high';
    const SEVERITY_CRITICAL = 'critical';

    public function resolver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'resolved_by');
    }

    public function scopeUnresolved($query)
    {
        return $query->where('is_resolved', false);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('detection_type', $type);
    }

    public function scopeBySeverity($query, $severity)
    {
        return $query->where('severity', $severity);
    }
}
