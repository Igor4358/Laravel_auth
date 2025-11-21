<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use App\Jobs\MailSendJob;

class TestEmailCommand extends Command
{
    protected $signature = 'email:test {email}';
    protected $description = 'Send test welcome email';

    public function handle()
    {
        $email = $this->argument('email');

        $user = new User([
            'name' => 'Test User',
            'email' => $email,
        ]);

        MailSendJob::dispatch($user);

        $this->info("Test email sent to {$email}");
    }
}
