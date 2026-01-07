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
    public function let(): void
    {
        $this->beConstructedWith(
            new \App\Domain\LuckyNumber\ValueObject\LuckyNumberId('id'),
            new \App\Domain\LuckyNumber\ValueObject\LuckyNumberValue(1)
        );
    }

    public function it_is_initializable(): void
    {
        $this->shouldHaveType(LuckyNumber::class);
    }
}
