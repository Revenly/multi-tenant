<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\Class_\InlineConstructorDefaultToPropertyRector;
use Rector\Config\RectorConfig;
use Rector\Set\ValueObject\LevelSetList;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->paths([
        __DIR__ . '/src/Abstracts',
        __DIR__ . '/src/Contracts',
        __DIR__ . '/src/Database',
        __DIR__ . '/src/Events',
        __DIR__ . '/src/Facades',
        __DIR__ . '/src/Jobs',
        __DIR__ . '/src/Logging',
        __DIR__ . '/src/Models',
        __DIR__ . '/src/Queue',
        //__DIR__ . '/src/Traits',
        //__DIR__ . '/src/Validators',
        //__DIR__ . '/src/Commands',
        //__DIR__ . '/src/Controllers',
        //__DIR__ . '/src/Exceptions',
        //__DIR__ . '/src/Generators',
        //__DIR__ . '/src/Listeners',
        //__DIR__ . '/src/Middleware',
        //__DIR__ . '/src/Providers',
        //__DIR__ . '/src/Repositories',
        //__DIR__ . '/src/Translations',
        //__DIR__ . '/src/Website',
        __DIR__ . '/tests',
    ]);

    $rectorConfig->skip([
        \Rector\Php81\Rector\Array_\FirstClassCallableRector::class,
        \Rector\Php81\Rector\Class_\MyCLabsClassToEnumRector::class,
        \Rector\Php81\Rector\Class_\SpatieEnumClassToEnumRector::class,
        \Rector\Php81\Rector\ClassConst\FinalizePublicClassConstantRector::class,
    ]);

    // register a single rule
    $rectorConfig->rule(InlineConstructorDefaultToPropertyRector::class);

    // define sets of rules
    $rectorConfig->sets([
        LevelSetList::UP_TO_PHP_82,
    ]);
};
