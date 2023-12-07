<?php

namespace App\Orchid\Screens\Members;

use App\Facades\Discord;
use App\Models\Member;
use App\Orchid\Layouts\Members\MemberListTable;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Fields\DateTimer;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class MembersScreen extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'members' => Member::all()
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Member Management';
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        $bar = [];
        if (Auth::user()->hasAccess('member.create')) {
            $bar[] = ModalToggle::make('Register Member')
                ->modal('addMemberModal')
                ->icon('bs.plus')
                ->method('saveMember');
        }
        return $bar;
    }

    /**
     * The screen's layout elements.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [
            MemberListTable::class,
            Layout::modal('addMemberModal', [
                Layout::view('partials.platform.registerMemberWarning'),
                Layout::rows([
                    Input::make('name')
                        ->title('Member Name')
                        ->required(),
                    DateTimer::make('birthday')
                        ->title('Member Birthday')
                        ->format('m/d/Y')
                        ->allowInput()
                        ->required(),
                    Input::make('pronouns')
                        ->title('Member Pronouns')
                        ->required(),
                    Input::make('source')
                        ->title('Referral Source')
                ])
            ])->title('Register Member')
        ];
    }

    public function saveMember(Request $request)
    {
        $request->validate([
            'name'      => 'required|string',
            'birthday'  => 'required|date',
            'pronouns'  => 'required|string'
        ]);

        // validate birthday
        $birthday = $request->input('birthday');
        $birthday = Carbon::parse($birthday);
        $delta = now()->diffInYears($birthday);
        if ($delta < 18) {
            Alert::error('Members may not be under 18 years old.');
            return;
        }

        $member = new Member();
        $member->name = $request->input('name');
        $member->birthday = $birthday->toDateTimeString();
        $member->pronouns = $request->input('pronouns');
        $member->source = $request->input('source', 'admin');
        $member->admin_added = true;
        $member->save();

        Alert::success('Member created!');
    }
}
