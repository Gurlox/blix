<?php

declare(strict_types=1);

namespace App\Infrastructure\CLI;

use App\Application\Command\CreateImage\CreateImageCommand;
use App\Application\Command\CreatePost\CreatePostCommand;
use App\Core\Command\CommandBus;
use App\ValueObject\Uuid;
use Assert\Assert;
use Assert\InvalidArgumentException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

final class CreatePostCLI extends Command
{
    protected static $defaultName = 'app:create-post';

    protected static $defaultDescription = 'Creates post';

    public function __construct(
        private CommandBus $commandBus,
        ?string $name = null
    ) {
        parent::__construct($name);
    }
    
    protected function configure(): void
    {
        $this
            ->addArgument('title', InputArgument::REQUIRED, 'Post title')
            ->addArgument('text', InputArgument::REQUIRED, 'Post text')
            ->addArgument('imagePath', InputArgument::REQUIRED, 'Image path')
        ;
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $title = $input->getArgument('title');
        $text = $input->getArgument('text');
        $imagePath = $input->getArgument('imagePath');

        if (false === file_exists($imagePath)) {
            $output->write('Image does not exist');

            return Command::FAILURE;
        }

        try {
            Assert::lazy()
                ->that($title)->notBlank()
                ->that($text)->notBlank()
                ->verifyNow();

            $tmpFilePath = '/tmp/' . basename($imagePath);

            file_put_contents($tmpFilePath, file_get_contents($imagePath));

            $uploadedFile = new UploadedFile(
                $tmpFilePath,
                basename($imagePath),
                null,
                null,
                true,
            );

            $imageId = Uuid::create();
            $this->commandBus->handle(new CreateImageCommand($imageId->toString(), $uploadedFile));
            $this->commandBus->handle(new CreatePostCommand(Uuid::create()->toString(), $title, $text, $imageId->toString()));

            $output->writeln('Post created');
            return Command::SUCCESS;
        } catch (InvalidArgumentException $exception) {
            $output->write($exception->getMessage());

            return Command::FAILURE;
        }
    }
}
