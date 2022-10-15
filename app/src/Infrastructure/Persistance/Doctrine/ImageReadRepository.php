<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistance\Doctrine;

use App\Application\DTO\ImageReadDTO;
use App\Application\Repository\ImageReadRepositoryInterface;
use App\Domain\Entity\Image;
use App\ValueObject\UuidInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\RouterInterface;

class ImageReadRepository extends ServiceEntityRepository implements ImageReadRepositoryInterface
{
    public function __construct(
        ManagerRegistry $managerRegistry,
        private RequestStack $requestStack,
    ) {
        parent::__construct($managerRegistry, Image::class);
    }
    public function getById(UuidInterface $id): ImageReadDTO
    {
        /** @var ?Image $image */
        $image = $this->getEntityManager()->getRepository(Image::class)->find($id);

        if (null === $image) {
            throw new NotFoundHttpException('Image not found');
        }

        return new ImageReadDTO(
            $image->getId()->toString(),
            $image->getOriginalFileName(),
            $image->getExtension()->value,
            $image->getSize(),
            sprintf("%s/images/%s", $this->requestStack->getCurrentRequest()->getHttpHost(), $image->getFileName()),
        );
    }
}
