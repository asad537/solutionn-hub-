<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    protected $fillable = ['key', 'value'];

    public static function values()
    {
        return static::query()->pluck('value', 'key')->all();
    }
}
