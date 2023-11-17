<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Orchid\Screen\AsSource;
use Illuminate\Support\Carbon;

/**
 * Member model
 *
 * @property int            $id
 * @property string         $name
 * @property string|null    $discord_id
 * @property Carbon         $birthday
 * @property string         $pronouns
 * @property string|null    $bio
 * @property string|null    $avatar
 * @property string|null    $source
 * @property Carbon         $deleted_at
 * @property Carbon         $updated_at
 * @property Carbon         $created_at
 */
class Member extends Model
{
    use HasFactory, AsSource, SoftDeletes;

    protected $casts = [
        'birthday' => 'Carbon',
    ];
}
