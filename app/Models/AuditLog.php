<?php

namespace App\Models;

use App\Services\EncryptionService;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\App;

#[Fillable(['user_id', 'action', 'model', 'model_id', 'changes', 'ip_address', 'user_agent', 'status', 'ip_address_encrypted', 'user_agent_encrypted', 'changes_encrypted'])]
class AuditLog extends Model
{
    use HasFactory;

    protected $table = 'audit_logs';

    protected $hidden = [
        'ip_address_encrypted',
        'user_agent_encrypted',
        'changes_encrypted',
    ];

    protected $casts = [
        'changes' => 'json',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected static function booted()
    {
        static::creating(function ($log) {
            $encryptionService = App::make(EncryptionService::class);
            $log->ip_address_encrypted = $encryptionService->encryptNullable($log->ip_address);
            $log->user_agent_encrypted = $encryptionService->encryptNullable($log->user_agent);
            if (is_array($log->changes)) {
                $log->changes_encrypted = $encryptionService->encrypt(json_encode($log->changes));
            } else {
                $log->changes_encrypted = $encryptionService->encryptNullable($log->changes);
            }
        });

        static::updating(function ($log) {
            $encryptionService = App::make(EncryptionService::class);
            if ($log->isDirty('ip_address')) {
                $log->ip_address_encrypted = $encryptionService->encryptNullable($log->ip_address);
            }
            if ($log->isDirty('user_agent')) {
                $log->user_agent_encrypted = $encryptionService->encryptNullable($log->user_agent);
            }
            if ($log->isDirty('changes')) {
                if (is_array($log->changes)) {
                    $log->changes_encrypted = $encryptionService->encrypt(json_encode($log->changes));
                } else {
                    $log->changes_encrypted = $encryptionService->encryptNullable($log->changes);
                }
            }
        });
    }

    /**
     * Get the user who performed the action.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope to filter by user.
     */
    public function scopeByUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope to filter by action.
     */
    public function scopeByAction($query, string $action)
    {
        return $query->where('action', $action);
    }

    /**
     * Scope to filter by model.
     */
    public function scopeByModel($query, string $model)
    {
        return $query->where('model', $model);
    }

    /**
     * Scope to filter by date range.
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }
}
