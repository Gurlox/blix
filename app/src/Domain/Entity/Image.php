<?php

declare(strict_types=1);

namespace App\Domain\Entity;

use App\Enum\ImageExtensionEnum;
use App\ValueObject\Uuid;
use App\ValueObject\UuidInterface;
use Assert\Assert;
use Assert\LazyAssertionException;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;

#[ORM\Entity]
#[ORM\Table(name: 'images')]
class Image
{
    #[ORM\Id]
    #[ORM\Column(type: 'string')]
    public UuidInterface $id;

    #[ORM\Column(type: 'string')]
    private string $originalFileName;

    #[ORM\Column(type: 'string', enumType: ImageExtensionEnum::class)]
    private ImageExtensionEnum $extension;

    #[ORM\Column(type: 'integer')]
    private int $size;

    public function __construct(
        UuidInterface $id,
        string $originalFileName,
        ImageExtensionEnum $extension,
        int $size,
    ) {
        $this->id = $id;
        $this->originalFileName = $originalFileName;
        $this->extension = $extension;
        $this->size = $size;
    }

    /**
     * @throws LazyAssertionException
     */
    public static function createFromFile(UploadedFile $file): self
    {
        Assert::lazy()
            ->that($file->guessExtension())->inArray(ImageExtensionEnum::availableValues(), 'Image has invalid extension')
            ->that($file->getSize() / 1000000)->max(1, 'Maximum file size is 1 mb')
            ->verifyNow();

        return new self(
            Uuid::create(),
            $file->getFilename(),
            ImageExtensionEnum::from($file->guessExtension()),
            $file->getSize(),
        );
    }

    public function getExtension(): ImageExtensionEnum
    {
        return $this->extension;
    }

    public function getSize(): int
    {
        return $this->size;
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getOriginalFileName(): string
    {
        return $this->originalFileName;
    }
}
