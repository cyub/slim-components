<?php

namespace Tink\Common\ServiceProviders;

use Pimple\ServiceProviderInterface;
use Pimple\Container;
use Tink\Common\Services\Configure as ConfigureService;


class ConfigureServiceProvider implements ServiceProviderInterface
{
    public function register(Container $container)
    {
        $container['configure'] = function ($c) {
        	$configurePath = empty($c['settings']['configurePath']) ? $c->get('basePath') : $c['settings']['configurePath'];
            return new ConfigureService($configurePath ,$c['settings']['mode']);
        };
    }
}