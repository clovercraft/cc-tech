<?php

namespace App\Orchid\Layouts\Plugins;

use Orchid\Screen\Actions\Link;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class PluginsTableLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'plugins';

    protected function textNotFound(): string
    {
        return __('No plugins currently tracked');
    }

    protected function subNotFound(): string
    {
        return __('Add plugins using the controls above to track them for updates.');
    }

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            TD::make('name')->sort(),
            TD::make('current', 'Current Version')->sort(),
            TD::make('latest', 'Latest Release')->sort(),
            TD::make('')
                ->render(fn ($plugin) => Link::make('Download')
                    ->href($plugin->download_link))
        ];
    }
}
