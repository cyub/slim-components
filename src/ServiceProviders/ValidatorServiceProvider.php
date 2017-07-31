<?php

namespace Tink\Common\ServiceProviders;

use Pimple\ServiceProviderInterface;
use Pimple\Container;


class ValidatorServiceProvider implements ServiceProviderInterface
{
    public function register(Container $container)
    {
        $container['validator'] = function ($c) {
            $trans = new Symfony\Component\Translation\Translator($locale = 'en');
    		$validator = new Illuminate\Validation\Factory($trans);
    		//money rule
			$validator->extend('money', function($attribute, $value, $parameters) {
	            return preg_match('/^(([1-9]\d{0,9})|0)(\.\d{1,2})?$/', $value);
	        });
			//telephone rule
	        $validator->extend('telephone', function($attribute, $value, $parameters) {
	            return preg_match('/^1[3-8][0-9]{9}$/', $value);
	        });

    		return $validator;
        };
    }
}