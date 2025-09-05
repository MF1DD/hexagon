<?php

declare(strict_types=1);

namespace Tests\Unit\App\UserInterface\Adapter;

use App\UserInterface\Adapter\BinConsoleAdapter;
use App\UserInterface\CLI\LuckyNumber\LuckyNumberCommand;
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

        // Optional: direkt Command-Objekte prüfen
        $this->assertInstanceOf(LuckyNumberCommand::class, $console->get('lucky:number'));
    }
}
