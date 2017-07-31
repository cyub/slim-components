<?php

namespace Tink\Common\ServiceProviders;

use Pimple\ServiceProviderInterface;
use Pimple\Container;
use Doctrine\Common\Cache\RedisCache;
use Doctrine\Common\Cache\FilesystemCache;
use InvalidArgumentException;
use Redis;

class CacheServiceProvider implements ServiceProviderInterface
{
    public function register(Container $container)
    {
        $container['cache'] = function ($c) {
            $config = $c->get('configure')->get('cache');;
            if (empty($config)) {
                throw new InvalidArgumentException('cache configure missed');
            }
            $driver = $config['default'];
            $cache = $this->{'create' . ucfirst($driver) . 'CacheDriver'}($config[$driver]);
            $cache->setNamespace($config['prefix']);
            return $cache;
        };
    }

    protected function createRedisCacheDriver($config)
    {
        $redis = new Redis();
        $redis->connect($config['host'], $config['port'], $config['timeout']);
        if (isset($config['auth'])) {
            $redis->auth($config['auth']);
        }
        if (isset($config['database'])) {
            $redis->select($config['database']);
        }

        $cacheDriver = new RedisCache();
        $cacheDriver->setRedis($redis);
        return $cacheDriver;
    }

    protected function createFileCacheDriver($config)
    {
        return new FilesystemCache($config['path']);
    }
}