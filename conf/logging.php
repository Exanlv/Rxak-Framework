<?php

use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;
use Rxak\Framework\Filesystem\Filesystem;

return [
    'main' => 'rxak',

    'loggers' => [
        'rxak' => [
            'handlers' => [
                new RotatingFileHandler(
                    Filesystem::getInstance()->baseDir . '/logs/rxak.log',
                    5,
                    Logger::INFO
                ),
            ],
        ],
    ],
];
