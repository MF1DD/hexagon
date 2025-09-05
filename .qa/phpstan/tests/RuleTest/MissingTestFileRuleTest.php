<?php

declare(strict_types=1);

namespace Tests\PHPStan\RuleTest;

use Override;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use Utils\PHPStan\Attribute\NoTestNeeded;
use Utils\PHPStan\MissingTestFileRule;

/**
 * @extends RuleTestCase<MissingTestFileRule>
 */
final class MissingTestFileRuleTest extends RuleTestCase
{
    #[Override]
    protected function getRule(): Rule
    {
        return new MissingTestFileRule([
            [
                'sourceDir' => __DIR__ . '/Fixtures/src',
                'testDir'   => __DIR__ . '/Fixtures/tests',
            ],
        ]);
    }

    public function testRuleDetectsMissingTest(): void
    {
        $className = 'MyClass';
        $testPath = __DIR__ . '/Fixtures/tests';
        $message = sprintf(
            'Test for class "%s" not found!',
            $className,
        );

        $reasons = [
            sprintf("    💡 • Add #[%s] to this Class as Attribute", NoTestNeeded::class),
            sprintf("• Or create the test here: %s/%sTest.php", $testPath, $className),
        ];

        $this->analyse(
            [__DIR__ . '/Fixtures/src/MyClass.php'],
            [
                [
                    sprintf("%s\n%s\n%s", $message, $reasons[0], $reasons[1]),
                    1,
                ],
            ]
        );
    }

    public function testRuleSkipsIfTestExists(): void
    {
        $this->analyse(
            [__DIR__ . '/Fixtures/src/ClassWithTest.php'],
            []
        );
    }

    public function testRuleSkipsIfTestIsNotNeeded(): void
    {
        $this->analyse(
            [__DIR__ . '/Fixtures/src/NoTestNeededClass.php'],
            []
        );
    }
}
