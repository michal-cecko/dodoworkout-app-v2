<?php

namespace App\Filament\Resources\Orders\Tables;

use App\Filament\Actions\ResendOrderCreatedEmailAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class OrdersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('full_order_number')
                    ->label('Číslo')
                    ->sortable(['order_number'])
                    ->searchable(['order_number']),

                TextColumn::make('full_billing_name')
                    ->label('Objednávateľ')
                    ->sortable(['billing_last_name', 'company_name', 'billing_first_name'])
                    ->searchable(['billing_last_name', 'company_name', 'billing_first_name']),

                TextColumn::make('status')
                    ->label('Stav')
                    ->badge()
                    ->getStateUsing(fn ($record) => $record->status->translation())
                    ->color(fn ($record): string => $record->status->color())
                    ->icon(fn ($record): string => $record->status->icon()),

                TextColumn::make('total_with_vat')
                    ->label('Celkom s DPH')
                    ->money('EUR')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Dátum')
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->searchable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                //
            ])
            ->recordActions([
                ResendOrderCreatedEmailAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
