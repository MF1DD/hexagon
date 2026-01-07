<?php

declare(strict_types=1);

namespace Tests\Spec\App\Domain\LuckyNumber\ValueObject;

use App\Domain\LuckyNumber\ValueObject\LuckyNumberId;
use PhpSpec\ObjectBehavior;

/**
 * @mixin LuckyNumberId
 * @extends ObjectBehavior<LuckyNumberId, object>
 */
final class LuckyNumberIdSpec extends ObjectBehavior
{
    public function let(): void
    {
        $this->beConstructedWith('test-id');
    }

    public function it_is_initializable(): void
    {
        $this->shouldHaveType(LuckyNumberId::class);
    }
}
