<?php

declare(strict_types=1);

namespace App\UserInterface\Web\Adapter;

use Slim\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Utils\PHPStan\Attribute\NoTestNeeded;

#[NoTestNeeded]
final class ResponseAdapter
{
    /**
     * @param resource|string|null $content
     */
    public static function response(
        mixed $content,
        int $status = 200
    ): ResponseInterface {
        $response = new Response($status);
        $response->getBody()->write($content);
        return $response->withHeader('Content-Type', 'application/json');
    }
}
