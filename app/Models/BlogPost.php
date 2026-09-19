<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlogPost extends Model
{
    protected $fillable = ['title', 'slug', 'category', 'excerpt', 'meta_title', 'meta_description', 'content', 'image', 'image_alt', 'read_minutes', 'is_published', 'published_at'];
    protected $casts = ['is_published' => 'boolean', 'published_at' => 'datetime'];

    public function scopePublished($query)
    {
        return $query->where('is_published', true)->whereNotNull('published_at')->where('published_at', '<=', now());
    }

    // ── Compatibility with blog/show.blade.php ────────────────────────────────

    public function getAuthorNameAttribute()
    {
        return 'Admin';
    }

    public function getFeaturedImageAttribute()
    {
        return $this->image;
    }

    public function getReadingTimeAttribute()
    {
        return ($this->read_minutes ?? 5) . ' min read';
    }

    public function getMetaRobotsAttribute()
    {
        return 'index, follow';
    }

    public function getMetaKeywordsAttribute()
    {
        return null;
    }

    public function getSchemaAttribute()
    {
        return null;
    }

    public function getTagsAttribute()
    {
        return $this->category;
    }

    public function getStatusAttribute()
    {
        return $this->is_published ? 1 : 0;
    }

    /**
     * Render content — BlogPost stores raw HTML, so just return it.
     * Blog model stores EditorJS JSON, we keep this compatible.
     */
    public function renderContent(): string
    {
        return $this->content ?? '';
    }
}
