<?php

namespace App\Orchid\Filters;

use App\Models\MinecraftAccount;
use Illuminate\Database\Eloquent\Builder;
use Orchid\Filters\Filter;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\CheckBox;

class MemberHasMinecraftFilter extends Filter
{
    /**
     * The displayable name of the filter.
     *
     * @return string
     */
    public function name(): string
    {
        return 'Has Minecraft Account';
    }

    /**
     * The array of matched parameters.
     *
     * @return array|null
     */
    public function parameters(): ?array
    {
        return ['withmc'];
    }

    /**
     * Apply to a given Eloquent query builder.
     *
     * @param Builder $builder
     *
     * @return Builder
     */
    public function run(Builder $builder): Builder
    {
        if ($this->request->get('withmc') != false) {
            return $builder->whereHas('minecraftAccounts');
        }
        return $builder;
    }

    /**
     * Get the display fields.
     *
     * @return Field[]
     */
    public function display(): iterable
    {
        return [
            CheckBox::make('withmc')
                ->title('Has Minecraft Account')
                ->sendTrueOrFalse()
        ];
    }
}
