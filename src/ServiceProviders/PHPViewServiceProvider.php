<?php

namespace Tink\Common\ServiceProviders;

use Pimple\ServiceProviderInterface;
use Pimple\Container;
use Slim\Views\PhpRenderer;
use InvalidArgumentException;

class PHPViewServiceProvider 
{
	public function register(Container $container)
	{
		$container['view'] = function($c) {
			$config = $c->get('configure')->get('view');
			if (empty($config)) {
                throw new InvalidArgumentException('view configure missed');
            }
			return new PhpRenderer($config['view_path']);
		};

	}
}