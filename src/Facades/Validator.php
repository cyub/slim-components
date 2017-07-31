<?php

namespace Tink\Common\Facades;

class Validator extends Facade 
{
	protected static $message = [
		'required'   => ':attribute必填',
		'active_url' => '网址格式不正确',
		'same'       => ':attribute和:other不一致',
		'email'      => '邮箱格式不正确',
		'ip'         => 'ip格式不正确',
		'between'    => ':attribute必须介于指定的 :min 和 :max 值之间',
		'in'         => ':attribute必须只能在: :values之间',
		'max'        => ':attribute必须小于等于:max',
		'min'        => ':attribute必须大于等于:min',
		'numeric'    => ':attribute格式不正确，必须数字格式',
		'telephone'  => '手机号码格式不正确',
		'money'      => '金额格式不正确'
	];
	
	protected static function getFacadeAccessor() { return 'validator'; }

	public static function make($input, $rules, $message = [])
	{
		if ($message) {
			$message = array_merge(self::$message, $message);
		} else {
			$message = self::$message;
		}

		$validator = self::$app->get('validator');

		return $validator->make($input, $rules, $message);
	}
}