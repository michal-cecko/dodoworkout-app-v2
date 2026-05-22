<?php

namespace App\Traits\Translations;

use Filament\Resources\Pages\CreateRecord\Concerns\Translatable;
use Illuminate\Support\Arr;

trait TranslatableCreateView
{
    use Translatable;

    // This fixes issue with Spatie Translatable plugin where change in locale doesnt change repeater field mutations.
    public function updatedActiveLocale(): void
    {
        if (blank($this->oldActiveLocale)) {
            return;
        }

        $this->resetValidation();

        $translatableAttributes = static::getResource()::getTranslatableAttributes();

        $this->otherLocaleData[$this->oldActiveLocale] = Arr::only($this->data, $translatableAttributes);

        $this->form->fill([
            ...Arr::except($this->data, $translatableAttributes),
            ...$this->otherLocaleData[$this->activeLocale] ?? [],
        ]);
        // Fix part - End

        unset($this->otherLocaleData[$this->activeLocale]);
    }
}
