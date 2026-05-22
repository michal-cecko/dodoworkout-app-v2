<?php

namespace App\Filament\Resources\ShippingTypes\Pages;

use App\Filament\Resources\ShippingTypes\ShippingTypeResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListShippingTypes extends ListRecords
{
    protected static string $resource = ShippingTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
