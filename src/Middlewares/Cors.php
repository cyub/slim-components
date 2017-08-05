<?php

namespace Tink\Common\Middlewares;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Interop\Container\ContainerInterface;

class Cors 
{
	private $ci;
	private $options;

	public function __construct(ContainerInterface $ci)
	{
		$this->ci = $ci;
		$this->options = $this->normalizeOptions($ci->get('configure')->get('cors'));
	}

	public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next)
	{
		if (!$this->isCorsRequest($request)) {
			return $next($request, $response);
		}

		if (!$this->checkOrgin($request)) {
			return $response->withStatus(401, 'Forbidden');
		}

		if ($this->isPreflightRequest($request)) {
			if (!$this->checkPreflightRequest($request)) {
				return $response->withStatus(401, 'Forbidden');
			}

			if (!$this->checkRequestMethod($request)) {
				return $response->withStatus(405, 'Method Not Allowed');
			}
			return $this->buildPreflightResponse($request, $response);
		} else {
			return $next($request, $this->buildAllowCorsResponse($request, $response));
		}
	}

	private function isCorsRequest(ServerRequestInterface $request)
	{
		return $request->hasHeader('Origin') 
		&& $request->getHeaderLine('Origin') != $request->getUri()->getScheme() . '://' . $request->getUri()->getAuthority();
	}

	private function isPreflightRequest(ServerRequestInterface $request) 
	{
		return $this->isCorsRequest($request) && $request->getMethod() == 'OPTIONS' && $request->hasHeader('Access-Control-Request-Method');
	}

	private function checkOrgin(ServerRequestInterface $request)
	{
		if ($this->options['allowedOrigins'] === true) {
			return true;
		}

		return in_array($request->getHeaderLine('Origin'), $this->options['allowedOrigins']);
	}

	private function checkPreflightRequest(ServerRequestInterface $request)
	{
		if ($this->options['allowedHeaders'] === true) {
			return true;
		} 

		$headers = $request->getHeader('Access-Control-Request-Headers');
		foreach ($headers as $header) {
			if (!in_array($header, $this->options['allowedHeaders'])) {
				return false;
			}
		}
		return true;
	}

	private function checkRequestMethod(ServerRequestInterface $request)
	{
		if ($this->options['allowedMethods'] === true) {
			return true;
		}

		$methods = $request->getHeader('Access-Control-Request-Method');
		foreach ($methods as $method) {
			if (!in_array(strtoupper($method), $this->options['allowedMethods'])) {
				return false;
			}
		}

		return true;
	}

	private function buildPreflightResponse(ServerRequestInterface $request, ResponseInterface $response)
	{
		$headers = array();
		$headers['Access-Control-Allow-Origin'] = $this->options['allowedOrigins'] === true ? '*' : implode(',', $this->options['allowedOrigins']);
		$headers['Access-Control-Allow-Method'] = $this->options['allowedMethods'] === true ? '*' : implode(',', $this->options['allowedMethods']);
		$headers['Access-Control-Allow-Headers'] = $this->options['allowedHeaders'] === true ? $request->getHeaderLine('Access-Control-Request-Headers') : implode(',', $this->options['allowedHeaders']);
		if ($this->options['maxAge'] > 0) {
			$headers['Access-Control-Max-Age'] = $this->options['maxAge'];
		}

		return $this->setResponseHeaders($response, $headers);
	}

	private function setResponseHeaders(ResponseInterface $response, array $headers)
	{
		foreach ($headers as $name => $value) {
			$response = $response->withHeader($name, $value);
		}

		return $response;
	}

	private function buildAllowCorsResponse(ServerRequestInterface $request, ResponseInterface $response)
	{
		$headers = array();
		$headers['Access-Control-Allow-Origin'] = $this->options['allowedOrigins'] === true ? '*' : implode(',', $this->options['allowedOrigins']);
		if ($request->hasHeader('Access-Control-Request-Headers')) {
			$headers['Access-Control-Allow-Headers'] = $this->options['allowedHeaders'] === true ? $request->getHeaderLine('Access-Control-Request-Headers') : implode(',', $this->options['allowedHeaders']);
		}
		if ($request->hasHeader('Access-Control-Request-Method')) {
			$headers['Access-Control-Allow-Method'] = $this->options['allowedMethods'] === true ? '*' : implode(',', $this->options['allowedMethods']);
		}

		return $this->setResponseHeaders($response, $headers);
	}

	private function normalizeOptions(array $options)
	{
		if (in_array('*', $options['allowedOrigins'])) {
			$options['allowedOrigins'] = true;
		}

		if (in_array('*', $options['allowedHeaders'])) {
			$options['allowedHeaders'] = true;
		} else {
			$options['allowedHeaders'] = array_map('strtolower', $options['allowedHeaders']);
		}

		if (in_array('*', $options['allowedMethods'])) {
			$options['allowedMethods'] = true;
		} else {
			$options['allowedMethods'] = array_map('strtoupper', $options['allowedMethods']);
		}

		$options['maxAge'] = intval($options['maxAge']);

		return $options;
	}
}