<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistance\Doctrine;

use App\Application\Repository\PostRepositoryInterface;
use App\Domain\Entity\Post;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class PostRepository extends ServiceEntityRepository implements PostRepositoryInterface
{
    public function __construct(
        ManagerRegistry $managerRegistry,
    ) {
        parent::__construct($managerRegistry, Post::class);
    }

    public function save(Post $post): void
    {
        $this->getEntityManager()->persist($post);
        $this->getEntityManager()->flush();
    }
}
