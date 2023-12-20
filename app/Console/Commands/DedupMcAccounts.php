<?php

namespace App\Console\Commands;

use App\Models\MinecraftAccount;
use Illuminate\Console\Command;

class DedupMcAccounts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cc:dedup-mc-accounts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Removes all duplicated Minecraft UUID values';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $accounts = MinecraftAccount::all();
        $this->info('Validating ' . $accounts->count() . ' accounts...');
        $seen = [];
        $duplicates = 0;
        foreach ($accounts as $account) {
            if (in_array($account->uuid, $seen)) {
                $this->warn("Found duplicate Minecraft account: " . $account->name . " (" . $account->uuid . ")");
                $account->delete();
                $duplicates++;
            } else {
                $seen[] = $account->uuid;
            }
        }

        $this->info("Validated " . sizeof($seen) . " accounts.");
        $this->info("Removed " . $duplicates . " duplicates.");
        return Command::SUCCESS;
    }
}
