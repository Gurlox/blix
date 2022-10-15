<?php

declare(strict_types=1);

namespace App\Infrastructure\Form\DTO;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

class PostDTO
{
    #[Assert\NotNull]
    #[Assert\NotBlank]
    #[Assert\Length(min: 10, max: 80)]
    public ?string $title = null;

    #[Assert\NotNull]
    #[Assert\NotBlank]
    #[Assert\Length(min: 20)]
    public ?string $text = null;

    #[Assert\NotNull]
    public ?UploadedFile $image = null;
}
