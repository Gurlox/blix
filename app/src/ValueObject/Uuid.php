<?php

declare(strict_types=1);

namespace App\ValueObject;

use Ramsey\Uuid\Uuid as RamseyUuid;

class Uuid implements UuidInterface
{
    final protected function __construct(?string $uuid = null)
    {
        $this->uuid = null === $uuid
            ? RamseyUuid::uuid4()
            : RamseyUuid::fromString($uuid);
    }

    public static function create(?string $uuid = null): UuidInterface
    {
        return new self($uuid);
    }

    public function toString(): string
    {
        return $this->uuid->toString();
    }

    public function __toString(): string
    {
        return $this->uuid->toString();
    }
}
