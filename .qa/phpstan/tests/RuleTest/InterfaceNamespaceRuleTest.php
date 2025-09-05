<?php

declare(strict_types=1);

namespace Tests\PHPStan\RuleTest;

use Override;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use Tests\PHPStan\RuleTest\Interfaces\InvalidInterface;
use Utils\PHPStan\InterfaceNamespaceRule;

/**
 * @extends RuleTestCase<InterfaceNamespaceRule>
 */
final class InterfaceNamespaceRuleTest extends RuleTestCase
{
    /**
     * @return InterfaceNamespaceRule
     */
    #[Override]
    protected function getRule(): Rule
    {
        return new InterfaceNamespaceRule();
    }

    public function testValidInterface(): void
    {
        $this->analyse([__DIR__ . '/Interfaces/Port/MyValidInterface.php'], []);
    }

    public function testInvalidInterface(): void
    {
        $interfaceFqcn = InvalidInterface::class;
        $namespace = 'Tests\PHPStan\data';
        $className = 'InvalidInterface';

        $this->analyse([__DIR__ . '/Interfaces/InvalidInterface.php'], [
            [
                sprintf(
                    "❌ Interface \"%s\" is in the wrong namespace.\nIs expected: \"%s\Port\%s\" ",
                    $interfaceFqcn,
                    $namespace,
                    $className,
                ),
                7,
            ],
        ]);
    }
}
