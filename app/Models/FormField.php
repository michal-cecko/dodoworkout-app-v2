<?php

namespace App\Models;

use App\Contracts\CanCopyLocaleMutations;
use App\Enums\FormFieldFormat;
use App\Traits\Translations\HasCopyLocaleMutations;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;
use Spatie\Translatable\HasTranslations;

class FormField extends Model implements CanCopyLocaleMutations
{
    use HasTranslations, HasCopyLocaleMutations;

    protected $fillable = [
        'form_id',
        'label',
        'format',
        'options',
        'help_text',
        'is_required',
        'min',
        'max',

        //These below are not in the DB, just for Filament form.
        'min_select',
        'max_select',
        'min_number',
        'max_number',
        'min_date',
        'max_date',
        'min_time',
        'max_time',
        'max_file_count',
        'min_file_count',
    ];

    protected $casts = [
        'label' => 'array',
        'options' => 'array',
        'help_text' => 'array',
        'format' => FormFieldFormat::class
    ];

    protected $translatable = [
        'label',
        'help_text',
        'options',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::saving(function (FormField $model) {
            if ($model->format === FormFieldFormat::NUMBER) {
                $model->min = is_numeric($model->min_number) ? $model->min_number : null;
                $model->max = is_numeric($model->max_number) ? $model->max_number : null;
                $model->options = null;
            } elseif (in_array($model->format, [FormFieldFormat::DATE, FormFieldFormat::DATETIME])) {
                $model->min = !empty($model->min_date) ? $model->min_date : null;
                $model->max = !empty($model->max_date) ? $model->max_date : null;
                $model->options = null;
            } elseif (in_array($model->format, [FormFieldFormat::SELECT, FormFieldFormat::CHECKBOX])) {
                $model->min = is_numeric($model->min_select) ? $model->min_select : null;
                $model->max = is_numeric($model->max_select) ? $model->max_select : null;
            } elseif (in_array($model->format, [FormFieldFormat::FILE])) {
                $model->min = is_numeric($model->min_file_count) ? $model->min_file_count : null;
                $model->max = is_numeric($model->max_file_count) ? $model->max_file_count : null;
            } else {
                $model->min = null;
                $model->max = null;
                $model->options = null;
            }

            unset(
                $model->min_number,
                $model->max_number,
                $model->min_date,
                $model->max_date,
                $model->min_time,
                $model->max_time,
                $model->min_select,
                $model->max_select,
                $model->min_file_count,
                $model->max_file_count
            );
        });
    }

    public function form(): BelongsTo
    {
        return $this->belongsTo(Form::class);
    }

    public function getSlugAttribute(): string
    {
        return Str::slug($this->label, "_");
    }
}
