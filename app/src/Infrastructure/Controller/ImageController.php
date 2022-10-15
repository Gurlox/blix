<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller;

use App\Application\Command\CreateImage\CreateImageCommand;
use App\Core\Command\CommandBus;
use App\ValueObject\Uuid;
use Assert\InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Annotations as OA;

/**
 * @OA\Tag(name="Image")
 */
class ImageController
{
    public function __construct(
        private CommandBus $commandBus,
    ) {
    }

    #[Route(path: '/api/image', name: 'upload_image', methods: [Request::METHOD_POST])]
    /**
     * @OA\RequestBody(
     *      @OA\MediaType(
     *          mediaType="multipart/form-data",
     *          @OA\Schema(allOf={
     *              @OA\Schema(
     *                  @OA\Property(
     *                      description="File",
     *                      property="file",
     *                      type="string",
     *                      format="binary"
     *                  )
     *              )
     *          })
     *      )
     * )
     * @OA\Response(
     *     response=201,
     *     description="CREATED"
     * )
     */
    public function uploadImageAction(Request $request): JsonResponse
    {
        /** @var ?UploadedFile $file */
        $file = $request->files->get('file');

        if (null === $file) {
            return new JsonResponse(['message' => 'File not provided'], Response::HTTP_BAD_REQUEST);
        }

        $id = Uuid::create();

        try {
            $this->commandBus->handle(new CreateImageCommand($id->toString(), $file));
        } catch (InvalidArgumentException $exception) {
            return new JsonResponse(['message' => $exception->getMessage()], Response::HTTP_BAD_REQUEST);
        }

        return new JsonResponse(['id' => $id->toString()], Response::HTTP_CREATED);
    }
}
