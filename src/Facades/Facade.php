<?php

namespace Tink\Common\Facades;

use Illuminate\Support\Facades\Facade as IlluminateFacade;

class Facade extends IlluminateFacade
{
	protected static $container;
	protected static $app;
	protected static $slim;

	public static function setFacadeApplication($app)
	{
		self::$container = $app->getContainer();
		self::$app = $app->getContainer();
		self::$slim = $app;
	}
}
