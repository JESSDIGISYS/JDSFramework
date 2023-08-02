<?php

namespace JDS\Controller;

use Psr\Container\ContainerInterface;

abstract class AbstractController
{


	protected ?ContainerInterface $container = null;

	public function setContainer(ContainerInterface $container) : void
	{
		$this->container = $container;
	}
}
