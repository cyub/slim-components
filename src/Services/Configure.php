<?php

namespace Tink\Common\Services;

use Illuminate\Support\Arr;
use Exception;

class Configure 
{
	protected $configPath;
	protected $configExt = '.php';

	public function __construct($configurePath)
	{
		$this->setConfigPath($configurePath);
	}

	public function setConfigPath($path)
	{
		$this->configPath = $path;
		return $this;
	}

	public function get($key, $default = null)
	{
		list($fileName) = explode('.', $key);

        $path = $this->configPath . '/' . $fileName . $this->configExt;
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