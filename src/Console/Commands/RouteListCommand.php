<?php

namespace Rxak\Framework\Console\Commands;

use Rxak\Framework\Routing\Router;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RouteListCommand extends Command
{
    protected static $defaultName = 'route:list';

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $router = Router::getInstance();

        $router->loadRoutes();

        /**
         * @var \Rxak\Framework\Routing\RouteInterface $route
         */
        foreach ($router->routes as $route) {
            echo $route->getSummary(), PHP_EOL;
        }

        return Command::SUCCESS;
    }
}