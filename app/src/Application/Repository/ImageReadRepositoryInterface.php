<?php

declare(strict_types=1);

namespace App\Application\Repository;

use App\Application\DTO\ImageReadDTO;
use App\ValueObject\UuidInterface;

interface ImageReadRepositoryInterface
{
    public function getById(UuidInterface $id): ImageReadDTO;
}
