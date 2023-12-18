<?php

namespace App\Orchid\Filters;

use App\Models\Member;
use Illuminate\Database\Eloquent\Builder;
use Orchid\Filters\Filter;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\Select;

class MembersStatusFilter extends Filter
{
    /**
     * The displayable name of the filter.
     *
     * @return string
     */
    public function name(): string
    {
        return 'Member Status';
    }

    /**
     * The array of matched parameters.
     *
     * @return array|null
     */
    public function parameters(): ?array
    {
        return ['status'];
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
        $value = $this->request->get('status');
        if (!empty($value)) {
            return $builder->where('status', $value);
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
            Select::make('status')
                ->options([
                    ''                          => 'Any',
                    Member::STATUS_ACTIVE       => 'Active',
                    Member::STATUS_WHITELISTED  => 'Whitelisted',
                    Member::STATUS_INACTIVE     => 'Inactive',
                ])
                ->value('')
        ];
    }
}
