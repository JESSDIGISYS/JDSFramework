<?php

namespace JDS\Routing;

use JDS\Http\Request;

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
	public function dispatch(Request $request);

}


