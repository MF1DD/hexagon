<?php

declare(strict_types=1);

namespace App\Domain\Shared\Port;

interface IdGeneratorInterface
{
    public function generate(): string;
}
