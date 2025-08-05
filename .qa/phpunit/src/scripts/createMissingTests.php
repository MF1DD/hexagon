#!/usr/bin/env php
<?php

declare(strict_types=1);

$sourceDir = '/app/src';
$testRoot  = '/app/Tests/Unit';
$template  = '/app/.qa/phpunit/src/template/TestTemplate.tpl';

if (!file_exists($template)) {
    echo "❌ Template '{$template}' not found.\n";
    exit(1);
}

/** @var Iterator<int, SplFileInfo> $rii */
$rii = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($sourceDir)
);


foreach ($rii as $file) {
    if (!$file->isFile()) {
        continue;
    }
    if ($file->getExtension() !== 'php') {
        continue;
    }

    $content = file_get_contents($file->getPathname());

    if (false === $content) {
        continue;
    }

    // Skip interfaces
    if (preg_match('/^\s*interface\s+\w+/m', $content)) {
        echo sprintf('⏭️  Skipping interface: %s%s', $file->getPathname(), PHP_EOL);
        continue;
    }

    // Namespace extrahieren
    if (!preg_match('/^\s*namespace\s+([^;]+);/m', $content, $matches)) {
        echo sprintf('⚠️  No namespace found in %s%s', $file->getPathname(), PHP_EOL);
        continue;
    }

    $namespace = trim($matches[1]);
    $className = $file->getBasename('.php');

    // Test-Namespace + Pfad bauen
    $testNamespace = 'Tests\Unit\\' . $namespace;
    $testPath      = $testRoot . '/' . str_replace('\\', '/', $namespace) . '/' . $className . 'Test.php';

    if (file_exists($testPath)) {
        echo sprintf('⚠️  Test already exists: %s%s', $testPath, PHP_EOL);
        continue;
    }

    // Verzeichnisse anlegen
    @mkdir(dirname($testPath), 0o777, true);

    // Template ausfüllen
    $templateContent = file_get_contents($template);

    if ($templateContent === false) {
        echo sprintf("❌ Failed to read file: %s\n", $template);
        continue;
    }

    $testContent = str_replace(
        ['{{NAMESPACE}}', '{{CLASS}}'],
        [$testNamespace, $className],
        $templateContent
    );

    file_put_contents($testPath, $testContent);

    echo sprintf('✅ Created: %s%s', $testPath, PHP_EOL);
}
