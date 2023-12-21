<?php

namespace App\Orchid\Screens\Servers;

use App\Jobs\ExportWhitelist;
use App\Models\Plugin;
use App\Models\Server;
use App\Orchid\Layouts\Plugins\PluginsTableLayout;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Screen;
use Orchid\Screen\Sight;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class ServerDetailScreen extends Screen
{
    public $server;
    public $plugins;
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(Server $server): iterable
    {
        return [
            'server'    => $server,
            'plugins'   => $server->plugins,
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return $this->server->name . ' Detail View';
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            ModalToggle::make('Manage Plugins')
                ->modal('managePluginsModal')
                ->method('savePlugins')
                ->icon('bs.plus'),
            ModalToggle::make('Manage Whitelist')
                ->modal('manageWhitelistModal')
                ->method('saveWhitelistConfig')
                ->icon('bs.toggles'),
            Button::make('Reset Token')
                ->method('resetServerToken'),
        ];
    }

    /**
     * The screen's layout elements.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [
            Layout::legend('server', [
                Sight::make('type'),
                Sight::make('current_version'),
                Sight::make('ip'),
                Sight::make('api_key'),
                Sight::make('whitelist_active', 'Whitelist Active')
                    ->render(fn ($server) => $server->whitelist_active ? 'Yes' : 'No')
            ]),
            PluginsTableLayout::class,
            Layout::modal('managePluginsModal', [
                Layout::rows([
                    Relation::make('plugins.')
                        ->fromModel(Plugin::class, 'name')
                        ->multiple()
                        ->title('Select Installed Plugins')
                ])
            ])
                ->title('Manage Server Plugins')
                ->applyButton('Save'),
            Layout::modal('manageWhitelistModal', [
                Layout::rows([
                    CheckBox::make('server.whitelist_active')
                        ->title('Whitelist Active')
                        ->sendTrueOrFalse(),
                    CheckBox::make('run_export')
                        ->title('Run Export')
                        ->sendTrueOrFalse(),
                ])
            ])
                ->title('Server Whitelist Config')
                ->applyButton('Save'),
        ];
    }

    public function savePlugins(Request $request): void
    {
        $request->validate([
            'plugins'   => 'required|array'
        ]);

        $plugins = $request->input('plugins');
        foreach ($plugins as $plugin_id) {
            $this->server->plugins()->attach($plugin_id);
        }
    }

    public function resetServerToken(): void
    {
        $this->server->api_key = Str::uuid();
        $this->server->save();
    }

    public function saveWhitelistConfig(Request $request)
    {
        $request->validate([
            'server.whitelist_active'   => 'required',
            'run_export'                => 'required'
        ]);

        $whitelist = (int) $request->input('server.whitelist_active') > 0;
        $export = (int) $request->input('run_export') > 0;

        $this->server->whitelist_active = $whitelist;
        $this->server->save();

        if ($whitelist && $export) {
            ExportWhitelist::dispatchSync($this->server);
            Toast::success("Whitelist has been exported!");
        }
    }
}
