<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Page extends Model
{
    use HasFactory;

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
        'meta_data',
        'is_published',
        'sort_order',
        'template',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'meta_data' => 'array',
            'is_published' => 'boolean',
            'sort_order' => 'integer',
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
    public function getMetaTitleAttribute(): string
    {
        return $this->meta_data['title'] ?? $this->title;
    }

    /**
     * Get the meta description or fallback to excerpt.
     */
    public function getMetaDescriptionAttribute(): string
    {
        return $this->meta_data['description'] ?? $this->excerpt;
    }

    /**
     * Check if the page is published.
     */
    public function isPublished(): bool
    {
        return $this->is_published;
    }

    /**
     * Scope a query to only include published pages.
     */
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
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


}