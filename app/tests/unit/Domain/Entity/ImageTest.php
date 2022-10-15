<?php

declare(strict_types=1);

namespace App\Domain\Entity;

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
        Image::createFromFile($uploadedFile);
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
        Image::createFromFile($uploadedFile);
    }
}
