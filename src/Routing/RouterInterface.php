<?php

namespace JDS\Routing;

use JDS\Http\Request;
use Psr\Container\ContainerInterface;

/**
 * Router contract - handshake 
 * 
 * @package JDS\Routing
 */
interface RouterInterface
{

	/**
	 * Route dispatch
	 * 
	 * @param Request $request 
	 * @return mixed 
	 */
	public function dispatch(Request $request, ContainerInterface $container);

	public function setRoutes(array $routes): void;
}
