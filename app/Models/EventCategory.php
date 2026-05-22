<?php

namespace App\Models;

use App\Contracts\Sluggable;
use App\Enums\Locale;
use App\Observers\SlugObserver;
use App\Traits\HasSlug;
use Database\Factories\EventCategoryFactory;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;
use Spatie\Translatable\HasTranslations;

#[ObservedBy(SlugObserver::class)]
class EventCategory extends Model implements Sluggable
{
    /**
     * @use HasFactory<EventCategoryFactory>
     */
    use HasTranslations, HasSlug, HasFactory;
    protected $fillable = [
        'name',
        'slug',
    ];

    public $casts = [
        'name' => 'array',
        'slug' => 'array',
    ];

    public array $translatable = [
        'name',
        'slug',
    ];

    public function slugFormat(?Locale $locale = null): string
    {
        $translations = $this->getTranslations("name");
        return Str::slug($translations[strtolower($locale->value)] ?? $translations[strtolower(config('app.fallback_locale') ?? null)]);
    }

    public function events(): HasMany
    {
        return $this->hasMany(Event::class, "category_id");
    }
}
