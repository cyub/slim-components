<?php

namespace Tink\Common\ServiceProviders;

use Pimple\ServiceProviderInterface;
use Pimple\Container;
use Illuminate\Database\Capsule\Manager;
use PDO;

class DBServiceProvider implements ServiceProviderInterface
{
    public function register(Container $container)
    {
        $container['db'] = function ($c) {
            $config = $c->get('configure')->get('database');
            $capsule = new Manager;
            if (isset($config['driver'])) {
                $capsule->addConnection($config);
            } else {
                foreach ($config as $name => $c) {
                    $capsule->addConnection($c, $name);
                }
            }

            $capsule->setAsGlobal();
            $capsule->bootEloquent();
            $capsule->setFetchMode(PDO::FETCH_ASSOC);

            return $capsule;
        };
    }
}