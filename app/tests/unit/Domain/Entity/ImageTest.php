<?php

declare(strict_types=1);

namespace tests\unit\Domain\Entity;

use App\Domain\Entity\Image;
use App\ValueObject\Uuid;
use Assert\InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ImageTest extends TestCase
{
    public function testCreateFromFileWithInvalidExtensionShouldThrowException(): void
    {
        // given
        $uploadedFile = $this->createMock(UploadedFile::class);

        // when
        $uploadedFile
            ->method('guessExtension')
            ->willReturn('png');
        $uploadedFile
            ->method('getSize')
            ->willReturn(500000);

        // then
        $this->expectException(InvalidArgumentException::class);
        Image::createFromFile(Uuid::create(), $uploadedFile);
    }

    public function testCreateFromFileWithInvalidFileSizeShouldThrowException(): void
    {
        // given
        $uploadedFile = $this->createMock(UploadedFile::class);

        // when
        $uploadedFile
            ->method('guessExtension')
            ->willReturn('jpg');
        $uploadedFile
            ->method('getSize')
            ->willReturn(1500000);

        // then
        $this->expectException(InvalidArgumentException::class);
        Image::createFromFile(Uuid::create(), $uploadedFile);
    }
}
