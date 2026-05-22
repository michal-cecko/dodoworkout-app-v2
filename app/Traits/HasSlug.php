<?php

namespace App\Traits;

use App\Enums\Locale;

trait HasSlug
{
    public function slugColumn(): string
    {
        return "slug";
    }

    public function uniqueSlugQuery(string $slug, ?Locale $locale = null): bool
    {
        return static::query()->where("slug->" . $locale->value, $slug)->exists();
    }
}
