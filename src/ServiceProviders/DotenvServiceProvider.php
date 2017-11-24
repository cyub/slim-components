<?php

namespace Tink\Common\ServiceProviders;

use Pimple\ServiceProviderInterface;
use Pimple\Container;
use Dotenv;

class DotenvServiceProvider implements ServiceProviderInterface
{
    public function register(Container $container)
    {
        $container['dotenv'] = function ($c) {
            $dotenv = new Dotenv\Dotenv($c->get('basePath'));
            return $dotenv;
        };
        // allow overwrite existing environment variables
        $container->get('dotenv')->overload();
    }
}
