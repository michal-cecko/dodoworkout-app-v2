<?php

namespace App\Traits\Translations;

use Illuminate\Support\Str;
use Spatie\Translatable\Translatable;

trait HasTranslations
{
    use \Spatie\Translatable\HasTranslations;

    public function getTranslation(string $key, string $locale, bool $useFallbackLocale = true): mixed
    {
        if (method_exists($this, $key)) {
            return $this->relations[$key] ?? collect([]);
        }

        $normalizedLocale = $this->normalizeLocale($key, $locale, $useFallbackLocale);

        $isKeyMissingFromLocale = ($locale !== $normalizedLocale);

        $translations = $this->getTranslations($key);

        $baseKey = Str::before($key, '->'); // get base key in case it is JSON nested key

        $translatableConfig = app(Translatable::class);

        if (is_null(self::getAttributeFromArray($baseKey))) {
            $translation = null;
        } else {
            $translation = $translations[$normalizedLocale] ?? null;
            $translation ??= ($translatableConfig->allowNullForTranslation) ? null : '';
        }

        if ($isKeyMissingFromLocale && $translatableConfig->missingKeyCallback) {
            try {
                $callbackReturnValue = ($translatableConfig->missingKeyCallback)($this, $key, $locale, $translation, $normalizedLocale);
                if (is_string($callbackReturnValue)) {
                    $translation = $callbackReturnValue;
                }
            } catch (Exception) {
                // prevent the fallback to crash
            }
        }

        $key = str_replace('->', '-', $key);

        if ($this->hasGetMutator($key)) {
            return $this->mutateAttribute($key, $translation);
        }

        if ($this->hasAttributeMutator($key)) {
            return $this->mutateAttributeMarkedAttribute($key, $translation);
        }

        return $translation;
    }
}
