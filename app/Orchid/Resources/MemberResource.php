<?php

namespace App\Orchid\Resources;

use Orchid\Crud\Resource;
use Orchid\Screen\TD;
use App\Models\Member;
use App\Orchid\Filters\MemberHasMinecraftFilter;
use App\Orchid\Filters\MembersMinecraftName;
use App\Orchid\Filters\MembersNameFilter;
use App\Orchid\Filters\MembersStatusFilter;
use App\Orchid\Inputs\CustomInput;
use Orchid\Screen\Fields\DateTimer;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Sight;

class MemberResource extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = Member::class;

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
            Input::make('name')
                ->title('Member name'),
            CustomInput::pronouns('pronouns'),
            CustomInput::birthday('birthday'),
            Input::make('status')
                ->title('Status')
                ->disabled(),
            DateTimer::make('intro_verified_at')
                ->title('Intro Verified Date')
                ->allowInput()
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
            TD::make('name'),
            TD::make('displayPronouns', 'Pronouns'),
            TD::make('birthday'),
            TD::make('', 'Minecraft Account')
                ->render(function (Member $member) {
                    $account = $member->minecraftAccounts->pluck('name')->unique()->first();
                    return $account;
                }),
            TD::make('status'),
            TD::make('lastseen_at', 'Last Seen')
                ->render(function (Member $member) {
                    if ($member->lastseen_at == null) {
                        return '';
                    }
                    return $member->lastseen_at->toDateTimeString();
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
        return [
            Sight::make('id'),
            Sight::make('name'),
            Sight::make('pronouns'),
            Sight::make('birthday'),
            Sight::make('status'),
            Sight::make('lastseen_at', 'Last Seen')
                ->popover('The last time our API was able to verify this member in the Discord server.')
                ->render(fn (Member $member) => empty($member->lastseen_at) ? 'never' : $member->lastseen_at->toDateTimeString()),
            Sight::make('minecraftAccounts', 'Minecraft Accounts')
                ->render(function (Member $member) {
                    return implode(', ', $member->minecraftAccounts->pluck('name')->toArray());
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
        return [
            MembersNameFilter::class,
            MembersMinecraftName::class,
            MembersStatusFilter::class,
            MemberHasMinecraftFilter::class,
        ];
    }
}
