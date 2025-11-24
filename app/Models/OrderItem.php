<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'order_id',
        'product_id',
        'product_variant_id',
        'product_name',
        'product_sku',
        'variant_options',
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
            'variant_options' => 'array',
            'customizations' => 'array',
        ];
    }

    /**
     * Get the order that owns the item.
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
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
     * Get the item display name.
     */
    public function getDisplayNameAttribute(): string
    {
        $name = $this->product_name;
        
        if ($this->variant_options) {
            $optionStrings = [];
            foreach ($this->variant_options as $option => $value) {
                $optionStrings[] = "$option: $value";
            }
            $name .= ' (' . implode(', ', $optionStrings) . ')';
        }
        
        return $name;
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
     * Get the primary image for the item (from the product).
     */
    public function getImageAttribute(): ?ProductImage
    {
        if ($this->product) {
            return $this->product->images()->primary()->first() ?? $this->product->images()->first();
        }
        return null;
    }

    /**
     * Check if the item has variant options.
     */
    public function hasVariantOptions(): bool
    {
        return !empty($this->variant_options);
    }

    /**
     * Get formatted variant options for display.
     */
    public function getFormattedVariantOptionsAttribute(): array
    {
        if (!$this->variant_options) {
            return [];
        }

        $formatted = [];
        foreach ($this->variant_options as $option => $value) {
            $formatted[] = ucfirst($option) . ': ' . $value;
        }

        return $formatted;
    }
}