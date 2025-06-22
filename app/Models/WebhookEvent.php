<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebhookEvent extends Model
{
    use HasFactory;
    protected $table = 'webhook_events';

    protected $fillable = [
        'session_id',
        'event_type',
        'http_method',
        'payload',
        'headers',
        'source_ip',
        'user_agent',
        'received_at',
    ];

    protected $casts = [
        'payload' => 'array',
        'headers' => 'array',
        'received_at' => 'datetime',
    ];

    public function scopeForSession($query, $sessionId)
    {
        return $query->where('session_id', $sessionId);
    }
}
