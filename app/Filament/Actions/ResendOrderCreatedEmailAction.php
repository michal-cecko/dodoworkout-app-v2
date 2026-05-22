<?php

namespace App\Filament\Actions;

use App\Models\Order;
use App\Services\OrderService;
use Filament\Actions\Action;

class ResendOrderCreatedEmailAction extends Action
{
    public static function make(?string $name = 'resend_order_email'): static
    {
        return parent::make($name)
            ->label('Znovu poslať email')
            ->icon('heroicon-o-envelope')
            ->color('warning')
            ->requiresConfirmation()
            ->modalHeading('Znovu poslať potvrdzovací email?')
            ->modalDescription(fn (Order $record) => "Email bude odoslaný na: {$record->email}")
            ->action(fn (Order $record) => OrderService::resendOrderCreatedNotification($record));
    }
}
