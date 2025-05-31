<?php

namespace App\Http\Controllers;

use App\Models\EmailCampaign;
use App\Models\EmailRecipient;
use App\Services\BulkEmailService;
use Illuminate\Http\Request;

class BulkEmailController extends Controller
{
    protected $bulkEmailService;

    public function __construct(BulkEmailService $bulkEmailService)
    {
        $this->bulkEmailService = $bulkEmailService;
    }

    public function index()
    {
        $campaigns = EmailCampaign::latest()->paginate(10);
        return view('bulk-email.index', compact('campaigns'));
    }

    public function create()
    {
        $recipients = EmailRecipient::where('is_active', true)->get();
        return view('bulk-email.create', compact('recipients'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'body' => 'required|string',
        ]);

        $campaign = $this->bulkEmailService->createCampaign($request->all());

        return redirect()->route('bulk-email.show', $campaign)
                        ->with('success', 'Campaign created successfully!');
    }

    public function show(EmailCampaign $campaign)
    {
        $stats = $this->bulkEmailService->getCampaignStats($campaign);
        $recipients = EmailRecipient::where('is_active', true)->get();

        return view('bulk-email.show', compact('campaign', 'stats', 'recipients'));
    }

    public function send(Request $request, EmailCampaign $campaign)
    {
        $recipientIds = $request->input('recipient_ids', []);

        $this->bulkEmailService->sendCampaign($campaign, $recipientIds);
         dd([
        'campaign' => $campaign,
        'recipients' => $recipientIds,
    ]);

        return redirect()->route('bulk-email.show', $campaign)
                        ->with('success', 'Campaign emails are being sent!');
    }

    public function markComplete(EmailCampaign $campaign)
    {
        $campaign->update(['status' => 'sent']);

        return redirect()->route('bulk-email.show', $campaign)
                        ->with('success', 'Campaign marked as complete!');
    }

    public function destroy(EmailCampaign $campaign)
    {
        if ($campaign->status === 'sending') {
            return redirect()->route('bulk-email.index')
                            ->with('error', 'Cannot delete a campaign that is currently sending.');
        }

        $campaign->delete();

        return redirect()->route('bulk-email.index')
                        ->with('success', 'Campaign deleted successfully!');
    }
}
