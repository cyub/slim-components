<?php

namespace Tink\Common;

use Slim\App;
use InvalidArgumentException;
use Slim\Http\Environment;
use Tink\Common\ServiceProviders\Provider;
use Tink\Common\ServiceProviders\DotenvServiceProvider;
use Tink\Common\ServiceProviders\ConfigureServiceProvider;

class Starter
{
    private $app; // the slim application
    private $basePath; // the basepath of slim application
    private $isBooted; // the core of appliction's services isbooted

    /**
     * Create Starter for manage slim application
     * @param string $basePath the basepath of slim application
     */
    public function __construct($basePath)
    {
        $this->basePath = realpath($basePath);
    }

    /**
     * Set application
     * @param $app instance of Slim\App
     */
    public function setApplication(App $app)
    {
        $this->app = $app;
        return $this;
    }

    /**
     * Get application
     * @return $app instance of Slim\App
     */
    public function getApplication()
    {
        return $this->app;
    }

    /**
     * Boot the starter
     * @param  callable|null $callback the callback of all pre-boot services
     * @return
     */
    public function boot(callable $callback = null)
    {
        if ($this->isBooted) {
            return;
        }
        $this->bootSession();
        $this->bootConfigureService();
        $this->bootServiceProvider();
        $this->bootRoutes();
        $this->isBooted = true;
        if ($callback) {
            call_user_func($callback);
        }
    }

    /**
     * Detect application is running in console
     * @return boolean
     */
    public function isRunInConsole()
    {
        return defined('STDIN') && substr(strtolower(PHP_SAPI), 0, 3) === 'cli';
    }

    /**
     * prepare for starter boot
     * @return $this
     */
    public function prepare()
    {
        $app = $this->app;
        $container = $app->getContainer();
        $container['application'] = function () use ($app) {
            return $app;
        };
        $container['basePath'] = $this->basePath;
        if ($this->isRunInConsole()) { // mock cli-enviroment when application is running console
            global $argc, $argv;
            $container['environment'] = Environment::mock([
                'REQUEST_METHOD' => 'GET',
                'REQUEST_URI' => '/' . implode('/', array_slice($argv, 1)),
            ]);
        }

        return $this;
    }

    /**
     * Boot Session
     */
    public function bootSession()
    {
        session_start();
    }

    /**
     * Boot ServiceProvider
     */
    public function bootServiceProvider()
    {
        Provider::setServiceProviderContainer($this->app->getContainer());
    }

    /**
     * Boot ConfigureService
     */
    public function bootConfigureService()
    {
        $serviceProviders = [
            DotenvServiceProvider::class,
            ConfigureServiceProvider::class,
        ];
        foreach ($serviceProviders as $provider) {
            $providerInstance = new $provider();
            $providerInstance->register($this->app->getContainer());
        }
    }

    /**
     * Boot Routes
     */
    public function bootRoutes()
    {
        require $this->basePath . '/src/routes.php';
    }

    /**
     * run the slim application
     * @return void;
     */
    public function runApplication()
    {
        $this->app->run();
    }

    /**
     * Run starter
     */
    public function run()
    {
        if (!$this->app instanceof App) {
            throw new InvalidArgumentException('app must is Slim\App Object');
        }
        $this->prepare()->boot([$this, 'runApplication']);
    }
}
