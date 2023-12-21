<?php

namespace App\Orchid\Screens;

use App\Facades\Minecraft;
use App\Jobs\MinecraftAccountCreated;
use App\Models\Member;
use App\Models\MinecraftAccount;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Fields\DateTimer;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Screen;
use Orchid\Screen\Sight;
use Orchid\Screen\TD;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class MemberAccountScreen extends Screen
{

    public $member;
    public $account;

    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        $user = Auth::user();
        $user->load(['member']);
        return [
            'member'    => $user->member,
            'accounts'  => $user->member->minecraftAccounts,
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Manage My Account';
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Button::make('Sign out')
                ->novalidate()
                ->icon('bs.box-arrow-left')
                ->route('platform.logout'),
        ];
    }

    /**
     * The screen's layout elements.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [
            Layout::block([
                Layout::legend('member', [
                    Sight::make('birthday', 'Birthday'),
                    Sight::make('pronouns', 'Pronouns'),
                    Sight::make('bio', 'About Me')
                ])
            ])
                ->title('Member Profile')
                ->description('These help us know more about our members. We ask that all players please provide their birthday and pronouns.')
                ->commands([
                    ModalToggle::make('Edit Bio')
                        ->icon('bs.pencil')
                        ->modal('editBioModal')
                        ->method('updateBio')
                ]),
            Layout::block([
                Layout::table('accounts', [
                    TD::make('name'),
                    TD::make('status')
                ])
            ])
                ->title('Minecraft Accounts')
                ->description('Add one or more Minecraft accounts to be whitelisted on the Clovercraft SMP. You must be the sole owner of these accounts.')
                ->commands([
                    ModalToggle::make('Add Minecraft Account')
                        ->icon('bs.plus')
                        ->modal('addMinecraftModal')
                        ->method('addMinecraftAccount'),
                ]),
            Layout::modal('addMinecraftModal', [
                Layout::rows([
                    Input::make('username')
                        ->title('Minecraft Account Name')
                        ->required()
                        ->help('This must be a Java edition Minecraft username.')
                ])
            ])
                ->title('Add Minecraft Account'),
            Layout::modal('editBioModal', [
                Layout::rows([
                    DateTimer::make('member.birthday')
                        ->title('Birthday')
                        ->help('Please provide your birthdate. Format is mm/dd/yyyy')
                        ->required()
                        ->allowInput()
                        ->format('m/d/Y'),
                    Select::make('member.pronouns')
                        ->title('Pronouns')
                        ->help('Please select a set of pronouns. If your pronouns are not listed, you can also type in your own')
                        ->required()
                        ->allowAdd()
                        ->options([
                            'sheher'    => 'She/Her',
                            'shethey'   => 'She/They',
                            'theythem'  => 'They/Them',
                            'hehim'     => 'He/Him',
                            'hethey'    => 'He/They',
                            'itits'     => 'It/Its',
                            'any'       => 'Any Pronouns',
                        ])
                        ->empty('--'),
                    TextArea::make('member.bio')
                        ->title('About Me')
                        ->help('Optional, tell us a bit about you!')
                        ->rows(5),
                ])
            ])
        ];
    }

    public function addMinecraftAccount(Request $request): void
    {
        $request->validate([
            'username'  => 'required',
        ]);

        if (empty($this->member->birthday) || empty($this->member->pronouns)) {
            Toast::error("Please provide your birthday and pronouns before adding an account.");
            return;
        }

        $username = $request->input('username');
        $account = Minecraft::getAccount($username);

        if (MinecraftAccount::where('uuid', $account['id'])->count() > 0) {
            Toast::error("Sorry, that account has already been added to the whitelist.");
            return;
        }

        $record = new MinecraftAccount();
        $record->name = $account['username'];
        $record->uuid = $account['id'];
        $record->status = MinecraftAccount::ACTIVE;
        $this->member->minecraftAccounts()->save($record);

        MinecraftAccountCreated::dispatchSync($record);

        Toast::success("Account saved!");
    }

    public function updateBio(Request $request): void
    {
        $request->validate([
            'member.birthday'   => 'required|date',
            'member.pronouns'   => 'required|string',
        ]);

        $this->member->bio = $request->input('member.bio', '');
        $this->member->birthday = Carbon::parse($request->input('member.birthday'));
        $this->member->pronouns = $request->input('member.pronouns');
        $this->member->save();
        Toast::success("Bio updated!");
    }
}
