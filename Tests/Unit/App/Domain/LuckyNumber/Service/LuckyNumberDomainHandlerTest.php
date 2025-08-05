<?php

declare(strict_types=1);

namespace Tests\Unit\App\Domain\LuckyNumber\Service;

use App\Domain\LuckyNumber\Port\LuckyNumberGeneratorInterface;
use App\Domain\LuckyNumber\Service\LuckyNumberDomainHandler;
use App\Domain\Shared\Port\IdGeneratorInterface;
use Override;
use PHPUnit\Framework\TestCase;

final class LuckyNumberDomainHandlerTest extends TestCase
{
    public function testGenerateReturnsLuckyNumberWithExpectedValues(): void
    {
        $dto = new LuckyNumberDomainHandler(
            $this->getIdGenerator(),
            $this->getLuckyNumberGenerator(),
        );

        $luckyNumber = $dto->generate();

        $this->assertSame('test-id-123', (string) $luckyNumber->id);
        $this->assertSame(42, $luckyNumber->number->value);
    }

    private function getIdGenerator(): IdGeneratorInterface
    {
        return new class () implements IdGeneratorInterface {
            #[Override]
            public function generate(): string
            {
                return 'test-id-123';
            }
        };
    }

    private function getLuckyNumberGenerator(): LuckyNumberGeneratorInterface
    {
        return new class () implements LuckyNumberGeneratorInterface {
            #[Override]
            public function generate(int $min, int $max): int
            {
                return 42;
            }
        };
    }
}
