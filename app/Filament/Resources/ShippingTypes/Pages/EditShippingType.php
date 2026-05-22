<?php

namespace App\Filament\Resources\ShippingTypes\Pages;

use App\Filament\Resources\ShippingTypes\ShippingTypeResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditShippingType extends EditRecord
{
    protected static string $resource = ShippingTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
