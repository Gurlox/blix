<?php

declare(strict_types=1);

namespace App\Application\Repository;

use App\Domain\Entity\Image;
use Symfony\Component\HttpFoundation\File\UploadedFile;

interface ImageRepositoryInterface
{
    public function save(Image $image, UploadedFile $uploadedFile): void;
}
