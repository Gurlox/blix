<?php

declare(strict_types=1);

namespace App\Application\Query\GetPostById;

use App\Application\DTO\PostReadDTO;
use App\Application\Repository\PostReadRepositoryInterface;
use App\Core\Query\QueryHandler;
use App\ValueObject\Uuid;

class GetPostByIdQueryHandler implements QueryHandler
{
    public function __construct(
        private PostReadRepositoryInterface $postReadRepository,
    ) {
    }

    public function __invoke(GetPostByIdQuery $query): PostReadDTO
    {
        return $this->postReadRepository->getById(Uuid::create($query->id));
    }
}
