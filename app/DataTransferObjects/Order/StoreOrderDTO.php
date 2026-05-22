<?php

namespace App\DataTransferObjects\Order;

use App\DataTransferObjects\DTO;
use App\Enums\Locale;

class StoreOrderDTO extends DTO
{
    public Locale $locale;

    public function __construct(
        public readonly OrderBillingDataDTO  $billing,
        public readonly OrderShippingDataDTO $shipping,

        public readonly int                  $shipping_type_id,
        public readonly int                  $payment_type_id,

        public readonly ?string              $note,
        public readonly bool                 $marketing,
        public readonly ?int                 $user_id,

        Locale|string                        $locale,
    )
    {
        $this->locale = $locale instanceof Locale ? $locale : Locale::from($locale);
    }
}
