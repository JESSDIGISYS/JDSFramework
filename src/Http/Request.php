<?php

namespace JDS\Http;

/**
 * Receive a request
 * 
 * @package JDS\Http
 */
class Request 
{

	public function __construct(
		public readonly array $getParams,
		public readonly array $postParams,
		public readonly array $cookies,
		public readonly array $files,
		public readonly array $server
	) 
	{
	}

	/**
	 * Start our application
	 * 
	 * @return static 
	 */
	public static function createFromGlobals() : static 
	{
		return new static($_GET, $_POST, $_COOKIE, $_FILES, $_SERVER);
	}

	public function getPathInfo() : string
	{
		// use the string token function
		return strtok($this->server['REQUEST_URI'], '?');
	}

	public function getMethod() : string
	{
		return $this->server['REQUEST_METHOD'];
	}
}