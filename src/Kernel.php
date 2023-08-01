<?php

namespace JDS\Framework;

use Exception;
use JDS\Framework\Http\Request;
use JDS\Framework\Http\Response;
use JDS\Framework\Routing\Router;
use JDS\Framework\Exceptions\HttpException;

/**
 * Core of the application
 * 
 * its primary responsibility is to
 * receive a request and output a response
 * 
 * @package JDS\Framework
 */
class Kernel
{

	public function __construct(private Router $router) 
	{
		
	}

	/**
	 * handle the requests
	 * 
	 * @param Request $request 
	 * @return Response 
	 */
	public function handle(Request $request) : Response 
	{

		try {
			[$routeHandler, $vars] = $this->router->dispatch($request);

			$response = call_user_func_array($routeHandler, $vars);

		} catch (HttpException $exception) {
			$response = new Response($exception->getMessage(), $exception->getStatusCode());
		} catch (Exception $exception) {
			$response = new Response($exception->getMessage(), 500);
		}

		return $response;
	}
}

