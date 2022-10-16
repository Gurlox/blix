<?php

declare(strict_types=1);

namespace App\Application\Repository;

use App\Application\DTO\PostListReadDTO;
use App\Application\DTO\PostReadDTO;
use App\ValueObject\Paging;
use App\ValueObject\UuidInterface;

interface PostReadRepositoryInterface
{
    public function getById(UuidInterface $id): PostReadDTO;

    public function getPaginatedList(Paging $paging): array;

    public function countAll(): int;
}
