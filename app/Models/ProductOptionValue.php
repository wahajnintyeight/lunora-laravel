<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductOptionValue extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'product_option_id',
        'value',
        'price_adjustment_pkr',
        'sort_order',
        'is_default',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'price_adjustment_pkr' => 'integer',
            'sort_order' => 'integer',
            'is_default' => 'boolean',
        ];
    }

    /**
     * Get the option that owns the value.
     */
    public function option(): BelongsTo
    {
        return $this->belongsTo(ProductOption::class, 'product_option_id');
    }

    /**
     * Get the formatted price adjustment.
     */
    public function getFormattedPriceAdjustmentAttribute(): string
    {
        if ($this->price_adjustment_pkr === 0) {
            return '';
        }

        $amount = abs($this->price_adjustment_pkr);
        $formatted = 'PKR ' . number_format($amount / 100, 2);
        
        return $this->price_adjustment_pkr > 0 ? '+' . $formatted : '-' . $formatted;
    }

    /**
     * Scope a query to only include default values.
     */
    public function scopeDefault($query)
    {
        return $query->where('is_default', true);
    }
}