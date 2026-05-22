<?php

namespace App\Traits\Translations;

trait TranslatableEnum
{
    public function translation(): string {
        return __(class_basename(static::class) . "." . $this->value);
    }

    public static function translations(): array {
        $return = [];

        foreach (static::cases() as $case) {
            $return[$case->value] = $case->translation();
        }

        return $return;
    }
}
