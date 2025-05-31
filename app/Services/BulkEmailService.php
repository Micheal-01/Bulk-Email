<?php

namespace App\Services;

use App\Jobs\SendBulkEmailJob;
use App\Models\EmailCampaign;
use App\Models\EmailRecipient;
use Illuminate\Support\Facades\DB;

class BulkEmailService
{
    public function createCampaign(array $data)
    {
        return EmailCampaign::create([
            'name' => $data['name'],
            'subject' => $data['subject'],
            'body' => $data['body'],
            'status' => 'draft'
        ]);
    }

    public function sendCampaign(EmailCampaign $campaign, array $recipientIds = [])
    {
        DB::transaction(function () use ($campaign, $recipientIds) {
            // Get recipients
            $recipients = empty($recipientIds)
                ? EmailRecipient::where('is_active', true)->get()
                : EmailRecipient::whereIn('id', $recipientIds)->where('is_active', true)->get();

            // Update campaign
            $campaign->update([
                'status' => 'sending',
                'total_recipients' => $recipients->count(),
                'sent_count' => 0,
                'failed_count' => 0
            ]);

            // Queue emails
            foreach ($recipients as $recipient) {
                SendBulkEmailJob::dispatch($campaign, $recipient);
            }
        });

        return $campaign;
    }

    public function scheduleCampaign(EmailCampaign $campaign, $scheduledAt, array $recipientIds = [])
    {
        $campaign->update([
            'status' => 'scheduled',
            'scheduled_at' => $scheduledAt
        ]);

        // You can implement a scheduled job dispatcher here
        // For now, we'll just update the status
        return $campaign;
    }

    public function getCampaignStats(EmailCampaign $campaign)
    {
        return [
            'total_recipients' => $campaign->total_recipients,
            'sent_count' => $campaign->sent_count,
            'failed_count' => $campaign->failed_count,
            'pending_count' => $campaign->total_recipients - $campaign->sent_count - $campaign->failed_count,
            'success_rate' => $campaign->total_recipients > 0
                ? round(($campaign->sent_count / $campaign->total_recipients) * 100, 2)
                : 0
        ];
    }
}
