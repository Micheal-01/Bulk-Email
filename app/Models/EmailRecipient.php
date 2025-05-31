<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailRecipient extends Model
{
    use HasFactory;

    protected $fillable = [
        'email', 'name', 'metadata', 'is_active', 'subscribed_at'
    ];

    protected $casts = [
        'metadata' => 'array',
        'is_active' => 'boolean',
        'subscribed_at' => 'datetime',
    ];

    public function emailLogs()
    {
        return $this->hasMany(EmailLog::class, 'recipient_id');
    }
}
