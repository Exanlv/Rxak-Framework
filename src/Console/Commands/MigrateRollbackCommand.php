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

class MigrateRollbackCommand extends Command
{
    private string $migrationsPath;
    private string $migrationsTable;

    protected static $defaultName = 'migrate:rollback';

    protected function configure(): void
    {
        $this->addArgument('migration', InputArgument::OPTIONAL);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->migrationsPath = Config::get('migrations.path');
        $this->migrationsTable = Config::get('migrations.table');

        if (!Manager::schema()->hasTable('migrations')) {
            throw new \Exception('Migration table does not exist, no migrations have been run');
        }

        $migration = $input->getArgument('migration');

        if ($migration) {
            $this->reverseMigration($migration);

            return Command::SUCCESS;
        }

        $batch = $this->getBatch();

        if ($batch === null) {
            throw new \Exception('Migration table is empty. No migrations have been run');
        }

        $this->reverseMigrations($batch);

        return Command::SUCCESS;
    }

    private function reverseMigrations(int $batch): void
    {
        $completedMigrations = Manager::connection()
            ->table($this->migrationsTable)
            ->select('name')
            ->where('batch', $batch)
            ->get()
            ->toArray()
        ;

        $completedMigrations = array_map(function ($migration) {
            return $migration->name;
        }, $completedMigrations);

        foreach ($completedMigrations as $migration) {
            $this->reverseMigration($migration);
        }
    }

    private function getBatch(): ?int
    {
        $highest = Manager::connection()->table($this->migrationsTable)->selectRaw('MAX(batch) as batch')->get()->first();

        return $highest?->batch;
    }

    private function reverseMigration(string $migration): void
    {
        echo 'Reversing migration ', $migration, '... ';

        try {
            require(Filesystem::getInstance()->baseDir . '/' . $this->migrationsPath . '/' . $migration . '/' . '/rollback.php');
        } catch (\Exception $e) {
            echo 'Failed', PHP_EOL;

            throw $e;
        }

        echo 'Success', PHP_EOL;

        Manager::connection()->table($this->migrationsTable)->where([
            'name' => $migration
        ])->delete();
    }
}