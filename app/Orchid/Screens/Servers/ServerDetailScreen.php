<?php

namespace App\Orchid\Screens\Servers;

use App\Models\Plugin;
use App\Models\Server;
use App\Orchid\Layouts\Plugins\PluginsTableLayout;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;

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
                ->icon('bs.plus')
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
            Layout::view('partials.platform.serverDetails'),
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
                ->applyButton('Save')
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
}
