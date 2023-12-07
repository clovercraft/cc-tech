<?php

namespace App\Orchid\Screens;

use App\Facades\Discord;
use App\Models\AppSetting;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;

class AppSettingsScreen extends Screen
{
    public $settings;

    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        return ['settings'  => AppSetting::all(),];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Application Settings';
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [];
    }

    /**
     * The screen's layout elements.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        $block = [];
        $members = Discord::getGuildMembers();
        dd($members->toArray());
        if ($this->isDiscordInstalled()) {
            $block = [
                Layout::view(
                    'partials.platform.appSettingsDiscordReady'
                )
            ];
        } else {
            $block = [
                Layout::view('partials.platform.appSettingsDiscordInstall')
            ];
        }
        return [
            Layout::block($block)
                ->title('Discord API Integration')
                ->description('Manage the web app connection to the Discord server API')
        ];
    }

    private function isDiscordInstalled(): bool
    {
        return AppSetting::where('slug', 'discord_token')->exists();
    }
}
