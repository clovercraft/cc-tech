<?php

namespace App\Orchid\Screens\Members;

use App\Models\Member;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;

class MemberDetailScreen extends Screen
{
    public $member;

    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(Member $member): iterable
    {
        return [
            'member' => $member
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return $this->member->name . ' | Member Details';
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
        return [];
    }
}
