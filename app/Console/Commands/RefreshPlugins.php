<?php

namespace App\Console\Commands;

use App\Models\Plugin;
use Illuminate\Console\Command;

class RefreshPlugins extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'plugins:refresh';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Refresh the latest data on each plugin';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Reading plugin library...');

        $plugins = Plugin::all();
        $this->info($plugins->count() . " plugins to refresh");

        foreach ($plugins as $plugin) {
            $plugin->updateLatestVersion();
        }
        $this->info('Updated all plugin data!');
        return Command::SUCCESS;
    }
}
