<?php

namespace App\Filament\Resources\ShippingTypes\Pages;

use App\Filament\Resources\ShippingTypes\ShippingTypeResource;
use Filament\Resources\Pages\CreateRecord;

class CreateShippingType extends CreateRecord
{
    protected static string $resource = ShippingTypeResource::class;
}
