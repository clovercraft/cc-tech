<?php

namespace App\Orchid\Resources;

use App\Events\AnnouncementSaved;
use App\Models\Announcement;
use App\Models\Member;
use App\Notifications\NewAnnouncement;
use Illuminate\Database\Eloquent\Model;
use Orchid\Crud\Resource;
use Orchid\Crud\ResourceRequest;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Quill;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\TD;

class AnnouncementsResource extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = Announcement::class;

    public static function perPage(): int
    {
        return 25;
    }

    public static function permission(): ?string
    {
        return 'staff.members';
    }

    /**
     * Get the fields displayed by the resource.
     *
     * @return array
     */
    public function fields(): array
    {
        return [
            Input::make('title')
                ->title('Title')
                ->required(),
            Input::make('slug')
                ->title('Slug')
                ->help('This will be the end of the URL for this announcement. ie: december-2023-newsletter')
                ->required(),
            Quill::make('content')
                ->title('Content')
                ->toolbar(["text", "color", "header", "list", "format"])
                ->required(),
            Select::make('status')
                ->options([
                    'draft'     => 'Draft',
                    'published' => 'Published',
                    'hidden'    => 'Hidden'
                ])
                ->required()
        ];
    }

    /**
     * Get the columns displayed by the resource.
     *
     * @return TD[]
     */
    public function columns(): array
    {
        return [
            TD::make('title'),
            TD::make('updated_at', 'Publish Date')
                ->render(fn ($model) => $model->updated_at->format('d M, Y')),
            TD::make('status', 'Status')
        ];
    }

    /**
     * Get the sights displayed by the resource.
     *
     * @return Sight[]
     */
    public function legend(): array
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @return array
     */
    public function filters(): array
    {
        return [];
    }

    public function onSave(ResourceRequest $request, Model $model)
    {
        $model->forceFill($request->all())->save();

        $announcement = $model;
        if ($announcement->status == 'published' && $announcement->notice_sent == false) {
            $this->sendNotice($announcement);
            $announcement->notice_sent = true;
            $announcement->save();
        }
    }

    private function sendNotice(Announcement $announcement)
    {
        $notice = new NewAnnouncement($announcement);
        $members = Member::where('status', 'active')->whereHas('minecraftAccounts')->whereHas('user')->get();
        foreach ($members as $member) {
            $user = $member->user;
            if (empty($user)) {
                continue;
            }
            $user->notify($notice);
        }
    }
}
