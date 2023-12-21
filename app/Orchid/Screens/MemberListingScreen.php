<?php

namespace App\Orchid\Screens;

use App\Models\Member;
use League\CommonMark\Node\Block\Paragraph;
use Nette\Utils\Html;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Screen;
use Orchid\Screen\Sight;
use Orchid\Screen\TD;
use Orchid\Support\Facades\Layout;

class MemberListingScreen extends Screen
{

    public $members;

    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'members'   => Member::where('status', 'active')->whereHas('minecraftAccounts')->paginate()
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Member Listing';
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
            Layout::view('partials.platform.memberListingHeader'),
            Layout::table('members', [
                TD::make('name', 'Discord Name')
                    ->sort(),
                TD::make('pronouns', 'Pronouns'),
                TD::make('playerTag', 'Player Tag')
                    ->sort(),
                TD::make('birthday', 'Birthday')
                    ->sort(),
                TD::make()
                    ->render(fn ($member) => ModalToggle::make('View Profile')
                        ->icon('bs.eye')
                        ->modal('profileModal')
                        ->asyncParameters([
                            'member' => $member
                        ])),
            ]),
        ];
    }
}
