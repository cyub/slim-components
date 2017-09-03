# Slim Framework 3 通用组件包

用于[cyub/slim-skeleton](https://github.com/cyub/slim-skeleton)搭建脚手架程序

此通用包一共提供3类组件：服务提供者、门面模式支持、中间件。

## 服务提供者
借鉴Laravel的服务提供者，通过`slim-skeleton`里面[settings.php](https://github.com/cyub/slim-skeleton/blob/master/src/settings.php)配置providers选项来启用某一服务，配置示例如下
```
'providers' => [
    ...
    Tink\Common\ServiceProviders\LoggerServiceProvider::class,
    Tink\Common\ServiceProviders\DBServiceProvider::class,
    Tink\Common\ServiceProviders\RedisServiceProvider::class,
    Tink\Common\ServiceProviders\CacheServiceProvider::class,
    Tink\Common\ServiceProviders\ValidatorServiceProvider::class,
    ....
],
```
现支持的服务提供者如下：

**Dotdev**

读取.env文件的配置，依赖[vlucas/phpdotenv](https://github.com/vlucas/phpdotenv)，配合Configure服务来使用

**Configure**

用于读取网站配置，依赖`Tink\Common\Services\Configure`

**Facade**

用于支持门面模式

**Logger**

日志服务提供者，依赖[monolog](https://github.com/Seldaek/monolog)

**DB**

数据库服务提供者，依赖[Illuminate\Database](https://github.com/illuminate/database)

**Redis**

Redis服务提供者

**Cache**

缓存服务提供者，支持Redis和File缓存类型，依赖[doctrine/cache](https://github.com/doctrine/cache)

**Validator**

验证器服务提供者，依赖[illuminate/validation](https://github.com/illuminate/validation)

**Twig**

Twig模板引擎服务提供者，依赖[slim/twig-view](https://github.com/slimphp/Twig-View)

**PHPView**

PHP原生语言模板引擎提供者，依赖[slim/php-view](https://github.com/slimphp/PHP-View)

## 门面模式支持
如果在控制器层或者其他层里面访问注入到IOC容器的组件，需要将容器注入到控制器层，然后通过容器来访问组件。这种方式有时候会比较麻烦，这时候我们可以通过门面模式来动态访问某个组件，而不用关心这个组件具体实现是怎么样

通过配置`slim-skeleton`里面[settings.php](https://github.com/cyub/slim-skeleton/blob/master/src/settings.php)来启动门面，示例配置如下：
```
'alias' => [
    ...
    'Cache'     => Tink\Common\Facades\Cache::class,
    'DB'        => Tink\Common\Facades\DB::class,
    'Config'    => Tink\Common\Facades\Config::class,
    ...
]
```

现支持的门面模式有：

**App** 

用于访问当前应用信息

**Cache**

用于缓存操作

**DB**

用于数据库操作

**Log**

用于打日志

**Input**

用于获取请求参数

**Validator**

用于表单验证

**Config**

用于读取配置项

## 中间件
现在支持的中间件有:
* [Cors](http://www.baidu.com) 支持跨站资源共享配置
