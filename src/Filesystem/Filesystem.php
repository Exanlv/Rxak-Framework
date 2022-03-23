<?php

namespace Rxak\Framework\Filesystem;

class Filesystem
{
    private static Filesystem $filesystem;

    public static function init(string $baseDir)
    {
        self::$filesystem = new Filesystem($baseDir);
    }

    public static function getInstance(): Filesystem
    {
        return self::$filesystem;
    }

    /**
     * @var string $baseDir Root directory of the app, not ending with directory seperator
     */
    private function __construct(public string $baseDir)
    {
        
    }
}