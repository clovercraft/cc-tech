<?php

namespace App\Console\Commands;

use App\Models\Plugin;
use Illuminate\Console\Command;

class SpigotRefreshPlugins extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'spigot:refresh';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Refresh the latest data on each Spigot plugin';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Reading plugin library...');

        $plugins = Plugin::all();
        $this->info($plugins->count() . " plugins to refresh");

        foreach ($plugins as $plugin) {
            if ($plugin->isSpigotPlugin()) {
                $latest = $plugin->updateLatestVersion();
                $version = $latest->get('name');
                $plugin->latest = $version;
                $plugin->save();
            }
        }
        $this->info('Updated all plugin data!');
        return Command::SUCCESS;
    }
}
