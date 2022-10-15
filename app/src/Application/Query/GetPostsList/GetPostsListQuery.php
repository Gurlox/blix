<?php

declare(strict_types=1);

namespace App\Application\Query\GetPostsList;

use App\Core\Query\Query;

class GetPostsListQuery extends Query
{
    public function __construct(
        public readonly int $page,
        public readonly int $perPage,
    ) {
    }
}
