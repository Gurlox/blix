<?php

declare(strict_types=1);

namespace App\Application\DTO;

class ImageReadDTO
{
    public function __construct(
        public readonly string $id,
        public readonly string $originalFileName,
        public readonly string $extension,
        public readonly int $size,
        public readonly string $url,
    ) {
    }
}
