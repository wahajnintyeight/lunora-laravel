<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Page extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'slug',
        'content',
        'excerpt',
        'meta_title',
        'meta_description',
        'is_published',
        'published_at',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
            'published_at' => 'datetime',
        ];
    }

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * Get the URL for the page.
     */
    public function getUrlAttribute(): string
    {
        return route('pages.show', $this->slug);
    }

    /**
     * Get the excerpt or truncated content.
     */
    public function getExcerptAttribute($value): string
    {
        if ($value) {
            return $value;
        }

        // Generate excerpt from content if not set
        $content = strip_tags($this->content);
        return strlen($content) > 200 ? substr($content, 0, 200) . '...' : $content;
    }

    /**
     * Get the meta title or fallback to title.
     */
    public function getMetaTitleAttribute($value): string
    {
        return $value ?: $this->title;
    }

    /**
     * Get the meta description or fallback to excerpt.
     */
    public function getMetaDescriptionAttribute($value): string
    {
        return $value ?: $this->excerpt;
    }

    /**
     * Check if the page is published.
     */
    public function isPublished(): bool
    {
        return $this->is_published && 
               ($this->published_at === null || $this->published_at->isPast());
    }

    /**
     * Scope a query to only include published pages.
     */
    public function scopePublished($query)
    {
        return $query->where('is_published', true)
            ->where(function ($q) {
                $q->whereNull('published_at')
                  ->orWhere('published_at', '<=', now());
            });
    }

    /**
     * Scope a query to only include draft pages.
     */
    public function scopeDraft($query)
    {
        return $query->where('is_published', false);
    }

    /**
     * Scope a query by slug.
     */
    public function scopeBySlug($query, string $slug)
    {
        return $query->where('slug', $slug);
    }

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($page) {
            if (empty($page->published_at) && $page->is_published) {
                $page->published_at = now();
            }
        });

        static::updating(function ($page) {
            if ($page->is_published && $page->isDirty('is_published') && empty($page->published_at)) {
                $page->published_at = now();
            }
        });
    }
}