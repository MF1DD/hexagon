<?php

declare(strict_types=1);

namespace Tests\Behat;

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\TableNode;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use InvalidArgumentException;
use PHPUnit\Framework\Assert;
use Psr\Http\Message\ResponseInterface;

final class LuckyNumberContext implements Context
{
    private readonly Client $client;

    private ResponseInterface $response;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => 'http://nginx',
            'http_errors' => false,
        ]);
    }

    /**
     * @Given ich sende eine GET-Anfrage an :path
     * @throws GuzzleException
     */
    public function iSendGetRequestWith(string $path): void
    {
        $this->response = $this->client->get($path);
    }

    /**
     * @Then sollte der JSON-Response enthalten:
     */
    public function responseShouldBeJson(TableNode $table): void
    {
        $json = json_decode((string) $this->response->getBody(), true, 512, JSON_THROW_ON_ERROR);
        Assert::assertIsArray($json);

        foreach ($table->getRowsHash() as $key => $expectedType) {
            Assert::assertIsString($expectedType);
            Assert::assertArrayHasKey($key, $json, sprintf("Key '%s' fehlt im Response", $key));

            $actual = $json[$key];

            match ($expectedType) {
                'int' => Assert::assertIsInt($actual, sprintf("Key '%s' ist kein int", $key)),
                'bool' => Assert::assertIsBool($actual, sprintf("Key '%s' ist kein bool", $key)),
                'string' => Assert::assertIsString($actual, sprintf("Key '%s' ist kein string", $key)),
                default => throw new InvalidArgumentException('Unbekannter Typ: ' . $expectedType),
            };
        }
    }
}
