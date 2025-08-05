<?php

declare(strict_types=1);

namespace Tests\Spec\App\UserInterface\Web\LuckyNumber;

use App\UserInterface\Web\LuckyNumber\LuckyNumberController;
use PhpSpec\ObjectBehavior;

/**
 * @extends ObjectBehavior<LuckyNumberController, object>
 */
final class LuckyNumberControllerSpec extends ObjectBehavior
{
    public function it_is_initializable(): void
    {
        $this->shouldHaveType(LuckyNumberController::class);
    }
}
