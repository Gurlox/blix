<?php

declare(strict_types=1);

namespace App\Application\DTO;

use App\Domain\Entity\Post;

class PostReadDTO
{
    public function __construct(
        public readonly string $id,
        public readonly string $title,
        public readonly string $text,
        public readonly ImageReadDTO $image,
    ) {
    }
}
