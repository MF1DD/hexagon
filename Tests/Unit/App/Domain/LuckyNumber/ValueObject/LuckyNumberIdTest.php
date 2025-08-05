<?php

declare(strict_types=1);

namespace Tests\Unit\App\Domain\LuckyNumber\ValueObject;

use App\Domain\LuckyNumber\ValueObject\LuckyNumberId;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class LuckyNumberIdTest extends TestCase
{
    public function testInitialClass(): void
    {
        $dto = new LuckyNumberId('SomeId');
        self::assertInstanceOf(LuckyNumberId::class, $dto);
    }

    public function testWrongValueTrowAnException(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $dto = new LuckyNumberId('');

        self::fail('Did not throw exception: ' . $dto::class);
    }

    public function testEqualsReturnsTrueValue(): void
    {
        $dto = new LuckyNumberId('SomeId');
        $sameId = new LuckyNumberId('SomeId');
        $otherId = new LuckyNumberId('OtherId');

        self::assertTrue($dto->equals($sameId));
        self::assertFalse($dto->equals($otherId));
    }

    public function testReturnValueAsString(): void
    {
        $dto = new LuckyNumberId('SomeId');
        self::assertStringContainsString(
            'SomeId',
            (string) $dto,
        );
    }
}
