<?php

namespace Tink\Common\Facades;

class App extends Facade
{
	protected static function getFacadeAccessor() { return self::$container['application']; }

	public static function make($key)
	{
		return self::$app[$key];
	}

	public static function container()
	{
		return self::$app->getContainer();
	}

	public static function instance()
	{
		return self::$app['app'];
	}

	public static function singleton($name, $value)
	{
		self::$app[$name] = function () use ($value) {
			return $value;
		}
	}

	public static function basePath()
	{
		return realpath(dirname($_SERVER['DOCUMENT_ROOT']));
	}

	public static function environment()
	{
		return isset($_SERVER['SLIM_ENV']) ? $_SERVER['SLIM_ENV'] : self::$app->getContainer()->get('settings')['mode'];
	}
}
