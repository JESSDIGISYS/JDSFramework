<?php

namespace JDSFramework\Routing;

use JDSFramework\Http\Request;

/**
 * Router contract - handshake 
 * 
 * @package JDSFramework\Routing
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


