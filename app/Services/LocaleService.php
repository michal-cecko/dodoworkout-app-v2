<?php

namespace App\Services;

namespace App\Services;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;

class LocaleService
{
    public static function getLocaleOptions($skipDefault = false) : array {
        $locales = config("app.locales");

        if ($skipDefault) {
            $locales = array_filter($locales, fn($locale) => $locale !== config("app.fallback_locale"));
        }

        return $locales;
    }

    public static function localizeUrl(string $url, ?string $locale = null): string
    {
        $locale = $locale ?? config('app.fallback_locale');

        if($locale === config("app.fallback_locale")) {
            return URL::to($url);
        }

        return URL::to($locale . '/' . ltrim($url, '/'));
    }

    public static function localizePath(string $path, ?string $locale = null): string
    {
        $locale = $locale ?? config('app.fallback_locale');

        if($locale === config("app.fallback_locale")) {
            return ltrim($path, '/');
        }

        return $locale . '/' . ltrim($path, '/');
    }

    public static function getLocalizedRoutePathByName(string $name, ?string $changeToLocale = null, array $parameters = []): string {
        if($changeToLocale) {
            foreach (config("app.locales") as $locale) {
                if (str_starts_with($name, strtolower($locale->value) . ".")) {
                    $name = str_replace(strtolower($locale->value) . ".", "", $name);
                    break;
                }
            }
        } else {
            $changeToLocale = strtolower(app()->currentLocale());
        }

        if(strtolower($changeToLocale) !== strtolower(config("app.fallback_locale"))) {
            $name = strtolower($changeToLocale) . "." . $name;
        }

        return self::localizeUrl(route($name, $parameters));
    }
}
