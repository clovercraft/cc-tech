<?php

namespace App\Orchid\Screens;

use App\Models\Credential;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\Screen;
use Orchid\Screen\TD;
use Orchid\Support\Color;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class CredentialsScreen extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        return ['credentials' => Credential::all()];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Credentials';
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        $bar = [];

        if (Auth::user()->hasAccess('creds.create')) {
            $bar[] = ModalToggle::make('New Credential')
                ->modal('addCredentialModal')
                ->method('create')
                ->icon('bs.plus');
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
            Layout::table('credentials', [
                TD::make('name'),
                TD::make('')
                    ->render(function ($credential) {
                        $buttons = [
                            ModalToggle::make('view')
                                ->modal('viewCredentialModal')
                                ->asyncParameters(['id' => $credential->id])
                                ->icon('bs.eye'),
                        ];

                        if (Auth::user()->hasAccess('creds.edit')) {
                            $buttons[] = ModalToggle::make('edit')
                                ->modal('editCredentialModal')
                                ->asyncParameters(['id' => $credential->id])
                                ->method('update')
                                ->icon('bs.');
                        }

                        if (Auth::user()->hasAccess('creds.delete')) {
                            $buttons[] = Button::make('delete')
                                ->type(Color::DANGER)
                                ->icon('bs.trash')
                                ->method('delete', ['credential' => $credential->id]);
                        }
                        return Group::make($buttons);
                    })
            ]),
            Layout::modal('addCredentialModal', [
                Layout::rows([
                    Input::make('name')
                        ->title('Name')
                        ->required(),
                    Input::make('url')
                        ->title('Login Location')
                        ->help('The URL or other context for using this credential')
                        ->required(),
                    Input::make('username')
                        ->title('Username'),
                    Input::make('password')
                        ->title('Password'),
                    TextArea::make('notes')
                        ->title('Notes')
                        ->help('Relevant information for using this login')
                ])
            ])->title('Create Credential'),
            Layout::modal('viewCredentialModal', [
                Layout::rows([
                    Input::make('credential.name')
                        ->title('Name')
                        ->readonly(),
                    Input::make('credential.url')
                        ->title('Login Location')
                        ->disabled(),
                    Input::make('credential.username')
                        ->title('Username')
                        ->disabled(),
                    Input::make('credential.password')
                        ->title('Password')
                        ->disabled(),
                    TextArea::make('credential.notes')
                        ->title('Notes')
                        ->disabled(),
                ])
            ])->title('View Credential')
                ->withoutApplyButton()
                ->async('asyncLoadCredential'),
            Layout::modal('editCredentialModal', [
                Layout::rows([
                    Input::make('credential.name')
                        ->title('Name')
                        ->required(),
                    Input::make('credential.url')
                        ->title('Login Location')
                        ->required(),
                    Input::make('credential.username')
                        ->title('Username'),
                    Input::make('credential.password')
                        ->title('Password'),
                    TextArea::make('credential.notes')
                        ->title('Notes'),
                    Input::make('credential.id')
                        ->hidden()
                ])
            ])->title('Edit Credential')
                ->async('asyncLoadCredential')
        ];
    }

    public function asyncLoadCredential(int $id)
    {
        return [
            'credential' => Credential::find($id)
        ];
    }

    public function create(Request $request)
    {
        $request->validate([
            'name'  => 'required',
            'url'   => 'required'
        ]);

        $cred = new Credential();
        $cred->name = $request->input('name');
        $cred->url  = $request->input('url');
        $cred->username = $request->input('username');
        $cred->password = $request->input('password');
        $cred->notes = $request->input('notes');
        $cred->save();
    }

    public function update(Request $request)
    {
        $request->validate([
            'credential.name'  => 'required',
            'credential.url'   => 'required',
            'credential.id'    => 'required|int'
        ]);

        $cred = Credential::find($request->input('credential.id'));
        $cred->name = $request->input('credential.name');
        $cred->url  = $request->input('credential.url');
        $cred->username = $request->input('credential.username');
        $cred->password = $request->input('credential.password');
        $cred->notes = $request->input('credential.notes');
        $cred->save();
        Toast::success('Credential updated.');
    }

    public function delete($credential)
    {
        $cred = Credential::find($credential);
        $cred->delete();
        Toast::success('Credential has been deleted.');
    }
}
