<?php

namespace Rxak\Framework\Console\Commands;

use Exception;
use Rxak\Framework\Filesystem\Filesystem;
use Rxak\Framework\Routing\Router;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateConsoleCommand extends Command
{
    protected static $defaultName = 'make:command';

    protected function configure(): void
    {
        $this->addArgument('commandName', InputArgument::REQUIRED);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $commandName = $input->getArgument('commandName');

        if (!ctype_alnum($commandName) || !ctype_alpha($commandName[0])) {
            throw new Exception('Invalid name, only alphanumeric allowed. 1st character must be [A-Z]');
        }

        $commandName[0] = strtoupper($commandName[0]);

        $basePath = Filesystem::getInstance()->baseDir . '/src/Console/Commands';

        $finalizedFilename = $basePath . '/' . $commandName . '.php';

        if (file_exists($finalizedFilename)) {
            throw new \Exception('Command already exists');
        }

        file_put_contents(
            $finalizedFilename,
            str_replace(
                '__CLASSNAME__',
                $commandName,
                file_get_contents(__DIR__ . '/Templates/ConsoleCommand.txt')
            )
        );

        echo 'Command ', $commandName, ' created. ', $finalizedFilename, PHP_EOL;

        return Command::SUCCESS;
    }
}