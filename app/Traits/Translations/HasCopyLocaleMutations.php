<?php

namespace App\Traits\Translations;

use App\Enums\Locale;

/**
 * @method save()
 */
trait HasCopyLocaleMutations
{
    public function copyMutation(Locale $sourceLocale, Locale $targetLocale): void
    {
        foreach ($this->translatable ?? [] as $translatableProperty) {
            if (isset($this, $translatableProperty) && !method_exists($this, $translatableProperty)) {
                $this->setTranslation(
                    key: $translatableProperty,
                    locale: strtolower($targetLocale->value),
                    value: $this->getTranslation(
                        key: $translatableProperty,
                        locale: strtolower($sourceLocale->value)
                    )
                );
            } else if (method_exists($this, $translatableProperty)) {
                $relationRecords = $translatableProperty;
                foreach ($relationRecords as $relationRecord) {
                    $relationRecord->copyMutation($sourceLocale, $targetLocale);
                }
            }
        }

        $this->save();
    }
}
