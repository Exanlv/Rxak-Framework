<?php

namespace Rxak\Framework\Console;

use Rxak\Framework\Console\Commands\CreateConsoleCommand;
use Rxak\Framework\Console\Commands\CreateMigrationCommand;
use Rxak\Framework\Console\Commands\MigrateCommand;
use Rxak\Framework\Console\Commands\MigrateRollbackCommand;
use Rxak\Framework\Console\Commands\RouteListCommand;
use Rxak\Framework\Filesystem\Filesystem;
use Symfony\Component\Console\Application as SymfonyApplication;

class Kernel extends SymfonyApplication
{
    private array $rxakCommands = [
        CreateConsoleCommand::class,
        CreateMigrationCommand::class,

        MigrateCommand::class,
        MigrateRollbackCommand::class,

        RouteListCommand::class,
    ];

    public function __construct()
    {
        parent::__construct();
    }

    public function loadCommands()
    {
        foreach ($this->rxakCommands as $command) {
            $this->add(new $command);
        }

        $dir = array_filter(scandir(Filesystem::getInstance()->baseDir . '/app/Console/Commands'), function ($item) {
            return !in_array($item, ['.', '..']);
        });

        $dir = array_map(function ($item) {
            return substr($item, 0, strlen($item) - 4);
        }, array_filter($dir, function (string $filename) {
            return str_ends_with($filename, '.php');
        }));

        foreach ($dir as $command) {
            $this->add(new ('Rxak\App\Console\Commands\\' . $command));
        }
    }
}