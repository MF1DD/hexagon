<?php

declare(strict_types=1);

namespace Tests\Feature;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use PHPUnit\Framework\TestCase;

final class LuckyNumberApiTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Skip this test if nginx service is not available
        $client = new Client([
            'base_uri' => 'http://nginx',
            'timeout' => 1,
        ]);

        try {
            $client->get('/', ['http_errors' => false]);
        } catch (\Exception $e) {
            $this->markTestSkipped('Nginx service not available: ' . $e->getMessage());
        }
    }

    /**
     * @throws GuzzleException|\JsonException
     */
    public function testGenerateLuckyNumber(): void
    {
        $client = new Client([
            'base_uri' => 'http://nginx',
        ]);

        $response = $client->get('/lucky-number', [
            'http_errors' => false, // Damit kein Exception bei 4xx/5xx geworfen wird
        ]);

        $this->assertEquals(200, $response->getStatusCode());

        $data = json_decode((string) $response->getBody(), true, 51, JSON_THROW_ON_ERROR);

        self::assertIsArray($data);
        self::assertArrayHasKey('luckyNumber', $data);
        self::assertIsInt($data['luckyNumber']);
        self::assertArrayHasKey('isLucky', $data);
        self::assertIsBool($data['isLucky']);
    }
}
