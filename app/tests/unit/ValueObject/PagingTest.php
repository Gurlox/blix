<?php

declare(strict_types=1);

namespace tests\unit\ValueObject;

use App\ValueObject\Paging;
use Assert\InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class PagingTest extends TestCase
{
    /**
     * @dataProvider invalidDataProvider
     */
    public function testCreateWithInvalidDataShouldThrowException(int $page, int $perPage): void
    {
        $this->expectException(InvalidArgumentException::class);
        new Paging($page, $perPage);
    }

    public function testCreateWithValidDataShouldSucceed(): void
    {
        // given
        $page = 2;
        $perPage = 10;

        // when then
        $paging = new Paging($page, $perPage);
        $this->assertEquals($page, $paging->page);
        $this->assertEquals($perPage, $paging->perPage);
        $this->assertEquals(10, $paging->getOffset());
        $this->assertEquals($perPage, $paging->getLimit());
    }

    public function invalidDataProvider(): array
    {
        return [
            [-10, 1],
            [1, -30],
            [0, 1],
            [1, 0],
        ];
    }
}
