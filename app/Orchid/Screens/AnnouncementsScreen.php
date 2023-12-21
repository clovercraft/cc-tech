<?php

namespace App\Orchid\Screens;

use App\Models\Announcement;
use Carbon\Carbon;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Layouts\Modal;
use Orchid\Screen\Screen;
use Orchid\Screen\Sight;
use Orchid\Screen\TD;
use Orchid\Support\Facades\Layout;

class AnnouncementsScreen extends Screen
{
    public $announcements;
    public $current;

    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'announcements' => Announcement::where('status', 'published')->paginate(),
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Announcements and Updates';
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
            Layout::table('announcements', [
                TD::make('updated_at', 'Date')
                    ->sort()
                    ->render(fn ($announcement) => $announcement->updated_at->format('d M, Y'))
                    ->width('125px'),
                TD::make('title', '')
                    ->sort()
                    ->render(fn ($announcement) =>
                    Link::make($announcement->title)
                        ->route('platform.announcements.view', ['announcement' => $announcement]))
            ]),
        ];
    }
}
