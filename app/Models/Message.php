<?php

namespace App\Models;

use App\Services\EncryptionService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\App;

class Message extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'sender_id',
        'recipient_id',
        'subject',
        'body',
        'is_read',
        'read_at',
        'subject_encrypted',
        'body_encrypted',
    ];

    protected $hidden = [
        'subject_encrypted',
        'body_encrypted',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'read_at' => 'datetime',
    ];

    protected static function booted()
    {
        static::creating(function ($message) {
            $encryptionService = App::make(EncryptionService::class);
            $message->subject_encrypted = $encryptionService->encryptNullable($message->subject);
            $message->body_encrypted = $encryptionService->encryptNullable($message->body);
        });

        static::updating(function ($message) {
            $encryptionService = App::make(EncryptionService::class);
            if ($message->isDirty('subject')) {
                $message->subject_encrypted = $encryptionService->encryptNullable($message->subject);
            }
            if ($message->isDirty('body')) {
                $message->body_encrypted = $encryptionService->encryptNullable($message->body);
            }
        });
    }

    // Relationships
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function recipient()
    {
        return $this->belongsTo(User::class, 'recipient_id');
    }

    // Scopes
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    public function scopeRead($query)
    {
        return $query->where('is_read', true);
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('recipient_id', $userId);
    }

    public function scopeSentBy($query, $userId)
    {
        return $query->where('sender_id', $userId);
    }

    // Methods
    public function markAsRead()
    {
        if (!$this->is_read) {
            $this->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
        }
    }
}
