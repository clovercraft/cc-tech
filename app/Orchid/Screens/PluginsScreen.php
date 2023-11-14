<?php

namespace App\Orchid\Screens;

use App\Models\Plugin;
use App\Models\Server;
use App\Orchid\Layouts\Plugins\PluginsTableLayout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Layouts\Modal;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class PluginsScreen extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        return ['plugins' => Plugin::paginate()];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Plugins';
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Button::make('Refresh Data')
                ->method('refresh')
                ->icon('bs.arrow-clockwise'),
            ModalToggle::make('Track Plugin')
                ->modal('addPluginModal')
                ->method('savePlugin')
                ->icon('bs.plus'),
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
            PluginsTableLayout::class,
            Layout::modal('addPluginModal', [
                Layout::rows([
                    Input::make('name')
                        ->title('Name')
                        ->placeholder('Super Awesome Plugin')
                        ->help('The display name for this plugin'),
                    Input::make('description')
                        ->title('Description')
                        ->placeholder('Makes those things do other things')
                        ->help('What this plugin does for the server'),
                    Input::make('source')
                        ->title('Plugin URL')
                        ->placeholder('https://spigotmc.org/plugins/myplugin')
                        ->help('The homepage for this plugin'),
                    Input::make('modrinth_id')
                        ->title('Modrinth Project ID')
                        ->placeholder('RKp8fKrT')
                        ->help('The Modrinth Project ID for this plugin'),
                    Input::make('current')
                        ->title('Current plugin version')
                        ->placeholder('1.19.3')
                        ->help('The version of the plugin currently in use'),
                    CheckBox::make('in_use')
                        ->title('Active')
                        ->help('Is the plugin currently in use'),
                    Relation::make('servers.')
                        ->fromModel(Server::class, 'name')
                        ->multiple()
                        ->title('Attach to Servers')
                ]),
            ])
                ->size(Modal::SIZE_LG)
                ->title('Track New Plugin')
                ->applyButton('Save')
                ->closeButton('Cancel'),
        ];
    }

    public function savePlugin(Request $request)
    {
        $request->validate([
            'name'          => 'required',
            'description'   => 'required',
            'source'        => 'required|url',
            'current'       => 'required',
            'servers'       => 'array'
        ]);

        $input = $request->all();
        $input['in_use'] = $input['in_use'] == 'on' ? true : false;
        $input['latest'] = $input['current'];

        $plugin = new Plugin($input);
        $plugin->save();

        if (isset($input['servers'])) {
            foreach ($input['servers'] as $server_id) {
                $plugin->servers()->attach($server_id);
            }
        }
    }

    public function refresh()
    {
        Artisan::call('plugins:refresh');
        Toast::success('Refreshed plugin data!');
    }
}
