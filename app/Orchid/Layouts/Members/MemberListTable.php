<?php

namespace App\Orchid\Layouts\Members;

use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;
use App\Models\Member;

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
            TD::make('name')
                ->sort(),
            TD::make('pronouns')
                ->sort(),
            TD::make('birthday')
                ->sort()
                ->render(function (Member $member) {
                    return $member->birthday->format('M d, Y');
                })
        ];
    }
}
