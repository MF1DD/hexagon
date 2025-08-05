<?php

declare(strict_types=1);

namespace Tests\Spec\App\UserInterface\Adapter;

use App\UserInterface\Adapter\SlimWebAdapter;
use PhpSpec\ObjectBehavior;

/** @extends ObjectBehavior<SlimWebAdapter, object> */
final class SlimWebAdapterSpec extends ObjectBehavior
{
    public function it_is_initializable(): void
    {
        $this->shouldHaveType(SlimWebAdapter::class);
    }
}
