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
use Database\Factories\EventFactory;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Translatable\HasTranslations;

#[ObservedBy(SlugObserver::class)]
class Event extends Model implements Sluggable, HasMedia, Viewable, CanCopyLocaleMutations
{
    /**
     * @use HasFactory<EventFactory>
     */
    use HasTranslations, HasSlug, InteractsWithMedia, HasFactory, HasDraft, HasCopyLocaleMutations;

    protected $fillable = [
        'title',
        'content',
        'excerpt',
        'slug',
        'category_id',
        'is_draft',
        'start_at',
        'end_at',
        'address',
        'latitude',
        'longitude',
        'participants_count',
        'price',
        'last_price',
        'has_location',
        'locale_scope',
        'vat_included',
        'form_id',
    ];

    protected $casts = [
        'start_at' => 'datetime',
        'end_at' => 'datetime',
        'title' => 'array',
        'order_item_name' => 'array',
        'content' => 'array',
        'excerpt' => 'array',
        'confirmation_email_content' => 'array',
        'slug' => 'array',
        'address' => 'array',
        'locale_scope' => Locale::class,
    ];

    protected $translatable = [
        'title',
        'order_item_name',
        'confirmation_email_content',
        'content',
        'excerpt',
        'slug',
        'address',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::saving(function ($model) {
            if (!$model->has_location) {
                $model->latitude = null;
                $model->longitude = null;
            }
        });

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

    public function category(): BelongsTo
    {
        return $this->belongsTo(EventCategory::class);
    }

    public function form(): BelongsTo
    {
        return $this->belongsTo(Form::class);
    }

    public function formSubmissions(): MorphMany
    {
        return $this->morphMany(FormSubmission::class, "priceable");
    }

    public function getDaysAttribute(): ?int
    {
        return $this->end_at?->diffInDays($this->start_at) ?? null;
    }

    protected function getOrderNameAttribute(): string
    {
        return $this->order_item_name;
    }
    public function getPermalinkAttribute(): string {
        return LocaleService::getLocalizedRoutePathByName(name: "event", changeToLocale: $this->locale_scope?->value, parameters: ['event' => $this->slug]);
    }

    public function getParticipantsAvailableAttribute(): ?int
    {
        if(empty($this->participants_count)) {
            return null;
        }
        $countOfRegistrations = $this->formSubmissions->count();

        return $this->participants_count - $countOfRegistrations;
    }

    public function getLastFewLeftAttribute(): bool
    {
        if (!$this->participants_count) {
            return false;
        }

        return $this->participants_available <= ($this->participants_count * 0.15);
    }

    public function getStorageBasePathAttribute(): string
    {
        return "eventy/{$this->getTranslations("slug")['sk']}";
    }

    public function getBuilderImagesPathAttribute(): string
    {
        return "{$this->storage_base_path}/builder";
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('image')->singleFile();
        $this->addMediaCollection('confirmation_email_attachments');
    }
}
