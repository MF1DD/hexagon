<?php

declare(strict_types=1);

namespace {{NAMESPACE}};

use PHPUnit\Framework\TestCase;

class {{CLASS}}Test extends TestCase
{
    public function testInitialClass(): void
    {
        $dto = new {{CLASS}}();
        self::assertInstanceOf({{CLASS}}::class, $dto);

        $this->fail('Other tests not yet implemented');
    }
}
