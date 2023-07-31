<?php

namespace JDS\Exceptions;

use Exception;

class HttpException extends Exception
{
	private int $statusCode = 400;

	/**
	 * Get the value of statusCode
	 */ 
	public function getStatusCode()
	{
		return $this->statusCode;
	}

	/**
	 * Set the value of statusCode
	 *
	 * @return  self
	 */ 
	public function setStatusCode($statusCode)
	{
		$this->statusCode = $statusCode;

		return $this;
	}
}