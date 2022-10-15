<?php

declare(strict_types=1);

namespace App\Application\Command\CreatePost;

use App\Core\Query\Query;

class GetImageByIdQuery extends Query
{
    public function __construct(
        public readonly string $id,
    ) {
    }
}
