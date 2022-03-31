<?php

namespace Rxak\Framework\Console;

use Symfony\Component\Console\Application as SymfonyApplication;

class Kernel extends SymfonyApplication
{
    public function __construct()
    {
        parent::__construct();

        $this->loadCommands();
    }

    public function loadCommands()
    {
        $dir = array_filter(scandir(__DIR__ . '/Commands'), function ($item) {
            return !in_array($item, ['.', '..']);
        });

        $dir = array_map(function ($item) {
            return substr($item, 0, strlen($item) - 4);
        }, array_filter($dir, function (string $filename) {
            return str_ends_with($filename, '.php');
        }));

        foreach ($dir as $command) {
            $this->add(new ('Rxak\Framework\Console\Commands\\' . $command));
        }
    }
}