<?php

declare(strict_types=1);

namespace App\Application\Repository;

use App\Domain\Entity\Image;
use App\ValueObject\UuidInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

interface ImageRepositoryInterface
{
    public function save(Image $image, UploadedFile $uploadedFile): void;

    public function getById(UuidInterface $id): Image;
}
