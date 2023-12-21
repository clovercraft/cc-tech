<?php

namespace App\Orchid\Inputs;

use Orchid\Screen\Fields\DateTimer;
use Orchid\Screen\Fields\Select;

class CustomInput
{
    public static function pronouns(string $target = 'member.pronouns')
    {
        return Select::make($target)
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
            ->empty('--');
    }

    public static function birthday(string $target = 'member.birthday')
    {
        return DateTimer::make($target)
            ->title('Birthday')
            ->help('Please provide your birthdate. Format is mm/dd/yyyy')
            ->required()
            ->allowInput()
            ->format('m/d/Y');
    }
}
