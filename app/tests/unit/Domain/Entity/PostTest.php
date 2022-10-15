<?php

declare(strict_types=1);

namespace tests\unit\Domain\Entity;

use App\ValueObject\Uuid;
use App\ValueObject\UuidInterface;
use Assert\InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class PostTest extends TestCase
{
    /**
     * @dataProvider invalidArgumentsProvider
     */
    public function testCreatePostWithInvalidDataShouldFail(UuidInterface $id, string $title, string $description, Image $image): void
    {
        $this->expectException(InvalidArgumentException::class);
        new Post($id, $title, $description, $image);
    }

    public function testCreatePostWithHtmlTagsShouldStripThem(): void
    {
        // given
        $title = '<p>Cool</p> <br><strong>title</strong>';
        $description = '<html><ul><li><u>point</u></li></ul></html>';

        // when then
        $post = new Post(Uuid::create(), $title, $description, $this->createMock(Image::class));

        $this->assertEquals('Cool title', $post->getTitle());
        $this->assertEquals('<ul><li>point</li></ul>', $post->getText());
    }

    public function invalidArgumentsProvider(): array
    {
        return [
            [Uuid::create(), 'short', 'lorem ipsum lorem ipsum lorem ipsum', $this->createMock(Image::class)],
            [Uuid::create(), '123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890', 'lorem ipsum lorem ipsum lorem ipsum', $this->createMock(Image::class)],
            [Uuid::create(), '1234567890', 'short', $this->createMock(Image::class)],
        ];
    }
}
