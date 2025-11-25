<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class ProductImage extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'product_id',
        'file_path',
        'alt_text',
        'sort_order',
        'is_primary',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
            'is_primary' => 'boolean',
        ];
    }

    /**
     * Get the product that owns the image.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the full URL for the image.
     */
    public function getUrlAttribute(): string
    {
        if ($this->isExternalUrl()) {
            return $this->file_path;
        }

        return Storage::url($this->file_path);
    }

    /**
     * Get the thumbnail URL for the image.
     */
    public function getThumbnailUrlAttribute(): string
    {
        if ($this->isExternalUrl()) {
            return $this->url;
        }

        $pathInfo = pathinfo($this->file_path);
        $thumbnailPath = $pathInfo['dirname'] . '/thumbnails/' . $pathInfo['filename'] . '-thumb.' . $pathInfo['extension'];

        if (Storage::exists($thumbnailPath)) {
            return Storage::url($thumbnailPath);
        }

        return $this->url;
    }

    /**
     * Get the medium size URL for the image.
     */
    public function getMediumUrlAttribute(): string
    {
        if ($this->isExternalUrl()) {
            return $this->url;
        }

        $pathInfo = pathinfo($this->file_path);
        $mediumPath = $pathInfo['dirname'] . '/medium/' . $pathInfo['filename'] . '-medium.' . $pathInfo['extension'];

        if (Storage::exists($mediumPath)) {
            return Storage::url($mediumPath);
        }

        return $this->url;
    }

    /**
     * Scope a query to only include primary images.
     */
    public function scopePrimary($query)
    {
        return $query->where('is_primary', true);
    }

    protected function isExternalUrl(): bool
    {
        return is_string($this->file_path)
            && preg_match('#^https?://#i', $this->file_path) === 1;
    }

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($image) {
            if ($image->isExternalUrl()) {
                return;
            }

            // Delete the actual file when the model is deleted
            if (Storage::exists($image->file_path)) {
                Storage::delete($image->file_path);
            }

            // Delete thumbnails and medium images
            $pathInfo = pathinfo($image->file_path);
            $thumbnailPath = $pathInfo['dirname'] . '/thumbnails/' . $pathInfo['filename'] . '-thumb.' . $pathInfo['extension'];
            $mediumPath = $pathInfo['dirname'] . '/medium/' . $pathInfo['filename'] . '-medium.' . $pathInfo['extension'];

            if (Storage::exists($thumbnailPath)) {
                Storage::delete($thumbnailPath);
            }
            
            if (Storage::exists($mediumPath)) {
                Storage::delete($mediumPath);
            }
        });
    }
}