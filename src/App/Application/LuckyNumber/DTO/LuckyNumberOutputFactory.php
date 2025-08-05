<?php

declare(strict_types=1);

namespace App\Application\LuckyNumber\DTO;

use App\Domain\LuckyNumber\Entity\LuckyNumber;

final class LuckyNumberOutputFactory
{
    public static function fromDomain(LuckyNumber $luckyNumber): LuckyNumberOutput
    {
        return new LuckyNumberOutput(
            $luckyNumber->number->value,
            $luckyNumber->number->isLucky()
        );
    }
}
