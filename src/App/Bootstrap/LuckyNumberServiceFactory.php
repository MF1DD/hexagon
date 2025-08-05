<?php

declare(strict_types=1);

namespace App\Bootstrap;

use App\Application\LuckyNumber\Handler\GenerateLuckyNumberHandler;
use App\Domain\LuckyNumber\Port\LuckyNumberGeneratorInterface;
use App\Domain\LuckyNumber\Service\LuckyNumberDomainHandler;
use App\Domain\Shared\Port\IdGeneratorInterface;
use App\Infrastructure\LuckyNumber\Service\RandomLuckyNumberGenerator;
use App\Infrastructure\Shared\Adapter\FakeIdGenerator;

final class LuckyNumberServiceFactory
{
    public static function createGenerateLuckyNumberHandler(
        ?IdGeneratorInterface $idGenerator = null,
        ?LuckyNumberGeneratorInterface $luckyNumberGenerator = null,
    ): GenerateLuckyNumberHandler {
        $domainService = new LuckyNumberDomainHandler(
            idGenerator: $idGenerator instanceof IdGeneratorInterface ? $idGenerator : new FakeIdGenerator(),
            luckyNumberGenerator: $luckyNumberGenerator instanceof LuckyNumberGeneratorInterface ?
                $luckyNumberGenerator :
                new RandomLuckyNumberGenerator(),
        );

        return new GenerateLuckyNumberHandler($domainService);
    }
}
