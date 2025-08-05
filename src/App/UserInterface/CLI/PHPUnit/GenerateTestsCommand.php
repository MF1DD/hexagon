<?php

declare(strict_types=1);

namespace App\UserInterface\CLI\PHPUnit;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Utils\PHPStan\Attribute\NoTestNeeded;

#[AsCommand(name: 'tests:phpunit:generate')]
#[NoTestNeeded]
final class GenerateTestsCommand extends Command
{
    private string $sourceDir = '/app/src';

    private string $testRoot = '/app/Tests/Unit';

    private string $template = '/app/.qa/phpunit/src/template/TestTemplate.tpl';

    protected function configure(): void
    {
        $this
            ->setDescription('Generiert Unit-Test-Dateien aus vorhandenen Klassen.')
            ->addOption('dry-run', null, InputOption::VALUE_NONE, 'Nur anzeigen, nichts schreiben')
            ->addOption('log', null, InputOption::VALUE_REQUIRED, 'Pfad zu einer Log-Datei');
    }

    #[Override]
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io        = new SymfonyStyle($input, $output);
        $dryRun    = (bool) $input->getOption('dry-run');
        $logPath   = $input->getOption('log');
        $logOutput = [];

        if (!file_exists($this->template)) {
            $io->error(sprintf("Template '%s' nicht gefunden.", $this->template));
            return Command::FAILURE;
        }

        $candidates = $this->findCandidates();
        if (empty($candidates)) {
            $io->success('Keine neuen Tests zu erstellen. Alles ist aktuell!');
            return Command::SUCCESS;
        }

        $this->displayCandidates($io, $candidates);

        $selected = $this->askForSelection($io, $candidates);
        if (empty($selected)) {
            $io->note('Nichts ausgewählt. Keine Dateien wurden erstellt.');
            return Command::SUCCESS;
        }

        $templateContent = file_get_contents($this->template);
        if ($templateContent === false) {
            $io->error('Fehler beim Lesen des Templates: ' . $this->template);
            return Command::FAILURE;
        }

        $this->generateTests($io, $selected, $templateContent, $dryRun, $logOutput);

        $this->writeLogIfNeeded($logPath, $logOutput, $io);

        return Command::SUCCESS;
    }

    private function findCandidates(): array
    {
        $rii = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($this->sourceDir)
        );

        $candidates = [];

        foreach ($rii as $file) {
            if (!$file->isFile()) {
                continue;
            }
            if ($file->getExtension() !== 'php') {
                continue;
            }
            $content = file_get_contents($file->getPathname());
            if ($content === false) {
                continue;
            }
            if (preg_match('/^\s*interface\s+\w+/m', $content)) {
                continue;
            }

            if (!preg_match('/^\s*namespace\s+([^;]+);/m', $content, $matches)) {
                continue;
            }

            $namespace     = trim($matches[1]);
            $className     = $file->getBasename('.php');
            $testNamespace = 'Tests\Unit\\' . $namespace;
            $testPath      = $this->testRoot . '/' . str_replace('\\', '/', $namespace) . '/' . $className . 'Test.php';

            if (file_exists($testPath)) {
                continue;
            }

            $candidates[] = [
                'className'     => $className,
                'namespace'     => $namespace,
                'testNamespace' => $testNamespace,
                'testPath'      => $testPath,
            ];
        }

        return $candidates;
    }

    private function displayCandidates(SymfonyStyle $io, array $candidates): void
    {
        $io->section('Folgende Tests können generiert werden:');
        foreach ($candidates as $i => $candidate) {
            $io->writeln(sprintf('[%d] %s\\%s ➜ %s', $i + 1, $candidate['namespace'], $candidate['className'], $candidate['testPath']));
        }
    }

    private function askForSelection(SymfonyStyle $io, array $candidates): array
    {
        $answer = $io->ask('Welche Tests sollen erstellt werden? (yes = alle, no = keine, oder z.B. 1,3,5)', 'no');

        if (strtolower((string) $answer) === 'no') {
            return [];
        }

        if (strtolower((string) $answer) === 'yes') {
            return $candidates;
        }

        $indices  = array_map('trim', explode(',', (string) $answer));
        $selected = [];

        foreach ($indices as $indexStr) {
            if (!ctype_digit($indexStr)) {
                $io->warning(sprintf("Ungültige Eingabe ignoriert: '%s'", $indexStr));
                continue;
            }

            $i = $indexStr * 1 - 1;
            if (!isset($candidates[$i])) {
                $io->warning(sprintf('Kein Test mit Index %s gefunden.', $indexStr));
                continue;
            }

            $selected[] = $candidates[$i];
        }

        return $selected;
    }

    private function generateTests(SymfonyStyle $io, array $selected, string $templateContent, bool $dryRun, array &$logOutput): void
    {
        foreach ($selected as $candidate) {
            $testPath = $candidate['testPath'];

            if ($dryRun) {
                $msg = '🧪 [DRY RUN] Würde erstellen: ' . $testPath;
                $io->text($msg);
                $logOutput[] = $msg;
                continue;
            }

            $dir = dirname((string) $testPath);
            if (!is_dir($dir)) {
                @mkdir($dir, 0o777, true);
            }

            $testContent = str_replace(
                ['{{NAMESPACE}}', '{{CLASS}}'],
                [$candidate['testNamespace'], $candidate['className']],
                $templateContent
            );

            file_put_contents($testPath, $testContent);

            $msg = '✅ Erstellt: ' . $testPath;
            $io->success($msg);
            $logOutput[] = $msg;
        }
    }

    private function writeLogIfNeeded(?string $logPath, array $logOutput, SymfonyStyle $io): void
    {
        if (!$logPath) {
            return;
        }

        try {
            file_put_contents($logPath, implode(PHP_EOL, $logOutput) . PHP_EOL, FILE_APPEND);
            $io->note('Log geschrieben nach: ' . $logPath);
        } catch (\Throwable $throwable) {
            $io->error('Fehler beim Schreiben der Log-Datei: ' . $throwable->getMessage());
        }
    }
}
