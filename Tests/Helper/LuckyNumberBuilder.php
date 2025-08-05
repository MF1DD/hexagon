<?php

declare(strict_types=1);

namespace Tests\Helper;

use App\Application\LuckyNumber\DTO\LuckyNumberOutput;
use App\Domain\LuckyNumber\Entity\LuckyNumber;
use App\Domain\LuckyNumber\ValueObject\LuckyNumberId;
use App\Domain\LuckyNumber\ValueObject\LuckyNumberValue;
use App\Infrastructure\Shared\Adapter\FakeIdGenerator;
use Random\RandomException;

final class LuckyNumberBuilder
{
    /**
     * @throws RandomException
     */
    public static function buildLuckyNumber(
        string|LuckyNumberId $id = 'lucky-number-id',
        int|LuckyNumberValue $number = 42,
    ): LuckyNumber {
        return new LuckyNumber(
            id: $id instanceof LuckyNumberId ? $id : self::buildLuckyNumberId($id),
            number: $number instanceof LuckyNumberValue ? $number : self::buildLuckyNumberValue($number),
        );
    }

    /**
     * @throws RandomException
     */
    public static function buildLuckyNumberValue(
        ?int $value = null,
    ): LuckyNumberValue {
        return new LuckyNumberValue(
            value: $value ?? random_int(
                LuckyNumberValue::LUCKY_NUMBER_MINIMUM_VALUE,
                LuckyNumberValue::LUCKY_NUMBER_MAXIMUM_VALUE
            )
        );
    }

    public static function buildLuckyNumberId(
        ?string $value = null,
    ): LuckyNumberId {
        $IdGenerator = new FakeIdGenerator();
        return new LuckyNumberId(
            value: $value ?? $IdGenerator->generate()
        );
    }

    public static function buildLuckyNumberOutput(
        int $luckyNumber = 21,
        bool $isLucky = true
    ): LuckyNumberOutput {
        return new LuckyNumberOutput(
            luckyNumber: $luckyNumber,
            isLucky: $isLucky
        );
    }
}
