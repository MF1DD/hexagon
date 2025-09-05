<?php

declare(strict_types=1);

namespace Tests\Unit\App\UserInterface\CLI\LuckyNumber;

use App\Domain\LuckyNumber\ValueObject\LuckyNumberValue;
use App\UserInterface\CLI\LuckyNumber\LuckyNumberCommand;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Tester\CommandTester;

final class LuckyNumberCommandTest extends TestCase
{
    public function testCommandOutputsLuckyNumber(): void
    {
        $command = new LuckyNumberCommand();
        $tester = new CommandTester($command);

        $exitCode = $tester->execute([]);

        $this->assertSame(0, $exitCode);
        $output = $tester->getDisplay();

        preg_match('/ist: (\d+)./', $output, $number);
        if ((int) $number[1] % LuckyNumberValue::LUCKY_MODULO === 0) {
            $this->assertMatchesRegularExpression(
                '/🍀 Deine Zahl ist: \d+\. Es ist eine Glückszahl/',
                $output
            );
        } else {
            $this->assertMatchesRegularExpression(
                '/🍀 Deine Zahl ist: \d+\. Es ist keine Glückszahl/',
                $output
            );
        }
    }
}
