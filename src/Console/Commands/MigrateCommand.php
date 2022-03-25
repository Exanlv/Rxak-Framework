<?php

namespace Rxak\Framework\Console\Commands;

use Illuminate\Database\Capsule\Manager;
use Illuminate\Database\Schema\Blueprint;
use Rxak\Framework\Config\Config;
use Rxak\Framework\Filesystem\Filesystem;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MigrateCommand extends Command
{
    private string $migrationsPath;
    private string $migrationsTable;

    protected static $defaultName = 'migrate';

    protected function configure(): void
    {
        $this->addArgument('migration', InputArgument::OPTIONAL);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->migrationsPath = Config::get('migrations.path');
        $this->migrationsTable = Config::get('migrations.table');

        if (!Manager::schema()->hasTable('migrations')) {
            $this->createMigrationsTable();
        }

        $migration = $input->getArgument('migration');

        if ($migration) {
            $this->runMigration($migration, $this->getBatch());

            return Command::SUCCESS;
        }

        $this->runAllMigrations();

        return Command::SUCCESS;
    }

    private function createMigrationsTable(): void
    {
        echo 'Creating migrations table...', PHP_EOL;

        Manager::schema()->create($this->migrationsTable, function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->integer('batch');
        });
    }

    private function runAllMigrations(): void
    {
        $completedMigrations = Manager::connection()->table($this->migrationsTable)->select('name')->get()->toArray();
        $completedMigrations = array_map(function ($migration) {
            return $migration->name;
        }, $completedMigrations);

        $migrations = array_filter(
            scandir(Filesystem::getInstance()->baseDir . '/' . $this->migrationsPath),
            function (string $item) use ($completedMigrations) {
                return !in_array(
                    $item,
                    array_merge(['.', '..'], $completedMigrations)
                );
            }
        );

        if (count($migrations) === 0) {
            echo 'Nothing to migrate', PHP_EOL;

            return;
        }

        $batch = $this->getBatch();
        foreach ($migrations as $migration) {
            $this->runMigration($migration, $batch);
        }
    }

    private function getBatch(): int
    {
        $highest = Manager::connection()->table($this->migrationsTable)->selectRaw('MAX(batch) as batch')->get()->first();

        if ($highest === null) {
            return 1;
        }

        return $highest->batch + 1;
    }

    private function runMigration(string $migration, int $batch): void
    {
        echo 'Running migration ', $migration, '... ';

        try {
            require(Filesystem::getInstance()->baseDir . '/' . $this->migrationsPath . '/' . $migration . '/' . '/migrate.php');
        } catch (\Exception $e) {
            echo 'Failed', PHP_EOL;

            throw $e;
        }

        echo 'Success', PHP_EOL;

        Manager::connection()->table($this->migrationsTable)->insert([
            'name' => $migration,
            'batch' => $batch
        ]);
    }
}