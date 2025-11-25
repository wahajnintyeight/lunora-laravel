<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
class Product extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'sku',
        'description',
        'material',
        'brand',
        'price_pkr',
        'compare_at_price_pkr',
        'stock',
        'weight',
        'dimensions',
        'is_active',
        'is_featured',
        'meta_title',
        'meta_description',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'price_pkr' => 'integer',
            'compare_at_price_pkr' => 'integer',
            'stock' => 'integer',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'weight' => 'decimal:2',
        ];
    }

    /**
     * Get the category that owns the product.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the images for the product.
     */
    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    /**
     * Get the featured image for the product.
     */
    public function getFeaturedImageAttribute(): ?string
    {
        $primaryImage = $this->images()->where('is_primary', true)->first();
        if ($primaryImage) {
            return $primaryImage->url;
        }
        
        $firstImage = $this->images()->first();
        return $firstImage ? $firstImage->url : null;
    }

    /**
     * Get the options for the product.
     */
    public function options(): HasMany
    {
        return $this->hasMany(ProductOption::class);
    }

    /**
     * Get the variants for the product.
     */
    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }

    /**
     * Get the cart items for the product.
     */
    public function cartItems(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    /**
     * Get the order items for the product.
     */
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Get the formatted price attribute.
     */
    public function getFormattedPriceAttribute(): string
    {
        return 'PKR ' . number_format($this->price_pkr / 100, 2);
    }

    /**
     * Get the formatted compare at price attribute.
     */
    public function getFormattedCompareAtPriceAttribute(): string
    {
        if (!$this->compare_at_price_pkr) {
            return '';
        }
        return 'PKR ' . number_format($this->compare_at_price_pkr / 100, 2);
    }

    /**
     * Check if the product is in stock.
     */
    public function isInStock(): bool
    {
        if ($this->hasVariants()) {
            return $this->variants()->where('stock', '>', 0)->exists();
        }
        return $this->stock > 0;
    }

    /**
     * Check if the product has variants.
     */
    public function hasVariants(): bool
    {
        return $this->variants()->count() > 0;
    }

    /**
     * Get the available stock for the product.
     */
    public function getAvailableStock(): int
    {
        if ($this->hasVariants()) {
            return $this->variants()->sum('stock');
        }
        return $this->stock;
    }

    /**
     * Scope a query to only include active products.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include featured products.
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope a query to only include in-stock products.
     */
    public function scopeInStock($query)
    {
        return $query->where(function ($q) {
            $q->where('stock', '>', 0)
              ->orWhereHas('variants', function ($variant) {
                  $variant->where('stock', '>', 0);
              });
        });
    }
}