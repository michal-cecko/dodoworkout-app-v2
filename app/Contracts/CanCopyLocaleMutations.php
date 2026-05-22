<?php

namespace App\Contracts;

use App\Enums\Locale;

interface CanCopyLocaleMutations
{
    public function copyMutation(Locale $sourceLocale, Locale $targetLocale): void;
}
