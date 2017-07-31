<?php

namespace Tink\Common\Facades;

use Closure;

class Cache extends Facade
{
	protected static function getFacadeAccessor() { return 'cache'; }

	public static function put($key, $value, $minutes = 1)
	{
		self::$app->get('cache')->save($key, $value, $minutes * 60);
	}

	public static function forever($key, $value)
	{
		self::$app->get('cache')->save($key, $value, 0);
	}

	public static function remember($key, $minutes, $callable)
	{
		if (static::has($key)) {
			return static::get($key);
		}

		if ($callable instanceof Closure) {
			$value = call_user_func($callable);
		} else {
			$value = $callable;
		}

		static::put($key, $value, $minutes);

		return $value;
	}

	public static function rememberForever($key, $callable)
	{
		static::remember($key, 0, $callable);
	}

	public static function forget($key)
	{
		self::$app->get('cache')->delete($key);
	}

	public static function has($key)
	{
		return self::$app->get('cache')->contains($key);
	}

	public static function get($key, $default = null)
	{
		$result = self::$app->get('cache')->fetch($key);

		return $result ?: $default;
	}

	public static function increment($key, $amount = 1)
	{
		$oldValue = self::$app->get('cache')->fetch($key);

		$newValue = (int)$oldValue + $amount;

		self::$app->get('cache')->save($key, $newValue, 0);

		return $newValue;
	}

	public static function decrement($key, $amount = -1)
	{
		$oldValue = self::$app->get('cache')->fetch($key);
		$newValue = (int)$oldValue + $amount;

		self::$app->get('cache')->save($key, $newValue, 0);

		return $newValue;
	}
}