<?php

declare(strict_types=1);

namespace App\Application\LuckyNumber\DTO;

use JsonException;
use Utils\PHPStan\Attribute\NoTestNeeded;

#[NoTestNeeded]
final readonly class LuckyNumberOutput implements \Stringable
{
    public function __construct(
        public int $luckyNumber,
        public bool $isLucky,
    ) {
    }

    /** @throws JsonException */
    #[\Override]
    public function __toString(): string
    {
        return json_encode((array) $this, JSON_THROW_ON_ERROR);
    }
}
