<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller;

use App\Application\Query\GetPostById\GetPostByIdQuery;
use App\Application\Query\GetPostsList\GetPostsListQuery;
use App\Core\Command\CommandBus;
use App\Core\Query\QueryBus;
use App\ValueObject\Uuid;
use Assert\Assert;
use Assert\InvalidArgumentException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Annotations as OA;
use App\Application\Command\CreatePost\CreatePostCommand;
use App\Application\DTO\PostReadDTO;
use Nelmio\ApiDocBundle\Annotation\Model;
use App\Application\DTO\PostListReadDTO;

/**
 * @OA\Tag(name="Post")
 */
class PostRESTController
{
    public function __construct(
        private CommandBus $commandBus,
        private QueryBus $queryBus,
    ) {
    }

    #[Route('/api/posts', name: 'create_post', methods: ['POST'])]
    /**
     * @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(
     *         example={
     *             "title": "string",
     *             "text": "string",
     *             "imageId": "uuid"
     *         },
     *         @OA\Schema (
     *              type="object",
     *              @OA\Property(property="title", required=true, description="Event Status", type="string"),
     *              @OA\Property(property="text", required=true, description="Event Status", type="string"),
     *              @OA\Property(property="imageId", required=true, description="Event Status", type="string")
     *         )
     *     )
     * )
     * @OA\Response(
     *     response=201,
     *     description="CREATED"
     * )
     */
    public function createPost(Request $request): JsonResponse
    {
        $requestData = $request->toArray();

        try {
            Assert::lazy()
                ->that($requestData)->keyIsset('title', 'title key must be provided')
                ->that($requestData)->keyIsset('text', 'text key must be provided')
                ->that($requestData)->keyIsset('imageId', 'imageId key must be provided')
                ->verifyNow();

            Assert::that($requestData['imageId'])->uuid('imageId is invalid uuid');
        } catch (InvalidArgumentException $exception) {
            return new JsonResponse(['message' => $exception->getMessage()], Response::HTTP_BAD_REQUEST);
        }

        $id = Uuid::create();

        try {
            $this->commandBus->handle(
                new CreatePostCommand(
                    $id->toString(),
                    $requestData['title'],
                    $requestData['text'],
                    $requestData['imageId'],
                )
            );
        } catch (InvalidArgumentException $exception) {
            return new JsonResponse(['message' => $exception->getMessage()], Response::HTTP_BAD_REQUEST);
        }

        return new JsonResponse(['postId' => $id->toString()], Response::HTTP_CREATED);
    }

    #[Route('/api/posts/list', name: 'get_posts', methods: ['GET'])]
    /**
     * @OA\Parameter(
     *     name="page",
     *     in="query",
     *     @OA\Schema(type="string")
     * )
     * @OA\Parameter(
     *     name="perPage",
     *     in="query",
     *     @OA\Schema(type="string")
     * )
     *
     * @OA\Response(
     *     response=200,
     *     description="OK",
     *     @Model(type=PostListReadDTO::class)
     * )
     */
    public function getPostsList(Request $request): JsonResponse
    {
        $page = $request->query->get('page');
        $perPage = $request->query->get('perPage');

        try {
            Assert::lazy()
                ->that($page)->numeric()
                ->that($perPage)->numeric()
                ->verifyNow();
        } catch (InvalidArgumentException $exception) {
            return new JsonResponse(['message' => $exception->getMessage()], Response::HTTP_BAD_REQUEST);
        }

        return new JsonResponse(
            $this->queryBus->handle(
                new GetPostsListQuery(
                    (int) $page,
                    (int) $perPage,
                )
            )
        );
    }

    #[Route('/api/posts/{id}', name: 'get_post', methods: ['GET'])]
    /**
     * @OA\Response(
     *     @Model(type=PostReadDTO::class),
     *     response=200,
     *     description="OK"
     * )
     */
    public function getPostById(string $id): JsonResponse
    {
        try {
            Assert::that($id)->uuid('id is invalid uuid');
        } catch (InvalidArgumentException $exception) {
            return new JsonResponse(['message' => $exception->getMessage()], Response::HTTP_BAD_REQUEST);
        }

        return new JsonResponse($this->queryBus->handle(new GetPostByIdQuery($id)));
    }
}
