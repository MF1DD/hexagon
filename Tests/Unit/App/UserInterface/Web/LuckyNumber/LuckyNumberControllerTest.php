<?php

declare(strict_types=1);

namespace Tests\Unit\App\UserInterface\Web\LuckyNumber;

use App\UserInterface\Web\LuckyNumber\LuckyNumberController;
use PHPUnit\Framework\TestCase;

final class LuckyNumberControllerTest extends TestCase
{
    public function testReturnsResponseWithLuckyNumber(): void
    {
        $dto = new LuckyNumberController();
        $result = $dto();

        self::assertSame(200, $result->getStatusCode());

        $body = (string) $result->getBody();

        $this->assertNotEmpty($body);

        $jsonBody = json_decode($body, true, 512, JSON_THROW_ON_ERROR);
        $this->assertIsArray($jsonBody);
        $this->assertArrayHasKey('luckyNumber', $jsonBody);
        $this->assertIsInt($jsonBody['luckyNumber']);
        $this->assertIsBool($jsonBody['isLucky']);
    }
}
