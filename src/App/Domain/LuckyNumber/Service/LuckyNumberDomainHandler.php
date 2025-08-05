<?php

declare(strict_types=1);

namespace App\Domain\LuckyNumber\Service;

use App\Domain\LuckyNumber\Entity\LuckyNumber;
use App\Domain\LuckyNumber\Port\LuckyNumberDomainHandlerInterface;
use App\Domain\LuckyNumber\Port\LuckyNumberGeneratorInterface;
use App\Domain\LuckyNumber\ValueObject\LuckyNumberId;
use App\Domain\LuckyNumber\ValueObject\LuckyNumberValue;
use App\Domain\Shared\Port\IdGeneratorInterface;
use Override;

final readonly class LuckyNumberDomainHandler implements LuckyNumberDomainHandlerInterface
{
    public function __construct(
        private IdGeneratorInterface $idGenerator,
        private LuckyNumberGeneratorInterface $luckyNumberGenerator,
    ) {
    }

    #[Override]
    public function generate(): LuckyNumber
    {
        return new LuckyNumber(
            id: new LuckyNumberId(
                $this->idGenerator->generate()
            ),
            number: new LuckyNumberValue(
                $this->luckyNumberGenerator->generate(
                    ...LuckyNumberValue::range()
                )
            )
        );
    }
}
