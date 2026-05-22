<?php

namespace App\Models;

use App\Enums\OrderCountry;
use App\Enums\OrderStatus;
use App\Enums\OrderType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Notifications\Notifiable;

class Order extends Model
{
    use Notifiable;

    protected $fillable = [
        'type',
        'email',

        'company_name',
        'billing_first_name',
        'billing_last_name',
        'billing_address',
        'billing_city',
        'billing_zip',
        'billing_country',
        'billing_phone',

        'is_company',
        'business_id',
        'tax_id',
        'vat_id',

        'status',
        'order_number',

        'subtotal',
        'discount_amount',
        'shipping_type_price',
        'payment_type_price',
        'total_no_vat',
        'vat_percentage',
        'vat_amount',
        'total_with_vat',

        'user_id',
        'locale',
        'shipping_type_id',

        'is_shipping_address',
        'shipping_first_name',
        'shipping_last_name',
        'shipping_address',
        'shipping_city',
        'shipping_zip',
        'shipping_country',
        'shipping_phone',

        'note',

        'payment_type_label',
        'payment_type_id',
        'shipping_type_label',
        'shipping_type_id',

        'canceled_at',
        'paid_at',
    ];

    protected $casts = [
        'type' => OrderType::class,
        'status' => OrderStatus::class,
        'billing_country' => OrderCountry::class,
        'shipping_country' => OrderCountry::class,
    ];

    public function shippingType(): BelongsTo
    {
        return $this->belongsTo(ShippingType::class);
    }

    public function paymentType(): BelongsTo
    {
        return $this->belongsTo(PaymentType::class);
    }

    public function formSubmissions(): HasMany
    {
        return $this->hasMany(FormSubmission::class, 'order_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getFullBillingNameAttribute(): string
    {
        return $this->is_company ? $this->company_name : $this->billing_first_name . ' ' . $this->billing_last_name;
    }

    public function getShouldPayVatAttribute(): bool
    {
        return !empty($this->vat_id) && config("order.base_billing_country") !== $this->billing_country;
    }

    public function getFullOrderNumberAttribute(): string
    {
        return $this->created_at->format('Y') . str_pad($this->order_number, 4, '0', STR_PAD_LEFT);
    }

    public function routeNotificationForMail()
    {
        return $this->email;
    }
}
