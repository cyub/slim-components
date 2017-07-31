<?php

namespace Tink\Common\ServiceProviders;

use Pimple\ServiceProviderInterface;
use Pimple\Container;
use Slim\Views\Twig;
use Slim\Views\TwigExtension;
use InvalidArgumentException;

class TwigViewServiceProvider implements ServiceProviderInterface
{
	public function register(Container $container)
	{
		$container['view'] = function($c) {
			$config = $c->get('configure')->get('view');
			if (empty($config)) {
                throw new InvalidArgumentException('view configure missed');
            }
			$view = new Twig($config['view_path'], [
				'cache' => $config['compiled_path'],
			]);

			$basePath = rtrim(str_ireplace('index.php', '', $c['request']->getUri()->getBasePath()), '/');
    		$view->addExtension(new TwigExtension($c['router'], $basePath));
    		var_dump(get_class($view));
    		return $view;
		};

	}
}