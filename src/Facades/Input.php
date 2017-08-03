<?php

namespace Tink\Common\Facades;

class Input extends Facade
{
	protected static function getFacadeAccessor() { return 'request'; }

	public static function get($key, $default = null)
	{
		return self::$app->get('request')->getParam($key, $default);
	}

	public static function all()
	{
		return self::$app->get('request')->getParams();
	}

	public static function has($key)
	{
		return self::$app->get('request')->getParam($key, null) !== null;
	}

	public static function only($keys)
	{
		$keys = is_array($keys) ?: func_get_args();
		$results = [];
		$params = self::$app->get('request')->getParams();
		foreach($keys as $key) {
			$results[$key] = isset($params[$key]) ? $params[$key] : null;
		}
		return $results;
	}

	public static function except($keys)
	{
		$keys = is_array($keys) ?: func_get_args();
		$results = [];
		$params = self::$app->get('request')->getParams();
		foreach ($params as $key => $param) {
			if (!in_array($key, $keys)) {
				$results[$key] = $param;
			}
		}
		return $results;
	}
}
