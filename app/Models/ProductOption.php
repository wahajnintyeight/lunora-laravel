<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductOption extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'product_id',
        'name',
        'type',
        'is_required',
        'sort_order',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_required' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    /**
     * Get the product that owns the option.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the values for the option.
     */
    public function values(): HasMany
    {
        return $this->hasMany(ProductOptionValue::class)->orderBy('sort_order');
    }

    /**
     * Scope a query to only include required options.
     */
    public function scopeRequired($query)
    {
        return $query->where('is_required', true);
    }

    /**
     * Get the option type display name.
     */
    public function getTypeDisplayAttribute(): string
    {
        return match($this->type) {
            'select' => 'Dropdown',
            'radio' => 'Radio Buttons',
            'checkbox' => 'Checkboxes',
            'text' => 'Text Input',
            'textarea' => 'Text Area',
            default => ucfirst($this->type),
        };
    }
}