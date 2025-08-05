<?php

declare(strict_types=1);

namespace App\Infrastructure\LuckyNumber\Service;

use App\Domain\LuckyNumber\Port\LuckyNumberGeneratorInterface;
use Random\RandomException;

final class RandomLuckyNumberGenerator implements LuckyNumberGeneratorInterface
{
    /**
     * @throws RandomException
     */
    #[\Override]
    public function generate(int $min, int $max): int
    {
        return random_int($min, $max);
    }
}
