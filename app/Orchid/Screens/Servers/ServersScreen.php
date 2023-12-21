<?php

namespace App\Orchid\Screens\Servers;

use App\Models\Server;
use App\Orchid\Layouts\Servers\ServersTableLayout;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Layouts\Modal;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;

class ServersScreen extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        return ['servers' => Server::all()];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Servers';
    }

    /**
     * Display header description.
     */
    public function description(): ?string
    {
        return 'Manage Clovercraft SMP Instances';
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            ModalToggle::make('Add Server')
                ->modal('addServerModal')
                ->method('saveServer')
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
            ServersTableLayout::class,
            Layout::modal('addServerModal', [
                Layout::rows([
                    Input::make('name')
                        ->title('Name')
                        ->help('A friendly display name for this server')
                        ->required(),
                    Input::make('ip')
                        ->title('IP Address')
                        ->help('The connection IP for this server')
                        ->required(),
                    Select::make('type')
                        ->title('Server Type')
                        ->options([
                            'vanilla'   => 'Vanilla',
                            'forge'     => 'Forge',
                            'fabric'    => 'Fabric'
                        ])
                        ->default('vanilla')
                        ->required(),
                    Input::make('current_version')
                        ->title('Minecraft Version')
                        ->help('The official Minecraft release version the server is running')
                        ->required()
                ])
            ])
                ->size(Modal::SIZE_LG)
                ->title('Add Server')
                ->applyButton('Save')
                ->closeButton('Cancel'),
        ];
    }

    public function saveServer(Request $request)
    {
        $request->validate([
            'name'  => 'required',
            'ip'    => 'required',
            'type'  => 'required|in:vanilla,forge,fabric'
        ]);

        $properties = $request->all();
        $properties['api_key'] = Str::uuid();

        $server = new Server($properties);
        $server->save();
    }
}
