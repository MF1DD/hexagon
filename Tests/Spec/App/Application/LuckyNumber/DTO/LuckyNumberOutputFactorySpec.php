<?php

declare(strict_types=1);

namespace Tests\Spec\App\Application\LuckyNumber\DTO;

use App\Application\LuckyNumber\DTO\LuckyNumberOutputFactory;
use PhpSpec\ObjectBehavior;

/**
 * @mixin LuckyNumberOutputFactory
 * @extends ObjectBehavior<LuckyNumberOutputFactory, object>
 */
final class LuckyNumberOutputFactorySpec extends ObjectBehavior
{
    public function it_is_initializable(): void
    {
        $this->shouldHaveType(LuckyNumberOutputFactory::class);
    }
}
