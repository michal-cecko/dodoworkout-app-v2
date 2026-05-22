<?php

namespace App\Observers;

use App\Contracts\Sluggable;

class SlugObserver
{
    public function creating(Sluggable $model): void
    {
        $finalSlug = [];

        foreach (config("app.locales") as $locale) {
            $baseOfSlug = $model->slugFormat($locale);
            $number = 0;

            do {
                $slug = $baseOfSlug;

                if ($number) {
                    $slug .= "-" . $number;
                }

                $number++;
            } while ($model->uniqueSlugQuery($slug, $locale));

            $finalSlug[strtolower($locale->value)] = $slug;
        }

        $model->{$model->slugColumn()} = $finalSlug;
    }
}
