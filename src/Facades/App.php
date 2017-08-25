<?php 

namespace Tink\Common\Facades;

class App extends Facade
{
	protected static function getFacadeAccessor() { return self::$container; }

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
		return self::$app['application'];
	}

	public static function singleton($name, $value)
	{
		self::$app[$name] = function () use ($value) {
			return $value;
		};

		return self::$app->get($name);
	}

	public static function basePath()
	{
		return self::$app->get('basePath');
	}

	public static function configPath()
	{
		return self::basePath() . '/' . 'config/';
	}

	public static function environment()
	{
		return getenv('MODE');
	}
}
