<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductVariant extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'product_id',
        'sku',
        'price_pkr',
        'compare_at_price_pkr',
        'stock',
        'weight',
        'options_json',
        'is_active',
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
            'weight' => 'decimal:2',
            'options_json' => 'array',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Get the product that owns the variant.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the cart items for the variant.
     */
    public function cartItems(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    /**
     * Get the order items for the variant.
     */
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Get the effective price (variant price or product price).
     */
    public function getEffectivePriceAttribute(): int
    {
        return $this->price_pkr ?? $this->product->price_pkr;
    }

    /**
     * Get the formatted effective price.
     */
    public function getFormattedEffectivePriceAttribute(): string
    {
        return 'PKR ' . number_format($this->effective_price / 100, 2);
    }

    /**
     * Get the formatted compare at price.
     */
    public function getFormattedCompareAtPriceAttribute(): string
    {
        $comparePrice = $this->compare_at_price_pkr ?? $this->product->compare_at_price_pkr;
        
        if (!$comparePrice) {
            return '';
        }
        
        return 'PKR ' . number_format($comparePrice / 100, 2);
    }

    /**
     * Get the variant title based on options.
     */
    public function getTitleAttribute(): string
    {
        if (!$this->options_json) {
            return $this->product->name;
        }

        $optionStrings = [];
        foreach ($this->options_json as $option => $value) {
            $optionStrings[] = "$option: $value";
        }

        return $this->product->name . ' (' . implode(', ', $optionStrings) . ')';
    }

    /**
     * Check if the variant is in stock.
     */
    public function isInStock(): bool
    {
        return $this->stock > 0;
    }

    /**
     * Get a specific option value.
     */
    public function getOption(string $optionName): ?string
    {
        return $this->options_json[$optionName] ?? null;
    }

    /**
     * Check if variant matches given options.
     */
    public function matchesOptions(array $options): bool
    {
        if (!$this->options_json) {
            return empty($options);
        }

        foreach ($options as $key => $value) {
            if (($this->options_json[$key] ?? null) !== $value) {
                return false;
            }
        }

        return true;
    }

    /**
     * Scope a query to only include active variants.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include in-stock variants.
     */
    public function scopeInStock($query)
    {
        return $query->where('stock', '>', 0);
    }

    /**
     * Scope a query to find variants by options.
     */
    public function scopeByOptions($query, array $options)
    {
        foreach ($options as $key => $value) {
            $query->whereJsonContains('options_json->' . $key, $value);
        }
        
        return $query;
    }
}