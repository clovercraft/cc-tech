<?php

namespace App\Console\Commands;

use App\Facades\Minecraft;
use App\Models\Member;
use App\Models\MinecraftAccount;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class LegacyMemberImport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cc:legacy-member-import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import the latest file in the runfiles folder to sync Minecraft accounts';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Finding latest file');
        $files = Storage::disk('runfiles')->allFiles();

        if (count($files) > 1) {
            $file = $this->getLatestFile($files);
        } else {
            $file = $files[0];
        }
        $this->info('Loading file: ' . $file);
        $json = Storage::disk('runfiles')->get($file);
        $data = collect(json_decode($json));

        $this->info('Parsed ' . $data->count() . ' legacy member records.');

        foreach ($data as $obj) {
            $member = Member::where('discord_id', $obj->discord_id)->first();
            if (empty($member)) {
                $this->warn("No member record found for legacy record: " . $obj->name);
                continue;
            }

            $exists = MinecraftAccount::where('uuid', $obj->minecraft_id)->count();
            if ($exists > 0) {
                $this->info("Skipping existing minecraft account uuid: " . $obj->minecraft_id);
                continue;
            }

            $player = Minecraft::getAccount($obj->minecraft_id);
            $account = new MinecraftAccount();
            $account->name = $player['username'];
            $account->uuid = $player['id'];
            $account->status = MinecraftAccount::ACTIVE;
            $member->minecraftAccounts()->save($account);
            $this->info("Created minecraft account: " . $account->name . " for member: " . $member->name);
        }

        return Command::SUCCESS;
    }

    private function getLatestFile(array $filenames): string
    {
        $files = collect([]);
        foreach ($filenames as $file) {
            $sortable = [
                'filename'  => $file,
                'time'      => now()->diffInSeconds(Storage::disk('runfiles')->lastModified($file))
            ];
            $files->push($sortable);
        }
        $files->sortBy('time');
        return $files->first();
    }
}
