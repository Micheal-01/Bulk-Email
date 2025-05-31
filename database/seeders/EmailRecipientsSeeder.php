<?php

namespace Database\Seeders;

use App\Models\EmailRecipient;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EmailRecipientsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $recipients = [
            ['email' => 'michealtomisin@gmail.com', 'name' => 'Tomisin'],

            // Add more test recipients
        ];

        foreach ($recipients as $recipient) {
            EmailRecipient::create([
                'email' => $recipient['email'],
                'name' => $recipient['name'],
                'is_active' => true,
                'subscribed_at' => now()
            ]);
        }

    }
}
