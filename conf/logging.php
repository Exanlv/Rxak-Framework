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
                    Filesystem::getInstance()->baseDir . '/logs/rxak.info.log',
                    5,
                    Logger::INFO
                ),
                new RotatingFileHandler(
                    Filesystem::getInstance()->baseDir . '/logs/rxak.error.log',
                    5,
                    Logger::ERROR
                ),
            ],
        ],
    ],
];
