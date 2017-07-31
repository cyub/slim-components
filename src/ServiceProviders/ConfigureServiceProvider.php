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
            return new ConfigureService($c['settings']['configurePath'] ,$c['settings']['mode']);
        };
    }
}