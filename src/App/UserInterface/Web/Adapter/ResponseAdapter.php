<?php

declare(strict_types=1);

namespace App\UserInterface\Web\Adapter;

use Slim\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Utils\PHPStan\Attribute\NoTestNeeded;

#[NoTestNeeded]
final class ResponseAdapter
{
    /**
     * @param StreamInterface|resource|string|null $body
     */
    public static function response(
        mixed $body,
        int $status = 200
    ): ResponseInterface {
        return new Response(
            status: $status,
            body: $body,
        );
    }
}
