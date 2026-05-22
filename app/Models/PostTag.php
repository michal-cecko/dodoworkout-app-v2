<?php

namespace App\Models;

use App\Contracts\Sluggable;
use App\Observers\SlugObserver;
use App\Traits\HasSlug;
use Database\Factories\PostTagFactory;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;
use Spatie\Translatable\HasTranslations;
use App\Enums\Locale;

#[ObservedBy(SlugObserver::class)]
class PostTag extends Model implements Sluggable
{
    /**
     * @use HasFactory<PostTagFactory>
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

    public function posts(): BelongsToMany
    {
        return $this->belongsToMany(Post::class, "post_tag_post_pivot", "tag_id", "post_id");
    }
}
