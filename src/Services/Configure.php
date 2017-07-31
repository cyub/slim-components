<?php

namespace Tink\Common\Services;

use Illuminate\Support\Arr;
use Exception;

class Configure 
{
	protected $environment;
	protected $configPath;
	protected $configExt = '.php';

	public function __construct($configurePath = null, $environment = null)
	{
		$this->setConfigPath($configurePath);
		$this->setEnvironment($environment);
	}

	public function setConfigPath($path)
	{
		if ($path) {
			$this->configPath = $path;
		} else {
			$this->configPath = realpath(dirname($_SERVER['DOCUMENT_ROOT']));
		}
		
		return $this;
	}

	public function setEnvironment($environment)
	{
		$this->environment = $environment;
	}

	public function get($key, $default = null)
	{
		list($fileName) = explode('.', $key);

        $path = $this->configPath . '/' . $this->environment . '/' . $fileName . $this->configExt;
        $configure = $this->load($path);

        return Arr::get([$fileName => $configure], $key, $default);
	}

	public static function load($file)
	{
		if (!file_exists($file)) {
			throw new Exception("$file don't exist");
		}

		$ext = pathinfo($file, \PATHINFO_EXTENSION);

		if ($ext != 'php') {
			throw new Exception("only support php configure file");
		}

		$config = include $file;
		return $config;
	}
}