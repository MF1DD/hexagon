<?php

declare(strict_types=1);

namespace Tests\Unit\App\Application\LuckyNumber\DTO;

use PHPUnit\Framework\TestCase;
use Tests\Helper\LuckyNumberBuilder;

final class LuckyNumberOutputFactoryTest extends TestCase
{
    public function testLuckyNumberOutputToString(): void
    {
        $dto = LuckyNumberBuilder::buildLuckyNumberOutput(
            luckyNumber: 27,
            isLucky: false,
        );

        $dtoString = (string) $dto;

        self::assertSame(
            '{"luckyNumber":27,"isLucky":false}',
            $dtoString
        );
    }
}
