<?php

declare(strict_types=1);

namespace App\Core\DoctrineType;

use App\ValueObject\Uuid;
use App\ValueObject\UuidInterface;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\GuidType;

class UuidType extends GuidType
{
    public const NAME = 'uuid';

    public function convertToPHPValue($value, AbstractPlatform $platform): ?UuidInterface
    {
        return null === $value ? null : Uuid::create($value);
    }

    public function getName(): string
    {
        return $this::NAME;
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): mixed
    {
        return $value;
    }
}
