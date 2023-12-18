<?php

declare(strict_types=1);

use App\Orchid\Screens\AppSettingsScreen;
use App\Orchid\Screens\CredentialsScreen;
use App\Orchid\Screens\MemberAccountScreen;
use App\Orchid\Screens\Members\MemberDetailScreen;
use App\Orchid\Screens\Members\MembersScreen;
use App\Orchid\Screens\PagesScreen;
use App\Orchid\Screens\PlatformScreen;
use App\Orchid\Screens\PluginsScreen;
use App\Orchid\Screens\Role\RoleEditScreen;
use App\Orchid\Screens\Role\RoleListScreen;
use App\Orchid\Screens\Servers\ServerDetailScreen;
use App\Orchid\Screens\Servers\ServersScreen;
use App\Orchid\Screens\User\UserEditScreen;
use App\Orchid\Screens\User\UserListScreen;
use App\Orchid\Screens\User\UserProfileScreen;
use Illuminate\Support\Facades\Route;
use Tabuna\Breadcrumbs\Trail;

/*
|--------------------------------------------------------------------------
| Dashboard Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the need "dashboard" middleware group. Now create something great!
|
*/

// Main
Route::screen('/main', PlatformScreen::class)
    ->name('platform.main');

// Platform > Profile
Route::screen('profile', UserProfileScreen::class)
    ->name('platform.profile')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.index')
        ->push(__('Profile'), route('platform.profile')));

// Platform > System > Users > User
Route::screen('users/{user}/edit', UserEditScreen::class)
    ->name('platform.systems.users.edit')
    ->breadcrumbs(fn (Trail $trail, $user) => $trail
        ->parent('platform.systems.users')
        ->push($user->name, route('platform.systems.users.edit', $user)));

// Platform > System > Users > Create
Route::screen('users/create', UserEditScreen::class)
    ->name('platform.systems.users.create')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.systems.users')
        ->push(__('Create'), route('platform.systems.users.create')));

// Platform > System > Users
Route::screen('users', UserListScreen::class)
    ->name('platform.systems.users')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.index')
        ->push(__('Users'), route('platform.systems.users')));

// Platform > System > Roles > Role
Route::screen('roles/{role}/edit', RoleEditScreen::class)
    ->name('platform.systems.roles.edit')
    ->breadcrumbs(fn (Trail $trail, $role) => $trail
        ->parent('platform.systems.roles')
        ->push($role->name, route('platform.systems.roles.edit', $role)));

// Platform > System > Roles > Create
Route::screen('roles/create', RoleEditScreen::class)
    ->name('platform.systems.roles.create')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.systems.roles')
        ->push(__('Create'), route('platform.systems.roles.create')));

// Platform > System > Roles
Route::screen('roles', RoleListScreen::class)
    ->name('platform.systems.roles')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.index')
        ->push(__('Roles'), route('platform.systems.roles')));

Route::screen('servers', ServersScreen::class)
    ->name('platform.servers')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.index')
        ->push(__('Servers'), route('platform.servers')));

Route::screen('servers/{server}', ServerDetailScreen::class)
    ->name('platform.server.details')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.servers')
        ->push(__('Detail View')));

Route::screen('plugins', PluginsScreen::class)
    ->name('platform.plugins')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.index')
        ->push(__('Plugins'), route('platform.plugins')));

Route::screen('credentials', CredentialsScreen::class)
    ->name('platform.credentials')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.index')
        ->push(__('Credentials'), route('platform.credentials')));

Route::screen('members', MembersScreen::class)
    ->name('platform.members')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.index')
        ->push(__('Members'), route('platform.members')));

Route::screen('me', MemberAccountScreen::class)
    ->name('platform.member.self')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.index')
        ->push(__('My Account')));

Route::screen('members/{member}', MemberDetailScreen::class)
    ->name('platform.member.details')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.members')
        ->push(__('Detail View')));

Route::screen('settings', AppSettingsScreen::class)
    ->name('platform.systems.settings')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.index')
        ->push(__('App Settings')));

Route::screen('pages', PagesScreen::class)
    ->name('platform.pages')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.index')
        ->push(__('Content Manager')));
