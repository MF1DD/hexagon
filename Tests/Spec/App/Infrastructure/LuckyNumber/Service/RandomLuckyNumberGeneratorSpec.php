<?php

declare(strict_types=1);

namespace Tests\Spec\App\Infrastructure\LuckyNumber\Service;

use App\Infrastructure\LuckyNumber\Service\RandomLuckyNumberGenerator;
use PhpSpec\ObjectBehavior;

/**
 * @mixin RandomLuckyNumberGenerator
 * @extends ObjectBehavior<RandomLuckyNumberGenerator, object>
 */
final class RandomLuckyNumberGeneratorSpec extends ObjectBehavior
{
    public function it_is_initializable(): void
    {
        $this->shouldHaveType(RandomLuckyNumberGenerator::class);
    }
}
