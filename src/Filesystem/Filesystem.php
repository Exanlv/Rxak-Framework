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

    private function __construct(public string $baseDir)
    {
        
    }
}