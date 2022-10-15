<?php

declare(strict_types=1);

namespace App\Application\Command\CreatePost;

use App\Application\DTO\ImageReadDTO;
use App\Application\Repository\ImageReadRepositoryInterface;
use App\Core\Query\QueryHandler;
use App\ValueObject\Uuid;

class GetImageByIdQueryHandler implements QueryHandler
{
    public function __construct(
        private ImageReadRepositoryInterface $imageReadRepository,
    ) {
    }

    public function __invoke(GetImageByIdQuery $query): ImageReadDTO
    {
        return $this->imageReadRepository->getById(Uuid::create($query->id));
    }
}
