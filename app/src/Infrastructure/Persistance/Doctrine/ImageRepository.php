<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistance\Doctrine;

use App\Application\Repository\ImageRepositoryInterface;
use App\Domain\Entity\Image;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ImageRepository extends ServiceEntityRepository implements ImageRepositoryInterface
{
    const SERVER_PATH_TO_IMAGE_FOLDER = '/application/public/images';

    public function __construct(
        ManagerRegistry $managerRegistry,
    ) {
        parent::__construct($managerRegistry, Image::class);
    }

    public function save(Image $image, UploadedFile $uploadedFile): void
    {
        $this->getEntityManager()->persist($image);
        $this->getEntityManager()->flush();

        $uploadedFile->move(
            self::SERVER_PATH_TO_IMAGE_FOLDER,
            sprintf("%s.%s", $image->getId(), $image->getExtension()->value),
        );
    }
}
