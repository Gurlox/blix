<?php

declare(strict_types=1);

namespace App\Application\Command\CreateImage;

use App\Core\Command\Command;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class CreateImageCommand extends Command
{
    public function __construct(
        public string $id,
        public UploadedFile $image,
    ) {
    }
}
