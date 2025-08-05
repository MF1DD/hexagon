<?php

declare(strict_types=1);

namespace Tests\Spec\App\UserInterface\CLI\LuckyNumber;

use App\UserInterface\CLI\LuckyNumber\LuckyNumberCommand;
use PhpSpec\ObjectBehavior;

/** @extends ObjectBehavior<LuckyNumberCommand, object> */
final class LuckyNumberCommandSpec extends ObjectBehavior
{
    public function it_is_initializable(): void
    {
        $this->shouldHaveType(LuckyNumberCommand::class);
    }
}
