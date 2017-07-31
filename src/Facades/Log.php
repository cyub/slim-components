<?php

namespace Tink\Common\Facades;

use Monolog\Logger as MonologLogger;

class Log extends Facade
{
	protected static function getFacadeAccessor() { return 'logger'; }

    public static function emergency($message, array $context = [])
    {
        return static::writeLog(__FUNCTION__, $message, $context);
    }

    public static function alert($message, array $context = [])
    {
        return static::writeLog(__FUNCTION__, $message, $context);
    }

    public static function critical($message, array $context = [])
    {
        return static::writeLog(__FUNCTION__, $message, $context);
    }

    public static function error($message, array $context = [])
    {
        return static::writeLog(__FUNCTION__, $message, $context);
    }

    public static function warning($message, array $context = [])
    {
        return static::writeLog(__FUNCTION__, $message, $context);
    }

    public static function notice($message, array $context = [])
    {
        return static::writeLog(__FUNCTION__, $message, $context);
    }

    public static function info($message, array $context = [])
    {
        return static::writeLog(__FUNCTION__, $message, $context);
    }

    public static function debug($message, array $context = [])
    {
        return static::writeLog(__FUNCTION__, $message, $context);
    }

    public static function log($level, $message, array $context = [])
    {
        return static::writeLog($level, $message, $context);
    }

    public static function write($level, $message, array $context = [])
    {
        return static::writeLog($level, $message, $context);
    }

    protected static function writeLog($level, $message, $context)
    {
        $logger   = self::$app->get('logger');
        $logger->{$level}($message, $context);
    }

    public static function getLogger()
    {
    	return self::$app->get('logger');
    }
}
