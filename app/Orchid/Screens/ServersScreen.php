<?php

namespace App\Orchid\Screens;

use App\Models\Server;
use App\Orchid\Layouts\ServersTableLayout;
use Orchid\Screen\Screen;

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
        return [];
    }

    /**
     * The screen's layout elements.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [
            ServersTableLayout::class
        ];
    }
}
