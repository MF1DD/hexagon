<?php
$finder = PhpCsFixer\Finder::create();
$finder->in(['src', 'Tests', '.qa']);

return (new PhpCsFixer\Config())
    ->setFinder($finder)
    ->setRiskyAllowed(true)
    ->setRules([
        '@PHP82Migration' => true,
        '@PSR12' => true,
        'strict_param' => true,
        'declare_strict_types' => true,
        'final_class' => true,
        'no_unused_imports' => true
    ]);
