<?php

namespace Tink\Common\ServiceProviders;

use Pimple\ServiceProviderInterface;
use Pimple\Container;
use Monolog\Logger;
use Monolog\Processor\UidProcessor;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Handler\ErrorLogHandler;
use Monolog\Handler\SyslogHandler;
use Monolog\Handler\FirePHPHandler;

class LoggerServiceProvider implements ServiceProviderInterface
{
    protected static $levels = [
        'debug'     => Logger::DEBUG,
        'info'      => Logger::INFO,
        'notice'    => Logger::NOTICE,
        'warning'   => Logger::WARNING,
        'error'     => Logger::ERROR,
        'critical'  => Logger::CRITICAL,
        'alert'     => Logger::ALERT,
        'emergency' => Logger::EMERGENCY,
    ];

    public function register(Container $container)
    {
        $container['logger'] = function ($c) {
            $config = $c->get('configure')->get('logger');
            $logger = new Logger($config['name']);
            $logger->pushProcessor(new UidProcessor());
            $this->setLogHandler($logger, $config);
            return $logger;
        };
    }

    protected function setLogHandler(&$logger, $config)
    {
        $logLevel = isset(self::$levels[$config['level']]) ? self::$levels[$config['level']] : Logger::DEBUG;

        switch ($config['type']) {
            case 'daily':
                $logger->pushHandler(new RotatingFileHandler($config['path'], 0, $logLevel));
                break;
            case 'errorlog':
                $logger->pushHandler(new ErrorLogHandler(ErrorLogHandler::OPERATING_SYSTEM, $logLevel));
                break;
            case 'syslog':
                $logger->pushHandler(new SyslogHandler($config['name'], LOG_USER, $logLevel));
                break;
            case 'firephp':
                $logger->pushHandler(new FirePHPHandler(), $logLevel);
                break;
            default: //single
                $logger->pushHandler(new StreamHandler($config['path'], $logLevel));
                break;
        }
    }
}