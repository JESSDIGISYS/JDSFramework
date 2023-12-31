<?php

namespace JDS\Http;

/**
 * Provide a response to the request
 * 
 * @package JDS\Http
 */
class Response
{
	public const HTTP_INTERNAL_SERVER_ERROR = 500;

	public function __construct(
		private ?string $content = '',
		private int $status = 200,
		private array $headers = []

	) {
		// Must be set before sending content
		// So best to create on instantiation like here
		http_response_code($this->status);
	}

	public function send(): void
	{
		echo $this->content;
	}

	public function setContent(?string $content)
	{
		$this->content = $content;
	}
}
