<?php

namespace App\Filament\Resources\Orders\Schemas;

use App\Enums\OrderCountry;
use App\Enums\OrderStatus;
use App\Models\Event;
use App\Models\PaymentType;
use App\Models\ShippingType;
use App\Services\OrderService;
use Filament\Actions\Action;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\MorphToSelect;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;

class OrderForm
{
    public static function configure(Schema $schema): Schema
    {
        $shippingTypes = ShippingType::select(['name', 'id', 'price'])->get();
        $paymentTypes = PaymentType::select(['name', 'id', 'price'])->get();

        return $schema
            ->columns(12)
            ->components([
                Grid::make(12)->schema([
                    Grid::make(1)->columnSpan(8)->schema([
                        Section::make('Fakturačné údaje')
                            ->collapsible()
                            ->columns(3)
                            ->schema([
                                TextInput::make('billing_first_name')->label('Meno')->required(),
                                TextInput::make('billing_last_name')->label('Priezvisko')->required(),
                                TextInput::make('email')->label('Email')->email()->required(),
                                Checkbox::make('is_company')->label('Nákup na firmu?')->live()->columnSpan(3),
                                TextInput::make('company_name')->label('Názov firmy')
                                    ->visible(fn (Get $get) => $get('is_company'))->required(),
                                TextInput::make('business_id')->label('IČO')
                                    ->visible(fn (Get $get) => $get('is_company'))->required(),
                                TextInput::make('tax_id')->label('DIČ')
                                    ->visible(fn (Get $get) => $get('is_company'))->required(),
                                TextInput::make('vat_id')->label('IČ DPH')
                                    ->visible(fn (Get $get) => $get('is_company'))->required(),
                                TextInput::make('billing_address')->label('Ulica')->required()->columnSpan(2),
                                TextInput::make('billing_city')->label('Mesto')->required(),
                                TextInput::make('billing_zip')->label('PSČ')->required(),
                                Select::make('billing_country')
                                    ->label('Krajina')
                                    ->options(OrderCountry::translations())
                                    ->selectablePlaceholder(false)
                                    ->required()->columnSpan(2),
                                TextInput::make('billing_phone')->label('Telefón')->tel(),
                            ]),

                        Section::make('Dodacie údaje')
                            ->collapsible()
                            ->columns(3)
                            ->schema([
                                Select::make('shipping_type_id')
                                    ->label('Spôsob dodania')
                                    ->options($shippingTypes->pluck('name', 'id'))
                                    ->live()
                                    ->afterStateHydrated(fn ($state, Set $set) => self::updateShippingTypeState($state, $set, $shippingTypes))
                                    ->afterStateUpdated(fn ($state, Set $set) => self::updateShippingTypeState($state, $set, $shippingTypes)),
                                TextInput::make('shipping_type_label')->label('Názov spôsobu dodania')->required(),
                                TextInput::make('shipping_type_price')->label('Cena za dodanie')->suffix('€')->numeric(),
                                Checkbox::make('is_shipping_address')->label('Iná dodacia adresa?')->live()->columnSpan(3),
                                Grid::make(3)
                                    ->visible(fn (Get $get) => $get('is_shipping_address'))
                                    ->columnSpan(3)
                                    ->schema([
                                        TextInput::make('shipping_first_name')->label('Meno')->required(),
                                        TextInput::make('shipping_last_name')->label('Priezvisko')->required(),
                                        TextInput::make('shipping_address')->label('Ulica')->required(),
                                        TextInput::make('shipping_city')->label('Mesto')->required(),
                                        TextInput::make('shipping_zip')->label('PSČ')->required(),
                                        Select::make('shipping_country')
                                            ->label('Krajina')
                                            ->options(OrderCountry::translations())
                                            ->selectablePlaceholder(false)
                                            ->required(),
                                        TextInput::make('shipping_phone')->label('Telefón')->tel(),
                                    ]),
                            ]),

                        Section::make('Platobné údaje')
                            ->collapsible()
                            ->columns(3)
                            ->schema([
                                Select::make('payment_type_id')
                                    ->label('Spôsob platby')
                                    ->options($paymentTypes->pluck('name', 'id'))
                                    ->required()
                                    ->live()
                                    ->afterStateHydrated(fn ($state, Set $set) => self::updatePaymentTypeState($state, $set, $paymentTypes))
                                    ->afterStateUpdated(fn ($state, Set $set) => self::updatePaymentTypeState($state, $set, $paymentTypes)),
                                TextInput::make('payment_type_label')->label('Názov spôsobu platby')->required(),
                                TextInput::make('payment_type_price')->label('Cena za spôsob platby')->suffix('€')->numeric(),
                                Textarea::make('note')->label('Poznámka')->rows(4)->columnSpan(3),
                            ]),

                        Section::make('Objednané položky')
                            ->collapsible()
                            ->schema([
                                Repeater::make('orderItems')
                                    ->hiddenLabel()
                                    ->relationship()
                                    ->collapsible()
                                    ->addActionLabel('Pridať novú položku')
                                    ->deleteAction(fn (Action $action) => $action->requiresConfirmation())
                                    ->itemLabel(fn (array $state): ?string => $state['name'] ?? null)
                                    ->columns(4)
                                    ->schema([
                                        MorphToSelect::make('orderable')
                                            ->label('Položka')
                                            ->types([
                                                MorphToSelect\Type::make(Event::class)
                                                    ->titleAttribute('order_item_name'),
                                            ])
                                            ->required(),
                                        TextInput::make('name')->label('Názov')->required(),
                                        TextInput::make('quantity')->label('Množstvo')->numeric()->required()->live(),
                                        TextInput::make('price_per_unit')->label('Cena za jednotku')->numeric()->suffix('€')->required()->live(),
                                        TextInput::make('discount_amount_per_unit')->label('Zľava za jednotku')->numeric()->suffix('€')->live(),
                                        TextInput::make('total_no_vat')->label('Celkom bez DPH')->numeric()->suffix('€')->disabled(),
                                        TextInput::make('vat_amount')->label('DPH')->numeric()->suffix('€')->disabled(),
                                        TextInput::make('total_with_vat')->label('Celkom s DPH')->numeric()->suffix('€')->disabled(),
                                    ])
                                    ->afterStateUpdated(fn (array $state, Set $set, Get $get) => self::recalculateSummary($state, $set, $get)),
                            ]),
                    ]),

                    Section::make('Objednávka')
                        ->columnSpan(4)
                        ->visibleOn('edit')
                        ->collapsible()
                        ->schema([
                            ToggleButtons::make('status')
                                ->label('Stav')
                                ->options(OrderStatus::translations())
                                ->icons(OrderStatus::icons())
                                ->colors(OrderStatus::colors()),
                            Placeholder::make('subtotal')->label('Medzisúčet')
                                ->content(fn (Get $get) => self::formatSummaryPrice($get('subtotal'))),
                            Placeholder::make('discount_amount')->label('Zľava')
                                ->content(fn (Get $get) => self::formatSummaryPrice($get('discount_amount'))),
                            Placeholder::make('shipping_type_price_summary')->label('Cena za spôsob dodania')
                                ->content(fn (Get $get) => self::formatSummaryPrice($get('shipping_type_price'))),
                            Placeholder::make('payment_type_price_summary')->label('Cena za spôsob platby')
                                ->content(fn (Get $get) => self::formatSummaryPrice($get('payment_type_price'))),
                            Placeholder::make('total_no_vat')->label('Celkom bez DPH')
                                ->content(fn (Get $get) => self::formatSummaryPrice($get('total_no_vat'))),
                            Placeholder::make('vat_amount')->label('DPH')
                                ->content(fn (Get $get) => self::formatSummaryPrice($get('vat_amount'))),
                            Placeholder::make('total_with_vat')->label('Celkom s DPH')
                                ->content(fn (Get $get) => self::formatSummaryPrice($get('total_with_vat'))),
                        ]),
                ]),
            ]);
    }

    private static function recalculateSummary(array $orderItems, Set $set, Get $get): void
    {
        $subtotal = 0;
        $discount = 0;
        $billingCountry = $get('billing_country');
        if (empty($billingCountry)) {
            return;
        }

        foreach ($orderItems as &$item) {
            $quantity = $item['quantity'] ?? 0;
            $pricePerUnit = $item['price_per_unit'] ?? 0;
            $discountPerUnit = $item['discount_amount_per_unit'] ?? 0;

            $itemTotalNoVat = $quantity * ($pricePerUnit - $discountPerUnit);
            $itemVat = round($itemTotalNoVat * OrderService::getVatPercentageForSpecificCountry(OrderCountry::from($billingCountry)) / 100, 2);
            $itemTotalWithVat = $itemTotalNoVat + $itemVat;

            $item['total_no_vat'] = $itemTotalNoVat;
            $item['vat_amount'] = $itemVat;
            $item['total_with_vat'] = $itemTotalWithVat;

            $subtotal += $quantity * $pricePerUnit;
            $discount += $quantity * $discountPerUnit;
        }
        unset($item);

        $totalWithoutVAT = $subtotal - $discount;
        $vatPercentage = OrderService::getVatPercentageForSpecificCountry(OrderCountry::from($billingCountry));
        $vatAmount = round($totalWithoutVAT * $vatPercentage / 100, 2);
        $totalWithVAT = $totalWithoutVAT + $vatAmount;

        $set('orderItems', $orderItems);
        $set('subtotal', $subtotal);
        $set('discount_amount', $discount);
        $set('total_no_vat', $totalWithoutVAT);
        $set('vat_amount', $vatAmount);
        $set('total_with_vat', $totalWithVAT);
    }

    private static function formatSummaryPrice(null|float|string $value): string
    {
        if (is_string($value)) {
            $value = (float) $value;
        }

        return number_format($value ?? 0, 2).' €';
    }

    private static function updateShippingTypeState($state, Set $set, $shippingTypes): void
    {
        $shippingType = $shippingTypes->where('id', $state)->first();
        if (! $shippingType) {
            return;
        }
        $set('shipping_type_label', $shippingType->name);
        $set('shipping_type_price', $shippingType->price);
    }

    private static function updatePaymentTypeState($state, Set $set, $paymentTypes): void
    {
        $paymentType = $paymentTypes->where('id', $state)->first();
        if (! $paymentType) {
            return;
        }
        $set('payment_type_label', $paymentType->name);
        $set('payment_type_price', $paymentType->price);
    }
}
