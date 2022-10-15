<?php

declare(strict_types=1);

namespace App\Application\Command\CreateImage;

use App\Application\Repository\ImageRepositoryInterface;
use App\Core\Command\CommandHandler;
use App\Domain\Entity\Image;
use App\ValueObject\Uuid;

class CreateImageCommandHandler implements CommandHandler
{
    public function __construct(
        private ImageRepositoryInterface $imageRepository,
    ) {
    }

    public function __invoke(CreateImageCommand $command): void
    {
        $this->imageRepository->save(
            Image::createFromFile(Uuid::create($command->id), $command->image),
            $command->image
        );
    }
}
