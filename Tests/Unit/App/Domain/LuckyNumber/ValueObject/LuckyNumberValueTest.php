<?php

declare(strict_types=1);

namespace Tests\Unit\App\Domain\LuckyNumber\ValueObject;

use App\Domain\LuckyNumber\ValueObject\LuckyNumberValue;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class LuckyNumberValueTest extends TestCase
{
    /** @return array<string, array<string, int|bool>> */
    public static function luckyAndNotLuckyNumbers(): array
    {
        return [
            'isLucky' => ['number' => LuckyNumberValue::LUCKY_MODULO, 'expect' => true],
            'isAlsoLucky' => ['number' => LuckyNumberValue::LUCKY_MODULO * 2, 'expect' => true],
            'notLucky' => ['number' => LuckyNumberValue::LUCKY_MODULO + 1, 'expect' => false],
        ];
    }

    public function testInvalidMinimumValueThrowException(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Lucky number must be between 1 and 100');

        $dto = new LuckyNumberValue(
            LuckyNumberValue::LUCKY_NUMBER_MINIMUM_VALUE - 1
        );

        self::fail('Did not throw exception: ' . $dto::class);
    }

    public function testInvalidMaximumValueThrowException(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Lucky number must be between 1 and 100');

        $dto = new LuckyNumberValue(
            LuckyNumberValue::LUCKY_NUMBER_MAXIMUM_VALUE + 1
        );

        self::fail('Did not throw exception: ' . $dto::class);
    }

    public function testMinimumAndMaximumAreValid(): void
    {
        $this->expectNotToPerformAssertions();

        new LuckyNumberValue(
            LuckyNumberValue::LUCKY_NUMBER_MAXIMUM_VALUE
        );
        new LuckyNumberValue(
            LuckyNumberValue::LUCKY_NUMBER_MINIMUM_VALUE
        );
    }

    #[DataProvider('luckyAndNotLuckyNumbers')]
    public function testIsLuckyAndNotLucky(int $number, bool $expect): void
    {
        $dto = new LuckyNumberValue($number);
        if ($expect) {
            $this->assertTrue($dto->isLucky());
        } else {
            $this->assertFalse($dto->isLucky());
        }
    }

    public function testRangeReturnsListOfMinAndMax(): void
    {
        [$min, $max] = LuckyNumberValue::range();

        $this->assertEquals(
            LuckyNumberValue::LUCKY_NUMBER_MINIMUM_VALUE,
            $min
        );
        $this->assertEquals(
            LuckyNumberValue::LUCKY_NUMBER_MAXIMUM_VALUE,
            $max
        );
    }
}
