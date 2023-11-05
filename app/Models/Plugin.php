<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * Plugin model class
 *
 * @property int         $id
 * @property string      $name
 * @property string      $description
 * @property string      $source
 * @property string      $latest
 * @property bool        $in_use
 * @property Carbon      $created_at
 * @property Carbon      $updated_at
 * @property Carbon|null $deleted_at
 */
class Plugin extends Model
{
    use HasFactory, SoftDeletes;
}
