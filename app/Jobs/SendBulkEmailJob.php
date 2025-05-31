<?php

namespace App\Jobs;

use App\Mail\BulkEmail;
use App\Models\EmailCampaign;
use App\Models\EmailLog;
use App\Models\EmailRecipient;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendBulkEmailJob implements ShouldQueue
{
  use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $campaign;
    public $recipient;

    public function __construct(EmailCampaign $campaign, EmailRecipient $recipient)
    {
        $this->campaign = $campaign;
        $this->recipient = $recipient;
    }

    public function handle()
    {
        try {
            // Create email log entry
            $emailLog = EmailLog::create([
                'campaign_id' => $this->campaign->id,
                'recipient_id' => $this->recipient->id,
                'status' => 'pending'
            ]);

            // Send email
            Mail::to($this->recipient->email)->send(new BulkEmail($this->campaign, $this->recipient));

            // Update log as sent
            $emailLog->update([
                'status' => 'sent',
                'sent_at' => now()
            ]);

            // Update campaign sent count
            $this->campaign->increment('sent_count');

        } catch (Exception $e) {
            // Update log as failed
            $emailLog->update([
                'status' => 'failed',
                'error_message' => $e->getMessage()
            ]);

            // Update campaign failed count
            $this->campaign->increment('failed_count');

            throw $e; // Re-throw to mark job as failed
        }
    }

    public function failed(Exception $exception)
    {
        // Handle job failure
        Log::error('Bulk email job failed: ' . $exception->getMessage());
    }
}
