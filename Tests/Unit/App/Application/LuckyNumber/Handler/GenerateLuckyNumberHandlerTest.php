<?php

declare(strict_types=1);

namespace Tests\Unit\App\Application\LuckyNumber\Handler;

use App\Application\LuckyNumber\Handler\GenerateLuckyNumberHandler;
use App\Domain\LuckyNumber\Entity\LuckyNumber;
use App\Domain\LuckyNumber\Port\LuckyNumberDomainHandlerInterface;
use Override;
use PHPUnit\Framework\TestCase;
use Tests\Helper\LuckyNumberBuilder;

final class GenerateLuckyNumberHandlerTest extends TestCase
{
    public function testInvokeReturnsLuckyNumberOutput(): void
    {
        $fakeDomainService = new readonly class () implements LuckyNumberDomainHandlerInterface {
            #[Override]
            public function generate(): LuckyNumber
            {
                return LuckyNumberBuilder::buildLuckyNumber(
                    id: 'fake-id',
                    number: 43,
                );
            }
        };

        $handler = new GenerateLuckyNumberHandler($fakeDomainService);

        $result = $handler();

        $this->assertSame(43, $result->luckyNumber);
        $this->assertFalse($result->isLucky);
    }
}
