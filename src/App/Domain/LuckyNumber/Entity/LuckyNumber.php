<?php

declare(strict_types=1);

namespace App\Domain\LuckyNumber\Entity;

use App\Domain\LuckyNumber\ValueObject\LuckyNumberId;
use App\Domain\LuckyNumber\ValueObject\LuckyNumberValue;
use Utils\PHPStan\Attribute\NoTestNeeded;

/** @psalm-suppress PossiblyUnusedProperty */
#[NoTestNeeded]
final readonly class LuckyNumber
{
    public function __construct(
        public LuckyNumberId $id,
        public LuckyNumberValue $number,
    ) {
    }
}
