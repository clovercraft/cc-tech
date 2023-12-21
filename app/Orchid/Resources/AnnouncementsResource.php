<?php

namespace App\Orchid\Resources;

use App\Models\Announcement;
use Orchid\Crud\Resource;
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
            TD::make('id'),

            TD::make('created_at', 'Date of creation')
                ->render(function ($model) {
                    return $model->created_at->toDateTimeString();
                }),

            TD::make('updated_at', 'Update date')
                ->render(function ($model) {
                    return $model->updated_at->toDateTimeString();
                }),
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
}
