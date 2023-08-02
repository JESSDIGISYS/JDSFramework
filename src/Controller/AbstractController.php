<?php

namespace JDS\Controller;

use Psr\Container\ContainerInterface;

/**
 * Controllers can access the twig through the container
 * using $this->container->get('twig');
 * 
 * 
 * @package JDS\Controller
 */
abstract class AbstractController
{


	protected ?ContainerInterface $container = null;

	public function setContainer(ContainerInterface $container) : void
	{
		$this->container = $container;
	}
}
