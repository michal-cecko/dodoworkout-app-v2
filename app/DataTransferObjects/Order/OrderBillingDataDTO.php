<?php

namespace App\DataTransferObjects\Order;

use App\DataTransferObjects\DTO;
use App\Enums\OrderCountry;

class OrderBillingDataDTO extends DTO
{
    public string|OrderCountry $billing_country;

    public function __construct(
        public readonly string  $email,
        public readonly ?string $billing_first_name,
        public readonly ?string $billing_last_name,
        public readonly string  $billing_address,
        public readonly string  $billing_city,
        public readonly string  $billing_zip,
        string|OrderCountry     $billing_country,
        public readonly string  $billing_phone,

        public readonly bool    $is_company,
        public readonly ?string $company_name,
        public readonly ?string $business_id,
        public readonly ?string $tax_id,
        public readonly ?string $vat_id,
    )
    {
        $this->billing_country = $billing_country instanceof OrderCountry ? $billing_country : OrderCountry::from($billing_country);
    }
}
