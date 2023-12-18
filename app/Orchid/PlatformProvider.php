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
            Menu::make('My Account')
                ->icon('bs.person')
                ->title('Account Management')
                ->route('platform.member.self'),
        ]);

        if (Auth::user()->hasAccess('content.edit')) {
            $menu->push(
                Menu::make('Pages')
                    ->icon('bs.page')
                    ->route('platform.pages')
                    ->title('Content Manager')
            );
        }

        if (Auth::user()->hasAccess('tech.view')) {
            $menu->push(
                Menu::make('Servers')
                    ->icon('bs.server')
                    ->route('platform.servers')
                    ->title('Technical Assets'),

                Menu::make('Plugins')
                    ->icon('bs.plugin')
                    ->route('platform.plugins'),
            );
        }

        if (Auth::user()->hasAccess('creds.view')) {
            $menu->push(
                Menu::make('Credentials')
                    ->icon('bs.lock')
                    ->route('platform.credentials')
                    ->title('Secrets Manager')
            );
        }

        if (Auth::user()->hasAccess('platform.systems.users')) {
            $menu->push(
                Menu::make(__('Users'))
                    ->icon('bs.people')
                    ->route('platform.systems.users')
                    ->permission('platform.systems.users')
                    ->title(__('Tool Admin')),

                Menu::make(__('Roles'))
                    ->icon('bs.shield')
                    ->route('platform.systems.roles')
                    ->permission('platform.systems.roles')
                    ->divider()
            );
        }

        if (Auth::user()->hasAccess('platform.systems.settings')) {
            $menu->push(
                Menu::make(__('Application Settings'))
                    ->icon('bs.gear-wide-connected')
                    ->route('platform.systems.settings')
                    ->permission('platform.systems.settings')
                    ->title(__('Site Settings')),
                Menu::make(__('Task Scheduler'))
                    ->icon('bs.stopwatch')
                    ->url('/totem')
                    ->permission('platform.systems.scheduler')
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
            ItemPermission::group(__('Members'))
                ->addPermission('member.manage', __('Manage'))
                ->addPermission('member.manage-self', __('Manage Self')),

            ItemPermission::group(__('Content Manager'))
                ->addPermission('content.edit', __('Edit'))
                ->addPermission('content.create', __('Create'))
                ->addPermission('content.delete', __('Delete')),

            ItemPermission::group(__('Technical Assets'))
                ->addPermission('tech.view',    __('View'))
                ->addPermission('tech.create',  __('Create'))
                ->addPermission('tech.edit',    __('Edit'))
                ->addPermission('tech.delete',  __('Delete')),

            ItemPermission::group(__('Credentials'))
                ->addPermission('creds.view',    __('View'))
                ->addPermission('creds.create',  __('Create'))
                ->addPermission('creds.edit',    __('Edit'))
                ->addPermission('creds.delete',  __('Delete')),

            ItemPermission::group(__('System'))
                ->addPermission('platform.systems.roles', __('Roles'))
                ->addPermission('platform.systems.users', __('Users'))
                ->addPermission('platform.systems.settings', __('Settings'))
                ->addPermission('platform.systems.scheduler', __('Scheduler')),
        ];
    }
}
