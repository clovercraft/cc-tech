<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\AsCollection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Orchid\Screen\AsSource;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Orchid\Filters\Filterable;

/**
 * Announcement model class
 *
 * @property    int         $id
 * @property    string      $title
 * @property    string      $slug
 * @property    string      $content
 * @property    Collection  $tags
 * @property    string      $status
 * @property    bool        $notice_sent
 * @property    Carbon      $deleted_at
 * @property    Carbon      $created_at
 * @property    Carbon      $updated_at
 */
class Announcement extends Model
{
    use HasFactory, AsSource, SoftDeletes, Filterable;

    protected $fillable = [
        'title',
        'slug',
        'content',
        'tags',
        'status'
    ];

    protected $casts = [
        'tags'  => AsCollection::class,
        'notice_sent'   => 'boolean'
    ];
}
