<?php

namespace App\Enums;

use App\Traits\Translations\TranslatableEnum;

enum OrderStatus: string
{
    use TranslatableEnum;

    case ACCEPTED = "ACCEPTED";
    case CANCELED = "CANCELED";
    case PAID = "PAID";
    case FREE = "FREE";

    public static function colors(): array
    {
        return [
            self::ACCEPTED->value => 'warning',
            self::CANCELED->value => 'danger',
            self::PAID->value => 'success',
            self::FREE->value => 'info',
        ];
    }

    public static function icons(): array
    {
        return [
            self::ACCEPTED->value => 'heroicon-s-arrow-path',
            self::CANCELED->value => 'heroicon-m-x-circle',
            self::PAID->value => 'heroicon-s-check-badge',
            self::FREE->value => 'heroicon-s-hand-thumb-up',
        ];
    }

    public function color(): ?string
    {
        return self::colors()[$this->value] ?? null;
    }

    public function icon(): ?string
    {
        return self::icons()[$this->value] ?? null;
    }
}
