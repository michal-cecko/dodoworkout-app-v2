<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'orderable_id',
        'orderable_type',
        'quantity',
        'name',
        'price_per_unit',
        'discount_amount_per_unit',
        'total_no_vat',
        'vat_percentage',
        'vat_amount',
        'total_with_vat',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function orderable(): MorphTo {
        return $this->morphTo();
    }

    public function getFinalPricePerUnitAttribute(): float
    {
        return $this->price_per_unit - $this->discount_amount_per_unit;
    }

    public function formSubmission(): HasOne
    {
        return $this->hasOne(FormSubmission::class, 'order_item_id');
    }
}
