<?php

namespace JDS;

use Exception;
use JDS\Http\Request;
use JDS\Http\Response;
use Doctrine\DBAL\Connection;
use JDS\Routing\RouterInterface;
use JDS\Exceptions\HttpException;
use Psr\Container\ContainerInterface;

/**
 * Core of the framework
 * 
 * its primary responsibility is to
 * receive a request and output a response
 * 
 * @package JDS
 */
class Kernel
{

	private string $appEnv;

	public function __construct(
		private RouterInterface $router,
		private ContainerInterface $container
	) {
		$this->appEnv = $this->container->get('APP_ENV');
	}

	/**
	 * handle the requests
	 * 
	 * @param Request $request 
	 * @return Response 
	 */
	public function handle(Request $request): Response
	{
		try {

			[$routeHandler, $vars] = $this->router->dispatch($request, $this->container);

			$response = call_user_func_array($routeHandler, $vars);
		} catch (Exception $exception) {
			$response = $this->createExceptionResponse($exception);
		}

		return $response;
	}

	/**
	 * this will check: are we in development or production mode
	 *
	 * so we can handle what the user will see and provide a better
	 *
	 * ability to help in development

	 * @throws Exception $exception 
	 * 
	 */
	private function createExceptionResponse(Exception $exception): Response
	{
		if (in_array($this->appEnv, ['dev', 'test'])) {
			throw $exception;
		}

		if ($exception instanceof HttpException) {
			return new Response($exception->getMessage(), $exception->getStatusCode());
		}
		return new Response('Server error', Response::HTTP_INTERNAL_SERVER_ERROR);
	}
}
