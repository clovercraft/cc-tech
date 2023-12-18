<?php

namespace App\Orchid\Resources;

use App\Models\MinecraftAccount;
use Orchid\Crud\Resource;
use Orchid\Screen\Fields\Code;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Matrix;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Sight;
use Orchid\Screen\TD;

class MinecraftEventResource extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\MinecraftEvent::class;

    public static function perPage(): int
    {
        return 25;
    }

    public static function permission(): ?string
    {
        return 'member.manage';
    }

    /**
     * Get the fields displayed by the resource.
     *
     * @return array
     */
    public function fields(): array
    {
        return [
            Input::make('event_type')
                ->title('Event Type'),
            Code::make('context')
                ->language(Code::JS)
                ->title('Context'),
            Relation::make('minecraft_account_id')
                ->fromModel(MinecraftAccount::class, 'name')
                ->title('Player')
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
            TD::make('event_type', 'Event Type'),
            TD::make('created_at', 'Event Time')
                ->render(function ($model) {
                    return $model->created_at->format('m/d/Y h:m:i');
                })
        ];
    }

    /**
     * Get the sights displayed by the resource.
     *
     * @return Sight[]
     */
    public function legend(): array
    {
        return [
            Sight::make('created_at', 'Event Time')
                ->render(function ($model) {
                    return $model->created_at->format('m/d/Y h:m:i');
                }),
            Sight::make('event_type', 'Event Type'),
            Sight::make('context', 'Context'),
            Sight::make('minecraftAccount', 'Player Account')
                ->render(function ($model) {
                    if (in_array($model->event_type, ['startup', 'shutdown'])) {
                        return 'Server';
                    } else {
                        return $model->minecraftAccount->name;
                    }
                })
        ];
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
