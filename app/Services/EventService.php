<?php

namespace App\Services;

use App\DataTransferObjects\Order\StoreOrderDTO;
use App\Enums\OrderCountry;
use App\Misc\MorphMap;
use App\Models\Event;
use App\Models\FormSubmission;
use App\Models\Order;
use App\Models\PaymentType;
use App\Models\ShippingType;
use App\Notifications\OrderCreated;
use Exception;
use Illuminate\Support\Collection;

class EventService
{
}
