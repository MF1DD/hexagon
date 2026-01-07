<?php

declare(strict_types=1);

namespace App\UserInterface\Adapter;

use App\UserInterface\CLI\LuckyNumber\LuckyNumberCommand;
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
        $console->addCommands([new LuckyNumberCommand()]);

        return $console;
    }
}
