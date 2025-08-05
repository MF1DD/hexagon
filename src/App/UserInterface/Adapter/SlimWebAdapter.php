<?php

declare(strict_types=1);

namespace App\UserInterface\Adapter;

use App\UserInterface\Web\LuckyNumber\LuckyNumberController;
use Psr\Container\ContainerInterface;
use Slim\App;
use Slim\Factory\AppFactory;

/**
 * @documentation https://www.slimframework.com/docs/v4/
 */
final class SlimWebAdapter
{
    public function run(): void
    {
        $app = self::createApp();
        $app->run();
    }

    /**
     * @return App<ContainerInterface|null>
     */
    public static function createApp(): App
    {
        $app = AppFactory::create();
        $app->get('/lucky-number', LuckyNumberController::class);

        return $app;
    }
}
