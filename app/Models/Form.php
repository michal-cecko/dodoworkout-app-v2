<?php

namespace App\Models;

use App\Contracts\CanCopyLocaleMutations;
use App\Contracts\Sluggable;
use App\Enums\Locale;
use App\Observers\SlugObserver;
use App\Traits\HasSlug;
use App\Traits\Translations\HasCopyLocaleMutations;
use App\Traits\Translations\HasTranslations;
use Exception;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

#[ObservedBy(SlugObserver::class)]
class Form extends Model implements CanCopyLocaleMutations, Sluggable
{
    use HasTranslations, HasFactory, HasCopyLocaleMutations, HasSlug;

    protected $fillable = [
        'name',
    ];

    protected $casts = [
        'name' => 'array',
        'slug' => 'array'
    ];

    protected $translatable = [
        'name',
        'slug',
        'formFields',
    ];

    public function slugFormat(?Locale $locale = null): string
    {
        $translations = $this->getTranslations("name");
        return Str::slug($translations[strtolower($locale->value)] ?? $translations[strtolower(config('app.fallback_locale') ?? null)]);
    }

    protected static function boot(): void
    {
        parent::boot();

        static::saving(function (Form $model) {
            if($model->getAttribute("formFields") !== null) {
                unset($model->formFields);
            }
        });
    }

    public function formFields(): HasMany
    {
        return $this->hasMany(FormField::class, "form_id");
    }
}
