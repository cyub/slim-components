<?php

namespace Tink\Common\Facades;

class DB extends Facade
{
	protected static function getFacadeAccessor() 
	{ 
		$connect = self::$app->get('db')->getConnection();
		return $connect; 
	}
}