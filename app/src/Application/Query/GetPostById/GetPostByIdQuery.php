<?php

declare(strict_types=1);

namespace App\Application\Query\GetPostById;

use App\Core\Query\Query;

class GetPostByIdQuery extends Query
{
    public function __construct(
        public readonly string $id,
    ) {
    }
}
