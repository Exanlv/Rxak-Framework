<?php

namespace Rxak\Framework\Logging;

use Monolog\Logger as MonoLogger;
use Rxak\Framework\Config\Config;

class Logger
{
    private static Logger $logger;

    public string $main;

    /**
     * @var MonoLogger[]
     */
    public array $loggers = [];

    private function __construct()
    {
        $config = Config::get('logging');

        $this->main = $config['main'];

        foreach ($config['loggers'] as $name => $loggerConfig) {
            $this->loggers[$name] = new MonoLogger($name);
            
            foreach ($loggerConfig['handlers'] as $handler) {
                $this->loggers[$name]->pushHandler($handler);
            }
        }
    }

    public static function init()
    {
        self::$logger = new Logger();
    }

    public static function debug(mixed $toLog)
    {
        self::$logger->log($toLog, 'debug');
    }

    public static function info(mixed $toLog)
    {
        self::$logger->log($toLog, 'info');
    }

    public static function notice(mixed $toLog)
    {
        self::$logger->log($toLog, 'notice');
    }

    public static function warning(mixed $toLog)
    {
        self::$logger->log($toLog, 'warning');
    }

    public static function error(mixed $toLog)
    {
        self::$logger->log($toLog, 'error');
    }

    public static function critical(mixed $toLog)
    {
        self::$logger->log($toLog, 'critical');
    }

    public static function alert(mixed $toLog)
    {
        self::$logger->log($toLog, 'alert');
    }

    public static function emergency(mixed $toLog)
    {
        self::$logger->log($toLog, 'emergency');
    }

    public function log(mixed $toLog, string $channel)
    {
        $this->loggers[$this->main]->{$channel}($toLog);
    }

    public static function getLogger($name): MonoLogger
    {
        return self::$logger->loggers[$name];
    }
}