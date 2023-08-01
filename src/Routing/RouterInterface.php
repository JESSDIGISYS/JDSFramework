<?php

namespace JDS\Framework\Routing;

use JDS\Framework\Http\Request;

/**
 * Router contract - handshake 
 * 
 * @package JDS\Framework\Routing
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


