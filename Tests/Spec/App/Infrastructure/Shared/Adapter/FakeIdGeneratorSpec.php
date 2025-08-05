<?php

declare(strict_types=1);

namespace Tests\Spec\App\Infrastructure\Shared\Adapter;

use App\Infrastructure\Shared\Adapter\FakeIdGenerator;
use PhpSpec\ObjectBehavior;

/**
 * @mixin FakeIdGenerator
 * @extends ObjectBehavior<FakeIdGenerator, object>
 */
final class FakeIdGeneratorSpec extends ObjectBehavior
{
    public function it_is_initializable(): void
    {
        $this->shouldHaveType(FakeIdGenerator::class);
    }

    public function it_returns_a_fake_id(): void
    {
        $this->generate()->shouldReturn('fake-id-1');
        $this->generate()->shouldReturn('fake-id-2');
        $this->generate()->shouldReturn('fake-id-3');
    }

    public function it_can_be_reset(): void
    {
        $this->generate()->shouldReturn('fake-id-1');
        $this->generate()->shouldReturn('fake-id-2');
        $this->reset();
        $this->generate()->shouldReturn('fake-id-1');
    }
}
