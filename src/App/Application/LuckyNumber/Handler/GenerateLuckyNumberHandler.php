<?php

declare(strict_types=1);

namespace App\Application\LuckyNumber\Handler;

use App\Application\LuckyNumber\DTO\LuckyNumberOutput;
use App\Application\LuckyNumber\DTO\LuckyNumberOutputFactory;
use App\Domain\LuckyNumber\Port\LuckyNumberDomainHandlerInterface;

final readonly class GenerateLuckyNumberHandler
{
    public function __construct(
        private LuckyNumberDomainHandlerInterface $domainService,
    ) {
    }

    public function __invoke(): LuckyNumberOutput
    {
        $luckyNumber = $this->domainService->generate();

        return LuckyNumberOutputFactory::fromDomain($luckyNumber);
    }
}
