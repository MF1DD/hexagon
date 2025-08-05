<?php

declare(strict_types=1);

namespace Tests\Unit\App\Bootstrap;

use App\Bootstrap\LuckyNumberServiceFactory;
use App\Domain\LuckyNumber\Port\LuckyNumberGeneratorInterface;
use App\Domain\Shared\Port\IdGeneratorInterface;
use Override;
use PHPUnit\Framework\TestCase;

final class LuckyNumberServiceFactoryTest extends TestCase
{
    public function testCreateGenerateLuckyNumberHandler(): void
    {
        $idGenerator = new class () implements IdGeneratorInterface {
            #[Override]
            public function generate(): string
            {
                return 'generated-id';
            }
        };

        $luckyNumberGenerator = new class () implements LuckyNumberGeneratorInterface {
            #[Override]
            public function generate(int $min, int $max): int
            {
                return 42;
            }
        };

        $dto = LuckyNumberServiceFactory::createGenerateLuckyNumberHandler(
            idGenerator: $idGenerator,
            luckyNumberGenerator: $luckyNumberGenerator,
        );

        self::assertSame(42, $dto()->luckyNumber);
        self::assertTrue($dto()->isLucky);
    }
}
