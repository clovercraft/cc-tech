<?php

namespace App\Orchid\Layouts;

use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

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
        return [
            TD::make('name'),
            TD::make('ip', 'IP'),
            TD::make('type', 'Format'),
            TD::make('current_version', 'Version'),
            TD::make('')
                ->render(fn ($server) => ModalToggle::make('view'))
        ];
    }
}
