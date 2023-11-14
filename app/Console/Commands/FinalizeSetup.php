<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class FinalizeSetup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:finalize-setup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Completes the first time setup for a new deploy';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if ($this->isFirstSetup()) {
            $this->info('Running first time setup actions');

            // create admin user
            $user = env('SETUP_ADMIN_USERNAME', 'admin');
            $email = env('SETUP_ADMIN_EMAIL', 'martin.sheeks@gmail.com');
            $password = env('SETUP_ADMIN_PASSWORD', Str::password());

            $command = sprintf("orchid:admin %s %s %s", $user, $email, $password);

            Artisan::call($command);
            $this->info('Admin user created.');
            $this->table(
                ['param', 'value'],
                [
                    ['user', $user],
                    ['email', $email],
                    ['password', $password]
                ]
            );
        } else {
            $this->info('Data already exists, skipping first time setup.');
        }
        return Command::SUCCESS;
    }

    public function isFirstSetup(): bool
    {
        try {
            $users = DB::table('users')->count();
            if ($users > 0) {
                return false;
            }
        } catch (\Exception $e) {
            return true;
        }
        return true;
    }
}
