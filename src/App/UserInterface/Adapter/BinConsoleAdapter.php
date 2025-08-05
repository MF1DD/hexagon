<?php

declare(strict_types=1);

namespace App\UserInterface\Adapter;

use App\UserInterface\CLI\LuckyNumber\LuckyNumberCommand;
use App\UserInterface\CLI\PHPUnit\CleanupTestsCommand;
use App\UserInterface\CLI\PHPUnit\GenerateTestsCommand;
use Exception;
use Symfony\Component\Console\Application;

final class BinConsoleAdapter
{
    /**
     * @throws Exception
     */
    public function run(): void
    {
        $console = self::createConsole();
        $console->run();
    }

    public static function createConsole(): Application
    {
        $console = new Application();

        // Register CLI Commands
        $console->add(new LuckyNumberCommand());
        $console->add(new GenerateTestsCommand());
        $console->add(new CleanupTestsCommand());

        return $console;
    }
}
