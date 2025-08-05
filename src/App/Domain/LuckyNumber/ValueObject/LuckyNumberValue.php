<?php

declare(strict_types=1);

namespace App\Domain\LuckyNumber\ValueObject;

use InvalidArgumentException;

final class LuckyNumberValue
{
    public const int LUCKY_NUMBER_MINIMUM_VALUE = 1;

    public const int LUCKY_NUMBER_MAXIMUM_VALUE = 100;

    public const int LUCKY_MODULO = 7;

    public function __construct(
        public int $value
    ) {
        $this->assertValid($value);
    }

    public function isLucky(): bool
    {
        return $this->value % self::LUCKY_MODULO === 0;
    }

    /** @return array<int, int> */
    public static function range(): array
    {
        return [self::LUCKY_NUMBER_MINIMUM_VALUE, self::LUCKY_NUMBER_MAXIMUM_VALUE];
    }

    private function assertValid(int $value): void
    {
        if ($value < self::LUCKY_NUMBER_MINIMUM_VALUE || $value > self::LUCKY_NUMBER_MAXIMUM_VALUE) {
            throw new InvalidArgumentException('Lucky number must be between 1 and 100');
        }
    }
}
