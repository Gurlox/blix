<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistance\Doctrine;

use App\Application\DTO\ImageReadDTO;
use App\Application\DTO\PostReadDTO;
use App\Application\Repository\PostReadRepositoryInterface;
use App\Domain\Entity\Post;
use App\ValueObject\Paging;
use App\ValueObject\UuidInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PostReadRepository extends ServiceEntityRepository implements PostReadRepositoryInterface
{
    public function __construct(
        ManagerRegistry $managerRegistry,
        private RequestStack $requestStack,
    ) {
        parent::__construct($managerRegistry, Post::class);
    }
    public function getById(UuidInterface $id): PostReadDTO
    {
        /** @var ?Post $post */
        $post = $this->getEntityManager()->getRepository(Post::class)->find($id);

        if (null === $post) {
            throw new NotFoundHttpException('Post not found');
        }

        return $this->createPostReadDTO($post);
    }

    public function getPaginatedList(Paging $paging): array
    {
        $posts = $this->getEntityManager()->getRepository(Post::class)
            ->createQueryBuilder('p')
            ->select('p')
            ->setFirstResult($paging->getOffset())
            ->setMaxResults($paging->getLimit())
            ->getQuery()
            ->getResult();

        $result = [];

        foreach ($posts as $post) {
            $result[] = $this->createPostReadDTO($post);
        }

        return $result;
    }

    public function countAll(): int
    {
        return $this->getEntityManager()->getRepository(Post::class)
            ->createQueryBuilder('p')
            ->select('COUNT(p)')
            ->getQuery()
            ->getSingleScalarResult();
    }

    private function createPostReadDTO(Post $post): PostReadDTO
    {
        $image = $post->getImage();

        return new PostReadDTO(
            $post->getId()->toString(),
            $post->getTitle(),
            $post->getText(),
            new ImageReadDTO(
                $image->getId()->toString(),
                $image->getOriginalFileName(),
                $image->getExtension()->value,
                $image->getSize(),
                sprintf("%s/images/%s", $this->requestStack->getCurrentRequest()->getHttpHost(), $image->getFileName()),
            ),
        );
    }
}
