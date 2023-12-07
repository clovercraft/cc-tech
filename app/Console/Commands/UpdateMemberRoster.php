<?php

namespace App\Console\Commands;

use App\Facades\Discord;
use App\Models\Member;
use Illuminate\Console\Command;

class UpdateMemberRoster extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cc:update-member-roster';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Polls the server for a new member roster and updates the internal listing.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // check discord auth
        if (!Discord::has_authorization()) {
            $this->error("Discord API has not been authenticated for this instance.");
            return Command::FAILURE;
        }

        $this->info("Pulling roster from Discord");
        $members = Discord::get_members();

        $this->info("API returned " . $members->count() . " member records");

        // update or create the member entries
        foreach ($members as $member) {
            $user = $member['user'];
            if (key_exists('bot', $user) && $user['bot'] == true) {
                $this->info("Skipping bot user: " . $user['username']);
                continue;
            }
            $record = Member::syncFromDiscord($user);
            if ($record) {
                $this->info("Synced user: " . $record->name);
            }
        }

        $this->info("All members synced");

        return Command::SUCCESS;
    }
}
