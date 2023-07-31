<?php

namespace JDS;

use Exception;
use JDS\Http\Request;
use JDS\Http\Response;
use JDS\Routing\Router;
use JDS\Exceptions\HttpException;
use JDS\Exceptions\HttpRequestMethodException;

/**
 * Core of the application
 * 
 * its primary responsibility is to
 * receive a request and output a response
 * 
 * @package JDS
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

