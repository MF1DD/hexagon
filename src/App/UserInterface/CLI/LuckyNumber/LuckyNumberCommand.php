<?php

declare(strict_types=1);

namespace App\UserInterface\CLI\LuckyNumber;

use App\Bootstrap\LuckyNumberServiceFactory;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'lucky:number')]
final class LuckyNumberCommand extends Command
{
    public function __invoke(OutputInterface $output): int
    {
        $generateLuckyNumberHandler = LuckyNumberServiceFactory::createGenerateLuckyNumberHandler();
        $luckyNumber = $generateLuckyNumberHandler();

        $output->writeln(
            sprintf(
                '🍀 Deine Zahl ist: %s. Es ist %s Glückszahl.',
                $luckyNumber->luckyNumber,
                $luckyNumber->isLucky ? 'eine' : 'keine',
            )
        );

        return Command::SUCCESS;
    }
}
