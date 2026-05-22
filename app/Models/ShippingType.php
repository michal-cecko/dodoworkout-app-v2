<?php

namespace App\Models;

use App\Contracts\CanCopyLocaleMutations;
use App\Enums\ShippingTypeEnum;
use App\Traits\HasHidden;
use App\Traits\Translations\HasCopyLocaleMutations;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class ShippingType extends Model implements CanCopyLocaleMutations
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
        'type' => ShippingTypeEnum::class,
    ];

    protected $translatable = [
        'description',
        'name',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::saving(function (ShippingType $model) {
            if (empty($model->price)) {
                $model->price = 0;
            }
        });
    }
}
