<?php

namespace App\Models\CMS;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Orchid\Screen\AsSource;

/**
 * CMS Page Model Class
 *
 * @property    int             $id
 * @property    string          $title
 * @property    string|null     $subtitle
 * @property    string|null     $cover_img
 * @property    string|null     $content
 * @property    User            $user
 */
class Page extends Model
{
    use HasFactory, SoftDeletes, AsSource;
}
