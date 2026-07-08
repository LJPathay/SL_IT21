<?php

namespace App\Models;

use App\Services\EncryptionService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\App;

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
        'source_encrypted',
        'source_id_encrypted',
        'details_encrypted',
    ];

    protected $hidden = [
        'source_encrypted',
        'source_id_encrypted',
        'details_encrypted',
    ];

    protected $casts = [
        'is_resolved' => 'boolean',
        'resolved_at' => 'datetime',
    ];

    protected static function booted()
    {
        static::creating(function ($detection) {
            $encryptionService = App::make(EncryptionService::class);
            $detection->source_encrypted = $encryptionService->encryptNullable($detection->source);
            $detection->source_id_encrypted = $encryptionService->encryptNullable($detection->source_id);
            $detection->details_encrypted = $encryptionService->encryptNullable($detection->details);
        });

        static::updating(function ($detection) {
            $encryptionService = App::make(EncryptionService::class);
            if ($detection->isDirty('source')) {
                $detection->source_encrypted = $encryptionService->encryptNullable($detection->source);
            }
            if ($detection->isDirty('source_id')) {
                $detection->source_id_encrypted = $encryptionService->encryptNullable($detection->source_id);
            }
            if ($detection->isDirty('details')) {
                $detection->details_encrypted = $encryptionService->encryptNullable($detection->details);
            }
        });
    }

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
