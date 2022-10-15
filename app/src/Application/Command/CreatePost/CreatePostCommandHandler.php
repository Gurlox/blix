<?php

declare(strict_types=1);

namespace App\Application\Command\CreatePost;

use App\Application\Repository\ImageRepositoryInterface;
use App\Application\Repository\PostRepositoryInterface;
use App\Core\Command\CommandHandler;
use App\Domain\Entity\Image;
use App\Domain\Entity\Post;
use App\ValueObject\Uuid;

class CreatePostCommandHandler implements CommandHandler
{
    public function __construct(
        private PostRepositoryInterface $postRepository,
        private ImageRepositoryInterface $imageRepository,
    ) {
    }

    public function __invoke(CreatePostCommand $command): void
    {
        $image = $this->imageRepository->getById(Uuid::create($command->imageId));
        $this->postRepository->save(new Post(Uuid::create($command->id), $command->title, $command->text, $image));
    }
}
