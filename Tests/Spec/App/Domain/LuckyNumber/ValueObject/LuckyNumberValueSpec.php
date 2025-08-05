<?php

declare(strict_types=1);

namespace Tests\Spec\App\Domain\LuckyNumber\ValueObject;

use App\Domain\LuckyNumber\ValueObject\LuckyNumberValue;
use PhpSpec\ObjectBehavior;

/**
 * @mixin LuckyNumberValue
 * @extends ObjectBehavior<LuckyNumberValue, object>
 */
final class LuckyNumberValueSpec extends ObjectBehavior
{
    public function it_is_initializable(): void
    {
        $this->shouldHaveType(LuckyNumberValue::class);
    }
}
