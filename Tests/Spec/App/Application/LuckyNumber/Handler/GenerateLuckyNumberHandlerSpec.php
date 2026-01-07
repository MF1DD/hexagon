<?php

declare(strict_types=1);

namespace Tests\Spec\App\Application\LuckyNumber\Handler;

use App\Application\LuckyNumber\Handler\GenerateLuckyNumberHandler;
use PhpSpec\ObjectBehavior;

/**
 * @mixin GenerateLuckyNumberHandler
 * @extends ObjectBehavior<GenerateLuckyNumberHandler, object>
 */
final class GenerateLuckyNumberHandlerSpec extends ObjectBehavior
{
    public function let(\App\Domain\LuckyNumber\Port\LuckyNumberDomainHandlerInterface $domainService): void
    {
        $this->beConstructedWith($domainService);
    }

    public function it_is_initializable(): void
    {
        $this->shouldHaveType(GenerateLuckyNumberHandler::class);
    }
}
