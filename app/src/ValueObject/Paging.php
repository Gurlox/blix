<?php

declare(strict_types=1);

namespace App\ValueObject;

use Assert\Assert;

class Paging
{
    public readonly int $page;

    public readonly int $perPage;

    public function __construct(
        int $page,
        int $perPage,
    ) {
        Assert::lazy()
            ->that($page)->min(1)
            ->that($perPage)->min(1)
            ->verifyNow();

        $this->page = $page;
        $this->perPage = $perPage;
    }

    public function getOffset(): int
    {
        return ($this->page - 1) * $this->perPage;
    }

    public function getLimit(): int
    {
        return $this->perPage;
    }
}
