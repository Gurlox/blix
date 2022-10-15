<?php

declare(strict_types=1);

namespace App\Application\Command\CreatePost;

use App\Core\Command\Command;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class CreatePostCommand extends Command
{
    public function __construct(
        public readonly string $title,
        public readonly string $text,
        public UploadedFile $image,
    ) {
    }
}
