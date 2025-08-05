<?php

declare(strict_types=1);

namespace Tests\Spec\App\UserInterface\Adapter;

use App\UserInterface\Adapter\BinConsoleAdapter;
use PhpSpec\ObjectBehavior;

/** @extends ObjectBehavior<BinConsoleAdapter, object> */
final class BinConsoleAdapterSpec extends ObjectBehavior
{
    public function it_is_initializable(): void
    {
        $this->shouldHaveType(BinConsoleAdapter::class);
    }
}
