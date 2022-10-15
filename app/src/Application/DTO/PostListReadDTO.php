<?php

declare(strict_types=1);

namespace App\Application\DTO;

class PostListReadDTO
{
    /**
     * @param PostReadDTO[] $posts
     */
    public function __construct(
        public readonly array $posts,
        public readonly int $totalCount,
    ) {
    }
}
