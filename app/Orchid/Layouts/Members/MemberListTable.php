<?php

namespace App\Orchid\Layouts\Members;

use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;
use App\Models\Member;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Support\Facades\Layout;

class MemberListTable extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'members';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            TD::make('name', 'Discord Name')
                ->sort(),
            TD::make('pronouns', 'Pronouns'),
            TD::make('playerTag', 'Player Tag')
                ->sort(),
            TD::make('birthday', 'Birthday')
                ->sort(),
            TD::make()
                ->render(fn ($member) =>
                Button::make('View')
                    ->icon('bs.eye')
                    ->method())
        ];
    }
}
