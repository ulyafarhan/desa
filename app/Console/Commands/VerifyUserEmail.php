<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class VerifyUserEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:verify-email {email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Manually verify a user\'s email address';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');

        $user = User::where('email', $email)->first();

        if ($user) {
            if ($user->hasVerifiedEmail()) {
                $this->info("User with email {$email} has already been verified.");
            } else {
                $user->markEmailAsVerified();
                $this->info("Email address for user with email {$email} has been verified successfully.");
            }
        } else {
            $this->error("User with email {$email} not found.");
        }
    }
}