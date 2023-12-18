<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;

/**
 * Member Asset Model Class
 *
 * @property int            $id
 * @property string         $name
 * @property string         $uuid
 * @property string         $visibility
 * @property string|null    $description
 * @property Member         $member
 * @property Carbon         $created_at
 * @property Carbon         $updated_at
 * @property Carbon         $deleted_at
 */
class MemberAsset extends Model
{
    use HasFactory, AsSource, Filterable;
}
