<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PageSeo extends Model
{
    use HasFactory;

    protected $table = 'page_seo_settings';

    protected $fillable = [
        'page_name',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'meta_robots',
    ];

    public static function getFor($pageName)
    {
        return static::where('page_name', $pageName)->first();
    }
}
