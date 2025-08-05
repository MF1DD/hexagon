<?php

declare(strict_types=1);

namespace Tests\Spec\App\Bootstrap;

use App\Bootstrap\LuckyNumberServiceFactory;
use PhpSpec\ObjectBehavior;

/**
 * @mixin LuckyNumberServiceFactory
 * @extends ObjectBehavior<LuckyNumberServiceFactory, object>
 */
final class LuckyNumberServiceFactorySpec extends ObjectBehavior
{
    public function it_is_initializable(): void
    {
        $this->shouldHaveType(LuckyNumberServiceFactory::class);
    }
}
