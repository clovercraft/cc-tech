<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * AppSetting Model Class
 *
 * @property string $slug
 * @property string $label
 * @property string $type
 * @property string $value
 */
class AppSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug',
        'label',
        'type',
        'value'
    ];

    public static function access(string $slug): string
    {
        $setting = AppSetting::where('slug', $slug)->first();
        if (empty($setting)) {
            return '';
        }
        return $setting->value;
    }
}
