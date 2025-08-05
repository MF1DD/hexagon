<?php

declare(strict_types=1);

namespace App\Domain\LuckyNumber\Port;

interface LuckyNumberGeneratorInterface
{
    public function generate(int $min, int $max): int;
}
