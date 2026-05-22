<?php

namespace App\Models;

use App\Contracts\CanCopyLocaleMutations;
use App\Contracts\Sluggable;
use App\Contracts\Viewable;
use App\Enums\Locale;
use App\Observers\SlugObserver;
use App\Services\LocaleService;
use App\Traits\HasDraft;
use App\Traits\HasSlug;
use App\Traits\Translations\HasCopyLocaleMutations;
use Database\Factories\PostFactory;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Translatable\HasTranslations;

#[ObservedBy(SlugObserver::class)]
class Post extends Model implements Sluggable, HasMedia, Viewable, CanCopyLocaleMutations
{
    /**
     * @use HasFactory<PostFactory>
     */
    use HasTranslations, HasSlug, InteractsWithMedia, HasFactory, HasDraft, HasCopyLocaleMutations;

    protected $fillable = [
        'title',
        'content',
        'excerpt',
        'slug',
        'likes',
        'dislikes',
        'published_at',
        'is_draft',
        'locale_scope',
    ];

    public $casts = [
        'title' => 'array',
        'content' => 'array',
        'excerpt' => 'array',
        'slug' => 'array',
        'published_at' => 'datetime',
        'locale_scope' => Locale::class,
    ];

    protected $translatable = [
        'title',
        'content',
        'excerpt',
        'slug',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::deleted(function ($model) {
            $model->media()->delete();
            Storage::disk("public")->deleteDirectory($model->storage_base_path);
        });
    }

    public function slugFormat(?Locale $locale = null): string
    {
        $translations = $this->getTranslations("title");
        return Str::slug($translations[strtolower($locale->value)] ?? $translations[strtolower(config('app.fallback_locale') ?? null)]);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(PostTag::class, "post_tag_post_pivot", "post_id", "tag_id");
    }

    public function getPermalinkAttribute(): string
    {
        return LocaleService::getLocalizedRoutePathByName(name: "post", changeToLocale: $this->locale_scope?->value, parameters: ['post' => $this->slug]);
    }

    public function getStorageBasePathAttribute(): string
    {
        return "clanky/{$this->getTranslations("slug")['sk']}";
    }

    public function getBuilderImagesPathAttribute(): string
    {
        return "{$this->storage_base_path}/builder";
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('image')->singleFile();
    }
}
