<?php

namespace MovieApps\Logging;

use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class LoggerFactory
{
    /**
     * @return Logger
     */
    public static function createLogger()
    {
        $logDir = '/var/log/ebs';
        
        $output = "[%datetime%] %channel%.%level_name%: %message%\n %context%\n %extra%\n\n";
        $formatter = new LineFormatter($output);

        $logger = new Logger('movie-apps-middleware');
        
        $path = $logDir . '/debug.plain.log';
        if (!file_exists($path)) {
            touch($path);
        }
        $debugLog = new StreamHandler($path, Logger::DEBUG);
        $debugLog->setFormatter($formatter);
        $logger->pushHandler($debugLog);

        $path = $logDir . '/error.plain.log';
        if (!file_exists($path)) {
            touch($path);
        }
        $errorLog = new StreamHandler($path, Logger::ERROR, false);
        $errorLog->setFormatter($formatter);
        $logger->pushHandler($errorLog);

        return $logger;
    }
}
