<?php

declare(strict_types=1);

namespace Tests\Spec\App\Domain\Shared\ValueObject;

use App\Domain\Shared\ValueObject\BaseId;
use PhpSpec\ObjectBehavior;

/**
 * @mixin BaseId
 * @extends ObjectBehavior<BaseId, object>
 */
final class BaseIdSpec extends ObjectBehavior
{
    public function it_is_initializable(): void
    {
        $this->shouldHaveType(BaseId::class);
    }
}
