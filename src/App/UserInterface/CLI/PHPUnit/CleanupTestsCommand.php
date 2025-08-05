<?php

declare(strict_types=1);

namespace App\UserInterface\CLI\PHPUnit;

use Override;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Utils\PHPStan\Attribute\NoTestNeeded;

#[AsCommand(name: 'tests:phpunit:cleanup')]
#[NoTestNeeded]
final class CleanupTestsCommand extends Command
{
    private string $testRoot = '/app/Tests/Unit';

    private string $sourceRoot = '/app/src';

    #[Override]
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $danglingTests = $this->findDanglingTestFiles();

        if (empty($danglingTests)) {
            $io->success('🎉 Alle Tests haben eine zugehörige Klasse.');
            return Command::SUCCESS;
        }

        $this->outputDanglingTests($io, $danglingTests);

        if (!$io->confirm('Möchtest du diese Tests löschen?', false)) {
            $io->note('Abgebrochen. Keine Dateien wurden gelöscht.');
            return Command::SUCCESS;
        }

        $this->deleteDanglingTests($io, $danglingTests);

        return Command::SUCCESS;
    }

    /**
     * Durchsucht das Testverzeichnis nach verwaisten Testdateien.
     *
     * @return list<string>
     */
    protected function findDanglingTestFiles(): array
    {
        $rii = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($this->testRoot)
        );

        $dangling = [];

        foreach ($rii as $file) {
            if (!$this->isPhpTestFile($file)) {
                continue;
            }

            $testPath = $file->getPathname();
            $content = file_get_contents($testPath);
            if ($content === false) {
                continue;
            }

            $relativeNamespace = $this->extractRelativeNamespace($content);
            if ($relativeNamespace === null) {
                continue;
            }

            $testClassName = $file->getBasename('.php');
            if (!str_ends_with((string) $testClassName, 'Test')) {
                continue;
            }

            $sourcePath = $this->resolveSourcePath($relativeNamespace, $testClassName);
            if (!file_exists($sourcePath)) {
                $dangling[] = $testPath;
            }
        }

        return $dangling;
    }

    private function isPhpTestFile(\SplFileInfo $file): bool
    {
        return $file->isFile() && $file->getExtension() === 'php';
    }

    private function extractRelativeNamespace(string $fileContent): ?string
    {
        if (preg_match('/^\s*namespace\s+Tests\\\\Unit\\\\([^;]+);/m', $fileContent, $matches)) {
            return $matches[1];
        }

        return null;
    }

    private function resolveSourcePath(string $relativeNamespace, string $testClassName): string
    {
        $className = substr($testClassName, 0, -4); // Entferne "Test"
        $relativePath = str_replace('\\', '/', $relativeNamespace);
        return $this->sourceRoot . '/' . $relativePath . '/' . $className . '.php';
    }

    /**
     * @param list<string> $files
     */
    private function outputDanglingTests(SymfonyStyle $io, array $files): void
    {
        $io->section('🧹 Verwaiste Tests gefunden:');
        foreach ($files as $i => $path) {
            $io->writeln(sprintf('[%d] %s', $i + 1, $path));
        }
    }

    /**
     * @param list<string> $files
     */
    private function deleteDanglingTests(SymfonyStyle $io, array $files): void
    {
        foreach ($files as $path) {
            if (unlink($path)) {
                $io->success('🗑️ Gelöscht: ' . $path);
            } else {
                $io->error('❌ Konnte nicht löschen: ' . $path);
            }
        }
    }
}
