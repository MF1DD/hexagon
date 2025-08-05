<?php

declare(strict_types=1);

namespace Tests\Spec\App\Domain\LuckyNumber\Entity;

use App\Domain\LuckyNumber\Entity\LuckyNumber;
use PhpSpec\ObjectBehavior;

/**
 * @mixin LuckyNumber
 * @extends ObjectBehavior<LuckyNumber, object>
 */
final class LuckyNumberSpec extends ObjectBehavior
{
    public function it_is_initializable(): void
    {
        $this->shouldHaveType(LuckyNumber::class);
    }
}
