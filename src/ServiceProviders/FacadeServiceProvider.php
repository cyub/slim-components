<?php

namespace Tink\Common\ServiceProviders;

use Pimple\ServiceProviderInterface;
use Pimple\Container;
use Tink\Common\Facades\Facade;

class FacadeServiceProvider implements ServiceProviderInterface
{
	public function register(Container $container) 
	{
		Facade::setFacadeApplication($container->get('application'));
		$aliases = $container['settings']['alias'];
		foreach ($aliases as $alias => $class) {
			class_alias($class, $alias);
		}
	}
}