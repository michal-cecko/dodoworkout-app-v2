<?php

namespace App\DataTransferObjects\Order;

use App\DataTransferObjects\DTO;
use App\Enums\OrderCountry;

class OrderShippingDataDTO extends DTO
{
    public null|string|OrderCountry $shipping_country;
    public function __construct(
        public readonly bool $is_shipping_address,
        public readonly ?string $shipping_first_name,
        public readonly ?string $shipping_last_name,
        public readonly ?string $shipping_address,
        public readonly ?string $shipping_city,
        public readonly ?string $shipping_zip_code,
        null|string|OrderCountry $shipping_country,
        public readonly ?string $shipping_phone,
    )
    {
        if(!empty($shipping_country)) {
            $this->shipping_country = $shipping_country instanceof OrderCountry ? $shipping_country : OrderCountry::from($shipping_country);
        }
    }
}
