<?php

namespace App\Models;

use App\Contracts\CanCopyLocaleMutations;
use App\Enums\PaymentTypeEnum;
use App\Traits\HasHidden;
use App\Traits\Translations\HasCopyLocaleMutations;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class PaymentType extends Model implements CanCopyLocaleMutations
{
    use HasTranslations, HasHidden, HasCopyLocaleMutations;

    protected $fillable = [
        'name',
        'description',
        'price',
        'is_hidden',
        'type',
        'icon',
    ];

    protected $casts = [
        'name' => 'array',
        'description' => 'array',
        'type' => PaymentTypeEnum::class,
    ];

    protected $translatable = [
        'name',
        'description',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::saving(function (PaymentType $model) {
            if (empty($model->price)) {
                $model->price = 0;
            }
        });
    }
}
