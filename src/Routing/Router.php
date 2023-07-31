<?php

namespace JDS\Routing;

use JDS\Exceptions\HttpException;
use JDS\Exceptions\HttpRequestMethodException;
use JDS\Http\Request;
use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use function \FastRoute\simpleDispatcher;

class Router implements RouterInterface 
{

	public function dispatch(Request $request) : array
	{

		$routeInfo = $this->extractRouteInfo($request);

		[$handler, $vars] = $routeInfo;

		if (is_array($handler)) {
			[$controller, $method] = $handler;
			$handler = [new $controller, $method];
		}

		return [$handler, $vars];
		// [$status, [$controller, $method], $vars] = $routeInfo;

		// // ***** Call the handler, provided by the route info, in order to create a Response *****

		// return [[new $controller, $method], $vars];

	}

	private function extractRouteInfo(Request $request) : array
	{
		// ***** Create a dispatcher *****
		$dispatcher = simpleDispatcher(function (RouteCollector $routeCollector) {

			$routes = include BASE_PATH . '/routes/web.php';
			foreach ($routes as $route) {
				// unpack the array with ... and use the variable $route from foreach
				$routeCollector->addRoute(...$route);
			}
		});

		// ***** Dispatch a URI, to obtain the route info *****
		// three things we want back, Status, Handler and any variables
		// dispatch requires 2 pieces of information
		// 1. httpMethod
		// 2. uri
		// both can be found in the request

		$routeInfo = $dispatcher->dispatch(
			$request->getMethod(),
			$request->getPathInfo()
		);

		switch ($routeInfo[0]) {
			case Dispatcher::FOUND:
				return [$routeInfo[1], $routeInfo[2]];

			case Dispatcher::METHOD_NOT_ALLOWED:
				$allowedMethods = implode(', ', $routeInfo[1]);
				$e =  new HttpRequestMethodException("The allowed methods are $allowedMethods");
				$e->setStatusCode(405);
				throw $e;

			default:
				$e = new HttpException("Not found!");
				$e->setStatusCode(404);
				throw $e;
		}
	}
}

