<?php

declare(strict_types=1);

namespace Utils\PHPStan;

use InvalidArgumentException;
use Override;
use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassLike;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\IdentifierRuleError;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;
use PHPStan\ShouldNotHappenException;
use Utils\PHPStan\Attribute\NoTestNeeded;

/**
 * @implements Rule<ClassLike>
 */
final readonly class MissingTestFileRule implements Rule
{
    /** @param array<int, array<string, string>> $pathMap */
    public function __construct(
        private array $pathMap
    ) {
        $invalideMapping = array_filter(
            $this->pathMap,
            fn ($mapping): bool =>
            !isset($mapping['sourceDir'], $mapping['testDir']),
        );

        if ($invalideMapping) {
            throw new InvalidArgumentException('Each path map must have "sourceDir" and "testDir".');
        }
    }

    /**
     * @return class-string<ClassLike>
     */
    #[Override]
    public function getNodeType(): string
    {
        return ClassLike::class;
    }

    /**
     * @return list<IdentifierRuleError>
     * @throws ShouldNotHappenException
     */
    #[Override]
    public function processNode(Node $node, Scope $scope): array
    {
        if (!$node instanceof Class_) {
            return [];
        }

        if ($node->name === null) {
            return [];
        }

        if ($this->hasNoTestNeededAttribute($node)) {
            return [];
        }

        $classFile = $scope->getFile();

        $className = $node->name->name;
        $testPath = $this->buildTestPathFromSourcePath($classFile);

        if ($testPath === null) {
            return [];
        }

        if (file_exists($testPath)) {
            return [];
        }

        return [
            RuleErrorBuilder::message(
                sprintf(
                    'Test for class "%s" not found!',
                    $className,
                )
            )
                ->identifier('no.tests.found')
                ->line(1)
                ->acceptsReasonsTip([
                    sprintf("Add #[%s] to this Class as Attribute", NoTestNeeded::class),
                    sprintf("Or create the test here: %s", $testPath),
                ])
                ->build(),
        ];
    }

    private function buildTestPathFromSourcePath(string $filePath): ?string
    {
        /** @var null|string $result */
        $result = array_reduce(
            $this->pathMap,
            function ($carry, array $mapping) use ($filePath) {
                if ($carry !== null) {
                    return $carry;
                }

                $sourceDir = rtrim((string) $mapping['sourceDir'], '/');
                $testDir   = rtrim((string) $mapping['testDir'], '/');

                if (str_starts_with($filePath, $sourceDir)) {
                    $relativePath = substr($filePath, strlen($sourceDir));
                    $testPath     = $testDir . $relativePath;
                    return str_replace('.php', 'Test.php', $testPath);
                }

                return null;
            },
            null
        );

        if (is_string($result)) {
            return $result;
        }

        return null;
    }

    private function hasNoTestNeededAttribute(Class_ $node): bool
    {

        $foundNoTestNeeded = array_map(
            fn ($group): bool =>
                array_map(
                    fn ($attr): bool =>
                        $attr->name->toString() === NoTestNeeded::class,
                    $group->attrs
                ) !== [],
            $node->attrGroups
        );

        return $foundNoTestNeeded !== [];
    }
}
