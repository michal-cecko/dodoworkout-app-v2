<?php

namespace App\Livewire;

use App\DataTransferObjects\Order\OrderBillingDataDTO;
use App\DataTransferObjects\Order\OrderShippingDataDTO;
use App\DataTransferObjects\Order\StoreOrderDTO;
use App\Enums\FormFieldFormat;
use App\Enums\Locale;
use App\Enums\OrderableType;
use App\Enums\OrderStatus;
use App\Enums\PaymentTypeEnum;
use App\Enums\ShippingTypeEnum;
use App\Forms\FrontendOrderForm;
use App\Misc\MorphMap;
use App\Models\Event;
use App\Models\Form;
use App\Models\FormSubmission;
use App\Models\Order;
use App\Models\PaymentType;
use App\Models\ShippingType;
use App\Notifications\OrderCreated;
use App\Services\EventService;
use App\Services\OrderService;
use Exception;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Wizard\Step;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Notifications\Notification;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;
use Livewire\Component;
use Ysfkaya\FilamentPhoneInput\Forms\PhoneInput;

class EventRegistrationForm extends Component implements HasSchemas
{
    use InteractsWithSchemas;

    const TEMP_DIR = "temp";
    protected Event $event;
    protected OrderService $orderService;
    protected EventService $eventService;

    public ?array $data = [];
    public array $eventData = [];
    public array $cartItems = [];
    public bool $submitted = false;

    public function boot(OrderService $orderService, EventService $eventService): void
    {
        $this->orderService = $orderService;
        $this->eventService = $eventService;
    }

    public function mount(Event $event): void
    {
        $this->form->fill();
        $this->event = $event;
        $this->eventData = $event->toArray();
        $this->initializeCartItems();
    }

    public function form(\Filament\Schemas\Schema $schema): \Filament\Schemas\Schema
    {
        return FrontendOrderForm::create(
            schema: $schema,
            shippingTypes: $this->getShippingTypes(),
            paymentTypes: $this->getPaymentTypes(),
            cartItems: collect($this->cartItems),
            includeShippingAddress: false,
            additionalWizardSteps: $this->getAdditionalWizardSteps(),
            submitButton: $this->getSubmitButton()
        )->statePath('data');
    }

    /**
     * @throws Exception
     */
    public function create(): void
    {
        try {
            $data = $this->form->getState();
            if (!self::canRegisterEmail($data['email'], $this->eventData['id'])) {
                $this->notifyError(__("err_email_already_registered_for_event"));
                return;
            }
            $order = $this->storeOrder($data);
            $this->storeFormSubmission($data, $order);
            $this->submitted = true;
            $order->refresh();
            OrderService::resendOrderCreatedNotification(order: $order);
            Storage::disk("local")->deleteDirectory(self::TEMP_DIR);
        } catch (Exception $e) {
            $this->notifyError($e->getMessage());
            if (!app()->environment("production")) {
                throw $e;
            }
        }
    }

    public function render(): View
    {
        // Conditionally render form or success view
        return $this->submitted
            ? view('livewire.event-registration-success') // New success view
            : view('livewire.event-registration-form');  // Original form view
    }

    protected function notifyError(string $message): void
    {
        Notification::make()
            ->title(__('error_occurred'))
            ->body($message)
            ->danger()
            ->send();
    }

    protected function initializeCartItems(): void
    {
        $cartItem = [
            'id' => $this->event->id,
            'type' => MorphMap::getKeyByModel(Event::class),
            'name' => $this->event->order_item_name,
            'quantity' => 1,
            'price' => $this->event->price,
        ];

        if (!empty($this->event->last_price)) {
            $cartItem['price'] = $this->event->price;
            $cartItem['discount'] = (float)$this->event->last_price - (float)$this->event->price;
            $cartItem['last_price'] = $this->event->last_price;
        }

        $this->cartItems = [$cartItem];
    }

    protected function getShippingTypes()
    {
        return ShippingType::visible()->where('type', ShippingTypeEnum::EMAIL)->get();
    }

    protected function getPaymentTypes()
    {
        return PaymentType::visible()->where('type', PaymentTypeEnum::BANK_TRANSFER)->get();
    }

    protected function getAdditionalWizardSteps(): array
    {
        $eventFields = $this->getEventSpecificFormFields();
        if (empty($eventFields)) {
            return [];
        }

        return ['event' => [
            'key' => 'event',
            'label' => __('ord_section_event'),
            'settings' => fn(Step $step) => $step->icon('heroicon-o-document-text'),
            'form_fields' => $this->getEventSpecificFormFields(),
        ]];
    }

    protected function getSubmitButton(): HtmlString
    {
        return new HtmlString(
            '<button type="submit" class="btn w-fit mt-auto mx-auto" data-variant="primary">' .
            __('submit') .
            '</button>'
        );
    }

    protected function getEventSpecificFormFields(): array
    {
        $form = Form::with(['formFields'])->find($this->eventData['form']['id'] ?? null);

        if (empty($form)) {
            return [];
        }

        if (empty($form->formFields)) {
            return [];
        }

        return [Grid::make([
            'default' => 1,
            'sm' => 3,
            'md' => 6,
            'lg' => 12,
        ])->schema(self::buildFormFields($form->formFields))];
    }

    public static function buildFormFields($fields): array
    {
        if (empty($fields)) {
            return [];
        }

        $finalFields = [];

        foreach ($fields as $field) {
            $finalFields[$field->slug] = self::buildFormField($field);
        }

        return $finalFields;
    }

    public static function buildFormField($field): mixed
    {
        $key = $field->slug;
        $fieldComponent = match ($field->format) {
            FormFieldFormat::TEXT => TextInput::make($key),
            FormFieldFormat::NUMBER => TextInput::make($key)->numeric()->step(1)
                ->minValue((int)$field->min ?: null)
                ->maxValue((int)$field->max ?: null),
            FormFieldFormat::PHONE => PhoneInput::make($key)
                ->defaultCountry('sk')
                ->locale(app()->currentLocale()),
            FormFieldFormat::BOOL => Checkbox::make($key)
                ->inline(false)
                ->columns(['default' => 4, 'md' => 3, 'sm' => 2, 'xs' => 1]),
            FormFieldFormat::SELECT => Select::make($key)
                ->options(self::formatOptions($field->options)),
            FormFieldFormat::CHECKBOX => CheckboxList::make($key)
                ->options(self::formatOptions($field->options)),
            FormFieldFormat::DATE => DatePicker::make($key)
                ->native(false)
                ->minDate($field->min ?: null)
                ->maxDate($field->max ?: null),
            FormFieldFormat::TIME => TimePicker::make($key)->native(false),
            FormFieldFormat::DATETIME => DateTimePicker::make($key)
                ->native(false)
                ->minDate($field->min ?: null)
                ->maxDate($field->max ?: null),
            FormFieldFormat::FILE => FileUpload::make($key)
                ->preserveFilenames()
                ->openable()
                ->downloadable()
                ->uploadingMessage(__("uploading_message"))
                ->panelLayout('integrated')
                ->disk("local")
                ->maxParallelUploads(1)
                ->directory(self::TEMP_DIR)
                ->columnSpan(12),
            default => TextInput::make($key),
        };

        if (($field->max > 1 || $field->min > 1) && in_array($field->format, [FormFieldFormat::SELECT, FormFieldFormat::CHECKBOX, FormFieldFormat::FILE])) {
            if ($field->format === FormFieldFormat::FILE) {
                $fieldComponent->minFiles((int)$field->min ?: null)
                    ->maxFiles((int)$field->max ?: null);
            } else {
                $fieldComponent->maxItems((int)$field->max ?: null)
                    ->minItems((int)$field->min ?: null);
            }

            if ($field->format === FormFieldFormat::SELECT) {
                $fieldComponent->multiple();
            }
        }

        return $fieldComponent
            ->label($field->label)
            ->required($field->is_required)
            ->helperText($field->help_text ?: null)
            ->columnSpan('full');
    }

    public static function formatOptions(array $options): array
    {
        return array_combine(
            array_column($options, 'value'),
            array_column($options, 'value')
        );
    }

    protected function storeOrder(array $data)
    {
        $billingData = new OrderBillingDataDTO(
            email: $data['email'],
            billing_first_name: $data['billing_first_name'],
            billing_last_name: $data['billing_last_name'],
            billing_address: $data['billing_address'],
            billing_city: $data['billing_city'],
            billing_zip: $data['billing_zip'],
            billing_country: $data['billing_country'],
            billing_phone: $data['billing_phone'],
            is_company: $data['is_company'],
            company_name: $data['is_company'] ? $data['company_name'] : null,
            business_id: $data['is_company'] ? $data['business_id'] : null,
            tax_id: $data['is_company'] ? ($data['tax_id'] ?? null) : null,
            vat_id: $data['is_company'] ? ($data['vat_id'] ?? null) : null,
        );

        $isShippingAddress = $data['is_shipping_address'] ?? false;
        $shippingData = new OrderShippingDataDTO(
            is_shipping_address: $isShippingAddress,
            shipping_first_name: $isShippingAddress ? $data['shipping_first_name'] : null,
            shipping_last_name: $isShippingAddress ? $data['shipping_last_name'] : null,
            shipping_address: $isShippingAddress ? $data['shipping_address'] : null,
            shipping_city: $isShippingAddress ? $data['shipping_city'] : null,
            shipping_zip_code: $isShippingAddress ? $data['shipping_zip_code'] : null,
            shipping_country: $isShippingAddress ? $data['shipping_country'] : null,
            shipping_phone: $isShippingAddress ? $data['shipping_phone'] : null,
        );

        $orderData = new StoreOrderDTO(
            billing: $billingData,
            shipping: $shippingData,
            shipping_type_id: $data['shipping_type_id'],
            payment_type_id: $data['payment_type_id'],
            note: $data['note'] ?? null,
            marketing: $data['marketing'] ?? false,
            user_id: auth()->user()?->id,
            locale: Locale::from(strtoupper(app()->currentLocale())),
        );

        return $this->orderService->storeOrder(
            orderDTO: $orderData,
            products: collect($this->cartItems),
        );
    }

    protected function storeFormSubmission(array $data, Order $order): ?FormSubmission
    {
        $submission = FormSubmission::create([
            'form_id' => $this->eventData['form_id'],
            'user_id' => auth()->user()?->id,
            'order_id' => $order->id,
            'order_item_id' => $order->orderItems->first()->id,
            'priceable_id' => $this->eventData['id'],
            'priceable_type' => OrderableType::EVENT->value,
        ]);

        if (!$this->eventData['form_id']) {
            return $submission;
        }

        $form = Form::with(['formFields'])->find($this->eventData['form']['id'] ?? null);
        if ($form) {
            foreach ($form->formFields as $field) {
                if (!isset($data[$field->slug])) continue;

                $value = $data[$field->slug];
                if ((is_string($value) && str_contains($value, self::TEMP_DIR . "/")) || (is_array($value) && str_contains(reset($value), self::TEMP_DIR . "/"))) {
                    $values = is_array($value) ? $value : [$value];

                    $submittedField = $submission->formFields()->create([
                        'form_field_id' => $field->id,
                        'format' => $field->format,
                        'value' => '_binary_',
                    ]);

                    foreach ($values as $filePath) {
                        if (str_contains($filePath, self::TEMP_DIR . "/")) {
                            $randomHash = Str::random();
                            $submittedField->addMedia(Storage::disk("local")->path($filePath))
                                ->usingFileName($randomHash . '.' . pathinfo($filePath, PATHINFO_EXTENSION))
                                ->toMediaCollection("media");
                        }
                    }
                } else {
                    $submittedField = $submission->formFields()->create([
                        'form_field_id' => $field->id,
                        'format' => $field->format,
                        'value' => $value,
                    ]);
                }

                $submittedField->setTranslations("label", $field->getTranslations("label"));

                $submittedField->save();

            }
        }

        return $submission;
    }

    public static function canRegisterEmail(string $email, $eventId): bool
    {
        $alreadyOrdered = Order::whereHas('formSubmissions', function ($query) use ($eventId) {
            $query->whereHasMorph('priceable', [Event::class], function ($query) use ($eventId) {
                $query->where('id', $eventId);
            });
        })->where("email", $email)->where("status", "!=", OrderStatus::CANCELED)->exists();

        return !$alreadyOrdered;
    }
}
