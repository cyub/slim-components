<?php

namespace Tink\Common\ServiceProviders;

use Pimple\ServiceProviderInterface;
use Pimple\Container;
use InvalidArgumentException;
use Redis;

class RedisServiceProvider implements ServiceProviderInterface
{
    public function register(Container $container)
    {
        $container['redis'] = function ($c) {
            $config = $c->get('configure')->get('cache.redis');
            if (empty($config)) {
                throw new InvalidArgumentException('redis configure missed');
            }

            $redis = new Redis();
            $redis->connect($config['host'], $config['port']);
            $redis->auth($config['auth']);

            return $redis;
        };
    }
}