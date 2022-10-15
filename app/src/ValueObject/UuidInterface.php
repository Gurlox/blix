<?php

declare(strict_types=1);

namespace App\ValueObject;

interface UuidInterface
{
    public static function create(?string $uuid = null): self;

    public function toString(): string;
}
