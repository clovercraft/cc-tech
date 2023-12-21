<?php

namespace App\Orchid\Layouts\Servers;

use Orchid\Icons\IconComponent;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;
use Orchid\Support\Facades\Layout;

class ServersTableLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'servers';

    protected function textNotFound(): string
    {
        return __('No servers currently registered');
    }

    protected function subNotFound(): string
    {
        return __('Add an SMP instance using the controls above.');
    }

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        $whitelistOn = (new IconComponent('bs.check-circle'))->render()();
        $whitelistOff = (new IconComponent('bs.x-circle'))->render()();
        return [
            TD::make('name'),
            TD::make('ip', 'IP'),
            TD::make('type', 'Format'),
            TD::make('current_version', 'Version'),
            TD::make('whitelist_active', 'Whitelist Enabled')
                ->render(fn ($server) => $server->whitelist_active ? $whitelistOn : $whitelistOff),
            TD::make('')
                ->render(fn ($server) => Link::make('view')
                    ->route('platform.server.details', ['server' => $server])
                    ->icon('bs.eye'))
        ];
    }
}
