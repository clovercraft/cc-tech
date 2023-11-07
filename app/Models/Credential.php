<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Orchid\Screen\AsSource;

/**
 * Credential Model
 *
 * @property int            $id
 * @property string         $name
 * @property string|null    $username
 * @property string|null    $password
 * @property string|null    $notes
 */
class Credential extends Model
{
    use HasFactory, SoftDeletes, AsSource;

    protected $casts = [
        'url'       => 'encrypted',
        'username'  => 'encrypted',
        'password'  => 'encrypted',
        'notes'     => 'encrypted',
    ];
}
