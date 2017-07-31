<?php

namespace Tink\Common\ServiceProviders;

use Pimple\Container;

class Provider 
{
	protected static $container;

	public static function setServiceProviderContainer(Container $container, $providers = null)
	{
	    self::$container = $container;
		self::registerServiceProvider($providers);
	}

	public static function registerServiceProvider($providers)
	{
		if (!$providers) {
			$providers = (array)self::$container['settings']['providers'];
		}
		foreach ($providers as $provider) {
	        $instance = new $provider;
	        $instance->register(self::$container);
	    }
	}
}