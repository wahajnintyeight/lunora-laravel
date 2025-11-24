<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CartItem extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'cart_id',
        'product_id',
        'product_variant_id',
        'quantity',
        'unit_price_pkr',
        'total_price_pkr',
        'customizations',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'quantity' => 'integer',
            'unit_price_pkr' => 'integer',
            'total_price_pkr' => 'integer',
            'customizations' => 'array',
        ];
    }

    /**
     * Get the cart that owns the item.
     */
    public function cart(): BelongsTo
    {
        return $this->belongsTo(Cart::class);
    }

    /**
     * Get the product for the item.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the product variant for the item.
     */
    public function variant(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }

    /**
     * Get the effective product (variant or product).
     */
    public function getEffectiveProductAttribute()
    {
        return $this->variant ?? $this->product;
    }

    /**
     * Get the item name (includes variant options if applicable).
     */
    public function getNameAttribute(): string
    {
        if ($this->variant) {
            return $this->variant->title;
        }
        return $this->product->name;
    }

    /**
     * Get the formatted unit price.
     */
    public function getFormattedUnitPriceAttribute(): string
    {
        return 'PKR ' . number_format($this->unit_price_pkr / 100, 2);
    }

    /**
     * Get the formatted total price.
     */
    public function getFormattedTotalPriceAttribute(): string
    {
        return 'PKR ' . number_format($this->total_price_pkr / 100, 2);
    }

    /**
     * Get the primary image for the item.
     */
    public function getImageAttribute(): ?ProductImage
    {
        return $this->product->images()->primary()->first() ?? $this->product->images()->first();
    }

    /**
     * Check if the item has customizations.
     */
    public function hasCustomizations(): bool
    {
        return !empty($this->customizations);
    }

    /**
     * Get a specific customization value.
     */
    public function getCustomization(string $key): ?string
    {
        return $this->customizations[$key] ?? null;
    }

    /**
     * Get formatted customizations for display.
     */
    public function getFormattedCustomizationsAttribute(): array
    {
        if (!$this->customizations) {
            return [];
        }

        $formatted = [];
        foreach ($this->customizations as $key => $value) {
            $formatted[] = ucfirst($key) . ': ' . $value;
        }

        return $formatted;
    }

    /**
     * Check if the item is still available (product active and in stock).
     */
    public function isAvailable(): bool
    {
        if (!$this->product->is_active) {
            return false;
        }

        if ($this->variant) {
            return $this->variant->is_active && $this->variant->stock >= $this->quantity;
        }

        return $this->product->stock >= $this->quantity;
    }

    /**
     * Get the maximum available quantity for this item.
     */
    public function getMaxAvailableQuantity(): int
    {
        if ($this->variant) {
            return $this->variant->stock;
        }
        return $this->product->stock;
    }

    /**
     * Update the total price based on quantity and unit price.
     */
    public function updateTotalPrice(): void
    {
        $this->total_price_pkr = $this->quantity * $this->unit_price_pkr;
        $this->save();
    }

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($cartItem) {
            // Automatically calculate total price
            $cartItem->total_price_pkr = $cartItem->quantity * $cartItem->unit_price_pkr;
        });
    }
}