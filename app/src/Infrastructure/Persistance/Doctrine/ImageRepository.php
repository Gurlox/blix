<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistance\Doctrine;

use App\Application\Repository\ImageRepositoryInterface;
use App\Domain\Entity\Image;
use App\ValueObject\UuidInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ImageRepository extends ServiceEntityRepository implements ImageRepositoryInterface
{
    const SERVER_PATH_TO_IMAGE_FOLDER = '/application/public/images';

    public function __construct(
        ManagerRegistry $managerRegistry,
        private string $env,
    ) {
        parent::__construct($managerRegistry, Image::class);
    }

    public function save(Image $image, UploadedFile $uploadedFile): void
    {
        $this->getEntityManager()->persist($image);
        $this->getEntityManager()->flush();

        if ('test' === $this->env) {
            return;
        }

        $uploadedFile->move(
            self::SERVER_PATH_TO_IMAGE_FOLDER,
            sprintf("%s.%s", $image->getId(), $image->getExtension()->value),
        );
    }

    public function getById(UuidInterface $id): Image
    {
        /** @var ?Image $image */
        $image = $this->getEntityManager()->getRepository(Image::class)->find($id);

        if (null === $image) {
            throw new NotFoundHttpException('Image not found');
        }

        return $image;
    }
}
