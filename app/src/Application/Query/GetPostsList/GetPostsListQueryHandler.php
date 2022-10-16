<?php

declare(strict_types=1);

namespace App\Application\Query\GetPostsList;

use App\Application\DTO\PostListReadDTO;
use App\Application\Repository\PostReadRepositoryInterface;
use App\Core\Query\QueryHandler;
use App\ValueObject\Paging;

class GetPostsListQueryHandler implements QueryHandler
{
    public function __construct(
        private readonly PostReadRepositoryInterface $postReadRepository,
    ) {
    }

    public function __invoke(GetPostsListQuery $query): PostListReadDTO
    {
        return new PostListReadDTO(
            $this->postReadRepository->getPaginatedList(
                new Paging(
                    $query->page,
                    $query->perPage
                ),
            ),
            $this->postReadRepository->countAll(),
        );
    }
}
