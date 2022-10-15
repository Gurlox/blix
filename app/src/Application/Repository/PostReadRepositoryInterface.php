<?php

declare(strict_types=1);

namespace App\Application\Repository;

use App\Application\DTO\PostListReadDTO;
use App\Application\DTO\PostReadDTO;
use App\ValueObject\UuidInterface;

interface PostReadRepositoryInterface
{
    public function getById(UuidInterface $id): PostReadDTO;

    public function getPaginatedList(int $page, int $perPage): array;

    public function countAll(): int;
}
