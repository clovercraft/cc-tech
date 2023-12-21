<?php

namespace App\Orchid\Screens;

use App\Models\Announcement;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;

class AnnouncementViewScreen extends Screen
{
    public $post;

    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(Announcement $announcement): iterable
    {
        return [
            'post'  => $announcement
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return $this->post->title;
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
            Layout::view('partials.platform.announcement', ['announcement' => $this->post]),
        ];
    }
}
