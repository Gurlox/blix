<?php

declare(strict_types=1);

namespace tests\unit\ValueObject;

use App\ValueObject\Uuid;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Exception\InvalidUuidStringException;

class UuidTest extends TestCase
{
    /**
     * @dataProvider invalidDataProvider
     */
    public function testWithInvalidDataShouldFail(string $value): void
    {
        $this->expectException(InvalidUuidStringException::class);
        Uuid::create($value);
    }

    public function invalidDataProvider(): array
    {
        return [
            ['invalid_uuid'],
            [''],
        ];
    }
}
