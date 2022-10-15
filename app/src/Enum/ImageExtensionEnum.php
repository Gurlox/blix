<?php

declare(strict_types=1);

namespace App\Enum;

enum ImageExtensionEnum: string
{
    case JPG = 'jpg';

    public static function availableValues(): array
    {
        $values = [];
        foreach (self::cases() as $case) {
            $values[] = $case->value;
        }

        return $values;
    }
}
