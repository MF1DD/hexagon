<?php

declare(strict_types=1);

namespace Tests\Spec\App\Domain\LuckyNumber\Service;

use App\Domain\LuckyNumber\Service\LuckyNumberDomainHandler;
use PhpSpec\ObjectBehavior;

/**
 * @mixin LuckyNumberDomainHandler
 * @extends ObjectBehavior<LuckyNumberDomainHandler, object>
 */
final class LuckyNumberDomainHandlerSpec extends ObjectBehavior
{
    public function let(\App\Domain\Shared\Port\IdGeneratorInterface $idGenerator, \App\Domain\LuckyNumber\Port\LuckyNumberGeneratorInterface $luckyNumberGenerator): void
    {
        $this->beConstructedWith($idGenerator, $luckyNumberGenerator);
    }

    public function it_is_initializable(): void
    {
        $this->shouldHaveType(LuckyNumberDomainHandler::class);
    }
}
