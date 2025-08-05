<?php

declare(strict_types=1);

namespace Tests\Unit\App\UserInterface\Adapter;

use App\UserInterface\Adapter\BinConsoleAdapter;
use App\UserInterface\CLI\LuckyNumber\LuckyNumberCommand;
use App\UserInterface\CLI\PHPUnit\CleanupTestsCommand;
use App\UserInterface\CLI\PHPUnit\GenerateTestsCommand;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application;

final class BinConsoleAdapterTest extends TestCase
{
    public function testCreateConsoleRegistersExpectedCommands(): void
    {
        $console = BinConsoleAdapter::createConsole();

        $this->assertInstanceOf(Application::class, $console);

        $commandNames = array_map(
            static fn ($cmd): ?string => $cmd->getName(),
            $console->all()
        );

        $this->assertContains('lucky:number', $commandNames);
        $this->assertContains('tests:phpunit:cleanup', $commandNames);
        $this->assertContains('tests:phpunit:generate', $commandNames);

        // Optional: direkt Command-Objekte prüfen
        $this->assertInstanceOf(LuckyNumberCommand::class, $console->get('lucky:number'));
        $this->assertInstanceOf(CleanupTestsCommand::class, $console->get('tests:phpunit:cleanup'));
        $this->assertInstanceOf(GenerateTestsCommand::class, $console->get('tests:phpunit:generate'));
    }
}
