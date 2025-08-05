<?php

declare(strict_types=1);

namespace Utils\PHPStan;

use Override;
use PhpParser\Node;
use PhpParser\Node\Stmt\Interface_;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;
use PHPStan\ShouldNotHappenException;

/**
 * @implements Rule<Interface_>
 */
final readonly class InterfaceNamespaceRule implements Rule
{
    #[Override]
    public function getNodeType(): string
    {
        return Interface_::class;
    }

    /**
     * @throws ShouldNotHappenException
     */
    #[Override]
    public function processNode(Node $node, Scope $scope): array
    {
        $namespace = $scope->getNamespace() ?? '';
        $className = $node->name ? $node->name->toString() : '';
        $interfaceFqcn = sprintf(
            '%s\%s',
            $namespace,
            $className,
        );

        if (str_ends_with($namespace, 'Port') || str_ends_with($namespace, 'Ports')) {
            return [];
        }

        return [
            RuleErrorBuilder::message(
                sprintf(
                    "❌ Interface \"%s\" is in the wrong namespace.\nIs expected: \"%s\Port\%s\" ",
                    $interfaceFqcn,
                    $namespace,
                    $className,
                )
            )->identifier('hexagon.interface')->build(),
        ];
    }
}
