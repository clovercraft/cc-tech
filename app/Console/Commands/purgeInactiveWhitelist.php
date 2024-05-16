<?php

namespace App\Console\Commands;

use App\Models\Member;
use Illuminate\Console\Command;

class purgeInactiveWhitelist extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cc:purge-inactive-whitelist';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove any inactive member accounts from whitelist';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $timeout = now()->subWeek()->toDateTimeString();
        $stale = Member::where('lastseen_at', '<', $timeout)->get();
        $this->info("Timeout cutoff: " . $timeout);
        $this->info($stale->count() . " stale accounts.");
        foreach ($stale as $member) {
            $member->deactivate();
        }
        $this->info("Purged inactive members.");
        return Command::SUCCESS;
    }
}
