<?php

namespace Tink\Common;

use Slim\App;
use Exception;
use Slim\Http\Environment;
use Tink\Common\ServiceProviders\Provider;
use Tink\Common\ServiceProviders\DotenvServiceProvider;
use Tink\Common\ServiceProviders\ConfigureServiceProvider;

class Starter
{
    private $app;
    private $basePath;
    private $isBooted;

    /**
     * Create Starter for manage application(Slim\App)
     * @param string $basePath the basepath of application
     */
    public function __construct($basePath)
    {
        $this->basePath = realpath($basePath);
    }

    /**
     * Set application
     * @param App $app instance of Slim\App
     */
    public function setApplication(App $app)
    {
        $this->app = $app;
        return $this;
    }

    /**
     * Boot the starter
     * @return
     */
    public function boot()
    {
        if ($this->isBooted) {
            return;
        }
        $this->bootSession();
        $this->bootConfigureService();
        $this->bootServiceProvider();
        $this->bootRoutes();
        $this->isBooted = true;
    }

    /**
     * Detect application is running console
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
     * Run starter
     */
    public function run()
    {
        if (!$this->app instanceof App) {
            throw new Exception('app must is Slim\App Object');
        }
        $this->prepare()->boot();
        $this->app->run();
    }
}
