<?php

declare(strict_types=1);

namespace Tests\PHPStan\RuleTest\Fixtures\src;

use Utils\PHPStan\Attribute\NoTestNeeded;

#[NoTestNeeded]
final class NoTestNeededClass
{
    public function __construct()
    {
    }
}
