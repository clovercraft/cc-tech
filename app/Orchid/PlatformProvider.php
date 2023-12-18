<?php

declare(strict_types=1);

namespace App\Orchid;

use Illuminate\Support\Facades\Auth;
use Orchid\Platform\Dashboard;
use Orchid\Platform\ItemPermission;
use Orchid\Platform\OrchidServiceProvider;
use Orchid\Screen\Actions\Menu;
use Orchid\Support\Color;

class PlatformProvider extends OrchidServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @param Dashboard $dashboard
     *
     * @return void
     */
    public function boot(Dashboard $dashboard): void
    {
        parent::boot($dashboard);

        // ...
    }

    /**
     * Register the application menu.
     *
     * @return Menu[]
     */
    public function menu(): array
    {
        $menu = collect([
            Menu::make('Dashboard')
                ->icon('bs.house')
                ->route(config('platform.index')),
            Menu::make('My Account')
                ->icon('bs.person')
                ->route('platform.member.self'),
        ]);

        if (Auth::user()->hasAccess('staff.default')) {
            $menu->push(
                Menu::make('Servers')
                    ->icon('bs.server')
                    ->route('platform.servers')
                    ->title('Staff Tools'),
                Menu::make('Plugins')
                    ->icon('bs.plugin')
                    ->route('platform.plugins'),
                Menu::make('Pages')
                    ->icon('bs.files')
                    ->route('platform.pages'),
            );
        }

        if (Auth::user()->hasAccess('staff.creds')) {
            $menu->push(
                Menu::make('Credentials')
                    ->icon('bs.lock')
                    ->route('platform.credentials')
            );
        }

        if (Auth::user()->hasAccess('staff.system')) {
            $menu->push(
                Menu::make(__('Application Settings'))
                    ->icon('bs.gear-wide-connected')
                    ->route('staff.system'),
                Menu::make(__('Task Scheduler'))
                    ->icon('bs.stopwatch')
                    ->url('/totem')
            );
        }

        if (Auth::user()->hasAccess('platform.systems.users')) {
            $menu->push(
                Menu::make(__('Users'))
                    ->icon('bs.people')
                    ->route('platform.systems.users')
                    ->permission('platform.systems.users')
                    ->title(__('User Admin')),

                Menu::make(__('Roles'))
                    ->icon('bs.shield')
                    ->route('platform.systems.roles')
                    ->permission('platform.systems.roles')
                    ->divider()
            );
        }

        return $menu->toArray();
    }

    /**
     * Register permissions for the application.
     *
     * @return ItemPermission[]
     */
    public function permissions(): array
    {
        return [
            ItemPermission::group(__('Member Components'))
                ->addPermission('member.self', __('Default Permissions'))
                ->addPermission('member.assets', __('Upload Assets'))
                ->addPermission('member.news', __('View Announcements')),

            ItemPermission::group(__('Staff Components'))
                ->addPermission('staff.default', __('Default Permissions'))
                ->addPermission('staff.members', __('Manage Members'))
                ->addPermission('staff.content', __('Manage Content'))
                ->addPermission('staff.news', __('Manage Announcements'))
                ->addPermission('staff.tech', __('Manage Technology'))
                ->addPermission('staff.creds', __('Manage Credentials'))
                ->addPermission('staff.system', __('HUB Site Settings')),

            ItemPermission::group(__('System'))
                ->addPermission('platform.systems.roles', __('Roles'))
                ->addPermission('platform.systems.users', __('Users'))
        ];
    }
}
