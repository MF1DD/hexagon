<?php

declare(strict_types=1);

namespace Tests\Unit\App\UserInterface\Adapter;

use App\UserInterface\Adapter\SlimWebAdapter;
use PHPUnit\Framework\TestCase;
use Slim\Psr7\Factory\ServerRequestFactory;

final class SlimWebAdapterTest extends TestCase
{
    /**
     * @throws \JsonException
     */
    public function testLuckyNumberRouteReturns200(): void
    {
        $app = SlimWebAdapter::createApp();

        $request = new ServerRequestFactory()
            ->createServerRequest('GET', '/lucky-number');

        $response = $app->handle($request);

        $this->assertSame(200, $response->getStatusCode());

        $body = (string) $response->getBody();

        $this->assertNotEmpty($body);

        $jsonBody = json_decode($body, true, 512, JSON_THROW_ON_ERROR);
        $this->assertIsArray($jsonBody);
        $this->assertArrayHasKey('luckyNumber', $jsonBody);
        $this->assertIsInt($jsonBody['luckyNumber']);
        $this->assertIsBool($jsonBody['isLucky']);
    }
}
