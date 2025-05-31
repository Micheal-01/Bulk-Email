<?php

namespace App\Http\Controllers;

use App\Models\EmailRecipient;
use Illuminate\Http\Request;

class RecipientsController extends Controller
{
    public function index()
    {
        $recipients = EmailRecipient::latest()->paginate(15);
        return view('recipients.index', compact('recipients'));
    }

    public function create()
    {
        return view('recipients.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:email_recipients,email',
            'name' => 'nullable|string|max:255',
        ]);

        EmailRecipient::create([
            'email' => $request->email,
            'name' => $request->name,
            'is_active' => true,
            'subscribed_at' => now()
        ]);

        return redirect()->route('recipients.index')
                        ->with('success', 'Recipient added successfully!');
    }

    public function bulkImport(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt'
        ]);

        $file = $request->file('csv_file');
        $csvData = array_map('str_getcsv', file($file));
        $header = array_shift($csvData);

        foreach ($csvData as $row) {
            $data = array_combine($header, $row);

            EmailRecipient::updateOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'] ?? null,
                    'is_active' => true,
                    'subscribed_at' => now()
                ]
            );
        }

        return redirect()->route('recipients.index')
                        ->with('success', 'Recipients imported successfully!');
    }
}
