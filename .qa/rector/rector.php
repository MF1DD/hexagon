<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Set\ValueObject\LevelSetList;
use Rector\Set\ValueObject\SetList;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->paths([
        dirname(__DIR__, 1),
        dirname(__DIR__, 2) . '/src',
        dirname(__DIR__, 2) . '/Tests',
    ]);

    $rectorConfig->sets([
        LevelSetList::UP_TO_PHP_84, // Hebt deinen Code auf aktuellen PHP 8.4 Standard
        SetList::TYPE_DECLARATION,  // Fügt fehlende Typehints & Return-Types hinzu
        SetList::CODING_STYLE,      // Vereinheitlicht Code-Stil
        SetList::EARLY_RETURN,      // Nutzt `early return`-Patterns
    ]);
};
