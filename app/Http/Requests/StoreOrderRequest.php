<?php

namespace App\Http\Requests;

use App\Enums\Locale;
use App\Enums\OrderableType;
use App\Enums\OrderCountry;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return self::getRules();
    }

    public static function getRules(): array
    {
        return [
            'email' => 'required|email:rfc,dns|max:255',
            'billing_first_name' => 'required|string|max:255',
            'billing_last_name' => 'required|string|max:255',
            'billing_phone' => 'required|string|max:255',
            'billing_address' => ['required', 'string', 'max:255'],
            'billing_city' => ['required', 'string', 'max:255'],
            'billing_zip' => ['required', 'string', 'max:255'],
            'billing_country' => ['required', Rule::enum(OrderCountry::class)],

            'is_company' => 'required|boolean',
            'company_name' => 'required_if:is_company,1|string|max:200',
            'business_id' => 'required_if:is_company,1|string|max:200',
            'tax_id' => 'sometimes|nullable|string|max:200',
            'vat_id' => 'sometimes|nullable|string|max:200',

            'is_shipping_address' => 'required|boolean',
            'shipping_name' => 'required_if:is_shipping_address,1|string|max:255',
            'shipping_surname' => 'required_if:is_shipping_address,1|string|max:255',
            'shipping_email' => 'required_if:is_shipping_address,1|email:rfc,dns|max:255',
            'shipping_phone' => 'required_if:is_shipping_address,1|string|max:255',
            'shipping_address' => 'required_if:is_shipping_address,1|string|max:255',
            'shipping_city' => 'required_if:is_shipping_address,1|string|max:255',
            'shipping_zip_code' => 'required_if:is_shipping_address,1|string|max:255',
            'shipping_country' => ['required_if:is_shipping_address,1', Rule::enum(OrderCountry::class)],

            'locale' => ["required", Rule::enum(Locale::class)],
            'shipping_type_id' => 'required|integer|exists:shipping_types,id',
            'payment_type_id' => 'required|integer|exists:payment_types,id',

            'note' => 'sometimes|nullable|string|max:200',

            'products' => 'required|array|min:1',
            'products.*.id' => ['required', 'integer'],
            'products.*.type' => ['required', Rule::enum(OrderableType::class)],
            'products.*.quantity' => 'required_if:products,array|integer',

            'terms' => "required|boolean|in:1",
            'marketing' => "required|boolean",
        ];
    }
}
