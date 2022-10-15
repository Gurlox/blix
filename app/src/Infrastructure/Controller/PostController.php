<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller;

use App\Application\Command\CreateImage\CreateImageCommand;
use App\Application\Command\CreatePost\CreatePostCommand;
use App\Core\Command\CommandBus;
use App\Infrastructure\Form\DTO\PostDTO;
use App\Instrastructure\Form\PostForm;
use App\ValueObject\Uuid;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Exception;

class PostController extends AbstractController
{
    public function __construct(
        private CommandBus $commandBus,
    ) {
    }

    #[Route('/', name: 'post_form')]
    public function form(Request $request): Response
    {
        $success = false;
        $data = new PostDTO();

        $form = $this->createForm(PostForm::class, $data);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var PostDTO $formData */
            $formData = $form->getData();

            try {
                $imageId = Uuid::create();
                $this->commandBus->handle(
                    new CreateImageCommand(
                        $imageId->toString(),
                        $formData->image,
                    ),
                );
                $this->commandBus->handle(
                    new CreatePostCommand(
                        Uuid::create()->toString(),
                        $formData->title,
                        $formData->text,
                        $imageId->toString(),
                    )
                );
                $success = true;
            } catch (Exception $exception) {
                $form->addError(new FormError($exception->getMessage()));
            }
        }

        return $this->renderForm('form.html.twig', [
            'form' => $form,
            'success' => $success,
        ]);
    }
}
