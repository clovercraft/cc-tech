<?php

namespace App\Orchid\Screens;

use App\Facades\Discord;
use App\Facades\Minecraft;
use App\Jobs\MinecraftAccountCreated;
use App\Models\Member;
use App\Models\MinecraftAccount;
use App\Orchid\Inputs\CustomInput;
use App\Orchid\Inputs\Pronouns;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
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
        $layout = [
            Layout::block([
                Layout::legend('member', [
                    Sight::make('birthday', 'Birthday'),
                    Sight::make('displayPronouns', 'Pronouns'),
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
        ];

        if ($this->member->intro_verified_at == null) {
            $layout[] = Layout::block(
                Layout::view('partials.platform.memberAccountVerifyIntro')
            )
                ->title('Discord Verification')
                ->commands([
                    Button::make('Verify Introduction')
                        ->icon('bs.eyeglass')
                        ->method('verifyDiscordIntro')
                ]);
        }

        if ($this->member->intro_verified_at == null) {
            $minecraftAddButton = ModalToggle::make('Add Minecraft Account')
                ->icon('bs.plus')
                ->modal('addMinecraftModal')
                ->method('addMinecraftAccount')
                ->disabled();
        } else {
            $minecraftAddButton = ModalToggle::make('Add Minecraft Account')
                ->icon('bs.plus')
                ->modal('addMinecraftModal')
                ->method('addMinecraftAccount');
        }

        $layout[] = Layout::block([
            Layout::table('accounts', [
                TD::make('name'),
                TD::make('status')
            ])
        ])
            ->title('Minecraft Accounts')
            ->description('Add one or more Minecraft accounts to be whitelisted on the Clovercraft SMP. You must be the sole owner of these accounts.')
            ->commands([
                $minecraftAddButton
            ]);

        $layout[] = Layout::modal('addMinecraftModal', [
            Layout::rows([
                Input::make('username')
                    ->title('Minecraft Account Name')
                    ->required()
                    ->help('This must be a Java edition Minecraft username. Minecraft usernames are case sensitive.')
            ])
        ])
            ->title('Add Minecraft Account');

        $layout[] = Layout::modal('editBioModal', [
            Layout::rows([
                CustomInput::birthday(),
                CustomInput::pronouns(),
                TextArea::make('member.bio')
                    ->title('About Me')
                    ->help('Optional, tell us a bit about you!')
                    ->rows(5),
            ])
        ]);

        return $layout;
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

        // verify birthday
        $now = now();
        $birth = Carbon::parse($this->member->birthday);
        if ($now->diffInYears($birth, true) < 18) {
            Toast::error("We're sorry. You must be 18 or older to be a member of our community.");
            return;
        }

        $username = $request->input('username');
        try {
            $account = Minecraft::getAccount($username);
        } catch (Exception $e) {
            Log::warning("Member attempted to verify invalid Minecraft username", [
                'id'        => $this->member->id,
                'member'    => $this->member->name,
                'username'  => $username
            ]);
            Toast::error("Sorry, that account could not be verified. Please try again, or open a support ticket.");
            return;
        }

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

    public function verifyDiscordIntro(): void
    {
        $verified = Discord::verify_intro_message($this->member);
        if ($verified) {
            $this->member->intro_verified_at = now();
            $this->member->save();
            Toast::success("Introduction verified!");
        } else {
            Toast::error("Sorry, we couldn't find your introduction. Contact staff for assistance.");
        }
    }
}
