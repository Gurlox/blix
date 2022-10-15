<?php

declare(strict_types=1);

namespace App\Application\Repository;

use App\Domain\Entity\Post;

interface PostRepositoryInterface
{
    public function save(Post $post): void;
}
