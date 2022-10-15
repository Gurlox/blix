<?php

declare(strict_types=1);

namespace App\Domain\Entity;

use Assert\Assert;
use Doctrine\ORM\Mapping as ORM;
use App\ValueObject\UuidInterface;

#[ORM\Entity]
#[ORM\Table(name: 'posts')]
class Post
{
    private const ALLOWED_TEXT_HTML_TAGS = ['ul', 'li', 'ol', 'p', 'strong'];

    #[ORM\Id]
    #[ORM\Column(type: 'string')]
    private readonly UuidInterface $id;

    #[ORM\Column(type: 'string')]
    private string $title;

    #[ORM\Column(type: 'string')]
    private string $text;

    #[ORM\OneToOne(targetEntity: Image::class)]
    private Image $image;

    public function __construct(
        UuidInterface $id,
        string $title,
        string $text,
        Image $image,
    ) {
        $this->id = $id;
        $this->setTitle($title);
        $this->setText($text);
        $this->setImage($image);
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        Assert::that($title)->betweenLength(10, 80, 'Title length should be between 10 and 80');
        $this->title = strip_tags($title);
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function setText(string $text): void
    {
        Assert::that($text)->minLength(20, 'Text minimum length should be 20');
        $this->text = strip_tags($text, self::ALLOWED_TEXT_HTML_TAGS);
    }

    public function getImage(): Image
    {
        return $this->image;
    }

    public function setImage(Image $image): void
    {
        $this->image = $image;
    }
}
