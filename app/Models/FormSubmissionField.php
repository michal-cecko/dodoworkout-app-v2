<?php

namespace App\Models;

use App\Enums\FormFieldFormat;
use App\Traits\Translations\HasTranslations;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class FormSubmissionField extends Model implements HasMedia
{
    use InteractsWithMedia, HasTranslations;

    protected $fillable = [
        'form_submission_id',
        'form_field_id',
        'format',
        'label',
        'value',
    ];

    protected $casts = [
        'format' => FormFieldFormat::class,
        'label' => 'array',
        'value' => 'json',
    ];

    protected $translatable = [
        'label',
    ];

    public function formSubmission(): BelongsTo
    {
        return $this->belongsTo(FormSubmission::class);
    }

    public function formField(): BelongsTo
    {
        return $this->belongsTo(FormField::class);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('media');
    }
}
