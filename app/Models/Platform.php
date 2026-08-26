<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Platform extends Model
{
    use HasFactory;

    protected $fillable = [
        'parent_id',
        'name',
        'icon',
        'slug',
        'h1',
        'description',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'meta_robots',
        'content',
        'status',
        'show_in_navbar',
        'show_in_footer'
    ];

    // Children platforms (sub-platforms)
    public function children()
    {
        return $this->hasMany(Platform::class, 'parent_id')->where('status', 'active');
    }

    // Parent platform
    public function parent()
    {
        return $this->belongsTo(Platform::class, 'parent_id');
    }
}
