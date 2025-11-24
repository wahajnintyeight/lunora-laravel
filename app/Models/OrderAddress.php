<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderAddress extends Model
{
    use HasFactory;

    /**
     * Address type constants.
     */
    const TYPE_SHIPPING = 'shipping';
    const TYPE_BILLING = 'billing';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'order_id',
        'type',
        'first_name',
        'last_name',
        'company',
        'address_line_1',
        'address_line_2',
        'city',
        'state',
        'postal_code',
        'country',
        'phone',
    ];

    /**
     * Get the order that owns the address.
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get the full name.
     */
    public function getFullNameAttribute(): string
    {
        return trim($this->first_name . ' ' . $this->last_name);
    }

    /**
     * Get the formatted address.
     */
    public function getFormattedAddressAttribute(): string
    {
        $address = [];
        
        if ($this->company) {
            $address[] = $this->company;
        }
        
        $address[] = $this->full_name;
        $address[] = $this->address_line_1;
        
        if ($this->address_line_2) {
            $address[] = $this->address_line_2;
        }
        
        $cityStatePostal = trim($this->city . ', ' . $this->state . ' ' . $this->postal_code);
        if ($cityStatePostal !== ', ') {
            $address[] = $cityStatePostal;
        }
        
        if ($this->country) {
            $address[] = $this->country;
        }
        
        return implode("\n", array_filter($address));
    }

    /**
     * Get the formatted address as a single line.
     */
    public function getFormattedAddressInlineAttribute(): string
    {
        return str_replace("\n", ', ', $this->formatted_address);
    }

    /**
     * Check if this is a shipping address.
     */
    public function isShipping(): bool
    {
        return $this->type === self::TYPE_SHIPPING;
    }

    /**
     * Check if this is a billing address.
     */
    public function isBilling(): bool
    {
        return $this->type === self::TYPE_BILLING;
    }

    /**
     * Scope a query to only include shipping addresses.
     */
    public function scopeShipping($query)
    {
        return $query->where('type', self::TYPE_SHIPPING);
    }

    /**
     * Scope a query to only include billing addresses.
     */
    public function scopeBilling($query)
    {
        return $query->where('type', self::TYPE_BILLING);
    }

    /**
     * Get all possible address types.
     */
    public static function getTypes(): array
    {
        return [
            self::TYPE_SHIPPING => 'Shipping',
            self::TYPE_BILLING => 'Billing',
        ];
    }
}