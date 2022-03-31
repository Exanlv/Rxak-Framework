<?php

namespace Rxak\Framework\Console\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateMigrationCommand extends Command
{
    protected static $defaultName = 'make:migration';

    protected function configure(): void
    {
        $this->addArgument('migrationName', InputArgument::REQUIRED);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $migrationName = $input->getArgument('migrationName');

        $path = MigrateCommand::getMigrationsPath();

        $realMigrationName = date('Y-m-d_Hi') . rand(1000, 9999) . $migrationName;

        $migrationPathFull = $path . '/' . $realMigrationName;

        mkdir($migrationPathFull);

        file_put_contents($migrationPathFull . '/migrate.php',  file_get_contents(__DIR__ . '/Templates/Migration.txt'));
        file_put_contents($migrationPathFull . '/rollback.php', file_get_contents(__DIR__ . '/Templates/MigrationRollback.txt'));

        echo 'Migration ', $migrationName, ' created. ', $migrationPathFull, PHP_EOL;

        return Command::SUCCESS;
    }
}