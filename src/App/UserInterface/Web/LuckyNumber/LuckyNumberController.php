<?php

declare(strict_types=1);

namespace App\UserInterface\Web\LuckyNumber;

use App\Bootstrap\LuckyNumberServiceFactory;
use App\UserInterface\Web\Adapter\ResponseAdapter;
use Psr\Http\Message\ResponseInterface;

final readonly class LuckyNumberController
{
    public function __invoke(): ResponseInterface
    {
        $generateLuckyNumberHandler = LuckyNumberServiceFactory::createGenerateLuckyNumberHandler();
        $luckyNumber = $generateLuckyNumberHandler();

        return ResponseAdapter::response(
            content: (string) $luckyNumber,
        );
    }
}
