<?php

declare(strict_types=1);

namespace App\Domain\LuckyNumber\Port;

use App\Domain\LuckyNumber\Entity\LuckyNumber;

interface LuckyNumberDomainHandlerInterface
{
    public function generate(): LuckyNumber;
}
