<?php

namespace App\Forms;

use App\Enums\OrderCountry;
use App\Models\PaymentType;
use App\Models\ShippingType;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Wizard;
use Filament\Schemas\Components\Wizard\Step;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;
use Illuminate\Support\HtmlString;
use JaOcero\RadioDeck\Forms\Components\RadioDeck;
use Ysfkaya\FilamentPhoneInput\Forms\PhoneInput;

class FrontendOrderForm
{
    public static bool $includeShippingAddress = true;
    public static array $additionalWizardSteps = [];
    private static ?HtmlString $submitButton;
    private static EloquentCollection $shippingTypes;
    private static EloquentCollection $paymentTypes;
    private static Collection $cartItems;

    public static function create(
        Schema $schema,
        EloquentCollection $shippingTypes,
        EloquentCollection $paymentTypes,
        Collection $cartItems,
        bool $includeShippingAddress = true,
        array $additionalWizardSteps = [],
        ?HtmlString $submitButton = null,
    ): Schema {
        self::$includeShippingAddress = $includeShippingAddress;
        self::$additionalWizardSteps = $additionalWizardSteps;
        self::$submitButton = $submitButton;
        self::$shippingTypes = $shippingTypes;
        self::$paymentTypes = $paymentTypes;
        self::$cartItems = $cartItems;

        return $schema->components(self::getFormFields());
    }

    public static function getFormFields(): array
    {
        $steps = array_merge([
            Step::make('billing')
                ->label(__('ord_section_billing'))
                ->icon('heroicon-o-user')
                ->schema([
                    self::getBillingInfoFields(),
                    self::getCompanyFields(),
                    self::getBillingAddressFields(),
                ]),
            Step::make('shipping')
                ->label(__('ord_section_shipping'))
                ->icon('heroicon-o-truck')
                ->schema([
                    Grid::make(12)->schema([self::getShippingFields()]),
                ]),
            Step::make('payment')
                ->label(__('ord_section_payment'))
                ->icon('heroicon-o-credit-card')
                ->schema([
                    self::getPaymentFields(),
                ]),
        ], self::createAdditionalSteps(), [
            Step::make('summary')
                ->label(__('ord_section_summary'))
                ->icon('heroicon-o-document-check')
                ->schema([
                    Placeholder::make('summary')
                        ->hiddenLabel()
                        ->content(fn (Get $get) => self::buildSummaryView($get)),
                    Checkbox::make('terms')
                        ->label(__('ord_fld_terms'))
                        ->required()
                        ->columnSpanFull(),
                    Checkbox::make('marketing')
                        ->label(__('ord_fld_marketing'))
                        ->columnSpanFull(),
                ]),
        ]);

        $wizard = Wizard::make($steps)->skippable(false);

        if (self::$submitButton) {
            $wizard->submitAction(self::$submitButton);
        }

        return [$wizard];
    }

    private static function buildSummaryView(Get $get): \Illuminate\Contracts\View\View
    {
        $data = [];

        $billingData = [
            __('ord_fld_billing_first_name') => $get('billing_first_name'),
            __('ord_fld_billing_last_name') => $get('billing_last_name'),
            __('ord_fld_email') => $get('email'),
            __('ord_fld_billing_phone') => $get('billing_phone'),
            __('ord_fld_billing_address') => $get('billing_address'),
            __('ord_fld_billing_city') => $get('billing_city'),
            __('ord_fld_billing_zip') => $get('billing_zip'),
            __('ord_fld_billing_country') => $get('billing_country'),
        ];

        if (! empty($get('is_company'))) {
            $billingData[__('ord_fld_company_name')] = $get('company_name');
            $billingData[__('ord_fld_company_business_id')] = $get('business_id');
            $billingData[__('ord_fld_company_tax_id')] = $get('tax_id');
            $billingData[__('ord_fld_company_vat_id')] = $get('vat_id');
        }

        $data[__('ord_section_billing')] = $billingData;

        $shippingData = [
            __('ord_fld_shipping_type') => self::$shippingTypes->where('id', $get('shipping_type_id'))->first()?->name,
        ];
        if (! empty($get('is_shipping_address'))) {
            $shippingData[__('ord_fld_shipping_first_name')] = $get('shipping_first_name');
            $shippingData[__('ord_fld_shipping_last_name')] = $get('shipping_last_name');
            $shippingData[__('ord_fld_shipping_phone')] = $get('shipping_phone');
            $shippingData[__('ord_fld_shipping_address')] = $get('shipping_address');
            $shippingData[__('ord_fld_shipping_city')] = $get('shipping_city');
            $shippingData[__('ord_fld_shipping_zip')] = $get('shipping_zip');
            $shippingData[__('ord_fld_shipping_country')] = $get('shipping_country');
        }
        $data[__('ord_section_shipping')] = $shippingData;

        $paymentData = [
            __('ord_fld_payment_type') => self::$paymentTypes->where('id', $get('payment_type_id'))->first()?->name,
        ];
        if (! empty($get('note'))) {
            $paymentData[__('ord_fld_note')] = $get('note');
        }
        $data[__('ord_section_payment')] = $paymentData;

        return view('parts.order.frontend-order-summary', [
            'data' => $data,
            'cartItems' => self::$cartItems,
        ]);
    }

    private static function getBillingInfoFields(): Grid
    {
        return Grid::make(12)->schema([
            TextInput::make('billing_first_name')
                ->label(__('ord_fld_billing_first_name'))
                ->maxLength(255)
                ->required()
                ->columnSpan(3),
            TextInput::make('billing_last_name')
                ->label(__('ord_fld_billing_last_name'))
                ->maxLength(255)
                ->required()
                ->columnSpan(3),
            TextInput::make('email')
                ->email()
                ->label(__('ord_fld_email'))
                ->maxLength(255)
                ->required()
                ->columnSpan(3),
            PhoneInput::make('billing_phone')
                ->label(__('ord_fld_billing_phone'))
                ->required()
                ->columnSpan(3),
        ]);
    }

    private static function getCompanyFields(): Grid
    {
        return Grid::make(12)->schema([
            Checkbox::make('is_company')
                ->label(__('ord_fld_is_company'))
                ->columnSpanFull()
                ->live()
                ->inline(),
            TextInput::make('company_name')
                ->label(__('ord_fld_company_name'))
                ->visible(fn (Get $get) => ! empty($get('is_company')))
                ->maxLength(255)
                ->required()
                ->columnSpan(3),
            TextInput::make('business_id')
                ->label(__('ord_fld_company_business_id'))
                ->required()
                ->maxLength(255)
                ->visible(fn (Get $get) => ! empty($get('is_company')))
                ->columnSpan(3),
            TextInput::make('tax_id')
                ->label(__('ord_fld_company_tax_id'))
                ->maxLength(255)
                ->visible(fn (Get $get) => ! empty($get('is_company')))
                ->columnSpan(3),
            TextInput::make('vat_id')
                ->label(__('ord_fld_company_vat_id'))
                ->visible(fn (Get $get) => ! empty($get('is_company')))
                ->maxLength(255)
                ->columnSpan(3),
        ]);
    }

    private static function getBillingAddressFields(): Grid
    {
        return Grid::make(12)->schema([
            TextInput::make('billing_address')
                ->label(__('ord_fld_billing_address'))
                ->maxLength(255)
                ->required()
                ->columnSpan(3),
            TextInput::make('billing_city')
                ->label(__('ord_fld_billing_city'))
                ->maxLength(255)
                ->required()
                ->columnSpan(3),
            TextInput::make('billing_zip')
                ->label(__('ord_fld_billing_zip'))
                ->maxLength(255)
                ->required()
                ->columnSpan(3),
            Select::make('billing_country')
                ->label(__('ord_fld_billing_country'))
                ->options(OrderCountry::translations())
                ->placeholder(__('ord_fld_billing_country_placeholder'))
                ->required()
                ->searchable()
                ->columnSpan(3),
        ]);
    }

    private static function getShippingFields(): Grid
    {
        $shippingTypes = empty(self::$shippingTypes)
            ? (self::$shippingTypes = ShippingType::visible()->get())
            : self::$shippingTypes;

        $options = $shippingTypes->mapWithKeys(fn ($st) => [$st->id => $st->name])->toArray();
        $descriptions = $shippingTypes->mapWithKeys(fn ($st) => [$st->id => $st->description])->toArray();
        $icons = $shippingTypes->mapWithKeys(fn ($st) => [$st->id => $st->icon])->toArray();

        $fields = [
            RadioDeck::make('shipping_type_id')
                ->label(__('ord_fld_shipping_type'))
                ->options($options)
                ->descriptions($descriptions)
                ->icons($icons)
                ->default($shippingTypes->first()?->id)
                ->required()
                ->color('primary')
                ->columnSpan(6),
        ];

        if (self::$includeShippingAddress) {
            $fields[] = Checkbox::make('is_shipping_address')
                ->label(__('ord_fld_is_shipping_address'))
                ->columnSpanFull()
                ->live()
                ->inline();

            foreach ([
                ['shipping_first_name', 'ord_fld_shipping_first_name', 'text'],
                ['shipping_last_name', 'ord_fld_shipping_last_name', 'text'],
                ['shipping_phone', 'ord_fld_shipping_phone', 'phone'],
                ['shipping_address', 'ord_fld_shipping_address', 'text'],
                ['shipping_city', 'ord_fld_shipping_city', 'text'],
                ['shipping_zip', 'ord_fld_shipping_zip', 'text'],
            ] as [$name, $label, $type]) {
                $field = $type === 'phone'
                    ? PhoneInput::make($name)
                    : TextInput::make($name);

                $fields[] = $field
                    ->label(__($label))
                    ->required()
                    ->visible(fn (Get $get) => $get('is_shipping_address'))
                    ->columnSpan(3);
            }

            $fields[] = Select::make('shipping_country')
                ->label(__('ord_fld_shipping_country'))
                ->options(OrderCountry::translations())
                ->searchable()
                ->placeholder(__('ord_fld_shipping_country_placeholder'))
                ->required()
                ->visible(fn (Get $get) => $get('is_shipping_address'))
                ->columnSpan(3);
        }

        return Grid::make(12)->schema($fields);
    }

    private static function getPaymentFields(): Grid
    {
        $paymentTypes = empty(self::$paymentTypes)
            ? (self::$paymentTypes = PaymentType::visible()->get())
            : self::$paymentTypes;

        $options = $paymentTypes->mapWithKeys(fn ($pt) => [$pt->id => $pt->name])->toArray();
        $descriptions = $paymentTypes->mapWithKeys(fn ($pt) => [$pt->id => $pt->description])->toArray();
        $icons = $paymentTypes->mapWithKeys(fn ($pt) => [$pt->id => $pt->icon])->toArray();

        return Grid::make(12)->schema([
            RadioDeck::make('payment_type_id')
                ->label(__('ord_fld_payment_type'))
                ->options($options)
                ->descriptions($descriptions)
                ->icons($icons)
                ->default($paymentTypes->first()?->id)
                ->color('primary')
                ->required()
                ->columnSpan(6),
            Textarea::make('note')
                ->label(__('ord_fld_note'))
                ->placeholder(__('ord_fld_note_placeholder'))
                ->columnSpanFull()
                ->rows(4),
        ]);
    }

    private static function createAdditionalSteps(): array
    {
        $steps = [];

        foreach (self::$additionalWizardSteps as $stepData) {
            $step = Step::make($stepData['key'])
                ->label($stepData['label'])
                ->schema($stepData['form_fields']);

            if (isset($stepData['settings'])) {
                $step = $stepData['settings']($step);
            }

            $steps[] = $step;
        }

        return $steps;
    }
}
