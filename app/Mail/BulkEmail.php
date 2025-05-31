<?php

namespace App\Mail;

use App\Models\EmailCampaign;
use App\Models\EmailRecipient;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BulkEmail extends Mailable
{

    use Queueable, SerializesModels;
    public $campaign;
    public $recipient;
    /**
     * Create a new message instance.
     */
    public function __construct(EmailCampaign $campaign, EmailRecipient $recipient)
    {
        $this->campaign = $campaign;
        $this->recipient = $recipient;
    }

    public function build()
    {
        return $this->subject($this->campaign->subject)
                    ->view('emails.bulk-email')
                    ->with([
                        'campaignBody' => $this->campaign->body,
                        'recipientName' => $this->recipient->name,
                        'recipientEmail' => $this->recipient->email,
                        'metadata' => $this->recipient->metadata
                    ]);
    }


}
