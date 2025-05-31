<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailCampaign extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'subject', 'body', 'status',
        'scheduled_at', 'sent_at', 'total_recipients',
        'sent_count', 'failed_count'
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'sent_at' => 'datetime',
    ];

    public function emailLogs()
    {
        return $this->hasMany(EmailLog::class, 'campaign_id');
    }

    public function recipients()
    {
        return $this->belongsToMany(EmailRecipient::class, 'email_logs', 'campaign_id', 'recipient_id');
    }
}
