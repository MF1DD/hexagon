<?php

declare(strict_types=1);

namespace Tests\Spec\App\Application\LuckyNumber\DTO;

use App\Application\LuckyNumber\DTO\LuckyNumberOutput;
use PhpSpec\ObjectBehavior;

/**
 * @mixin LuckyNumberOutput
 * @extends ObjectBehavior<LuckyNumberOutput, object>
 */
final class LuckyNumberOutputSpec extends ObjectBehavior
{
    public function it_is_initializable(): void
    {
        $this->shouldHaveType(LuckyNumberOutput::class);
    }
}
