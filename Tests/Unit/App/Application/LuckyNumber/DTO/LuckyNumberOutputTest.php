<?php

declare(strict_types=1);

namespace Tests\Unit\App\Application\LuckyNumber\DTO;

use App\Application\LuckyNumber\DTO\LuckyNumberOutputFactory;
use PHPUnit\Framework\TestCase;
use Tests\Helper\LuckyNumberBuilder;

final class LuckyNumberOutputTest extends TestCase
{
    public function testCreateLuckyNumberOutputFromDomain(): void
    {
        $luckyNumber = LuckyNumberBuilder::buildLuckyNumber(
            id: 'test-id-123',
            number: 21,
        );

        $dto = LuckyNumberOutputFactory::fromDomain($luckyNumber);

        self::assertSame(21, $dto->luckyNumber);
        self::assertTrue($dto->isLucky);
    }
}
