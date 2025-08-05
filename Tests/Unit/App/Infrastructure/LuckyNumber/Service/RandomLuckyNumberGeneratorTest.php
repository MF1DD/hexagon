<?php

declare(strict_types=1);

namespace Tests\Unit\App\Infrastructure\LuckyNumber\Service;

use App\Infrastructure\LuckyNumber\Service\RandomLuckyNumberGenerator;
use PHPUnit\Framework\TestCase;
use Random\RandomException;
use ValueError;

final class RandomLuckyNumberGeneratorTest extends TestCase
{
    /**
     * @throws RandomException
     */
    public function testInitialClass(): void
    {
        $min = 1;
        $max = 2;

        $dto = new RandomLuckyNumberGenerator();
        $generatedNumber = $dto->generate(min: $min, max: $max);

        self::assertTrue($generatedNumber <= $max);
        self::assertTrue($generatedNumber >= $min);
    }

    /**
     * @throws RandomException
     */
    public function testMinGivenAsMaxAndMaxGivenAsMin(): void
    {
        $this->expectException(ValueError::class);
        $this->expectExceptionMessage('Argument #1 ($min) must be less than or equal to argument #2 ($max)');

        $dto = new RandomLuckyNumberGenerator();
        $dto->generate(min: 5, max: 1);
    }
}
