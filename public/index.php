<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\UserInterface\Adapter\SlimWebAdapter;

$adapter = new SlimWebAdapter();
$adapter->run();
