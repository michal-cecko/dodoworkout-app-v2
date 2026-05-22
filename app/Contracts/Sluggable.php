<?php

namespace App\Contracts;

use App\Enums\Locale;

interface Sluggable
{
    function slugColumn() : string;
    function slugFormat(?Locale $locale = null) : string;
    function uniqueSlugQuery(string $slug, ?Locale $locale = null) : bool;
}
