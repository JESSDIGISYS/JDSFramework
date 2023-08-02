<?php

namespace JDS\Controller;

use JDS\Http\Response;
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

	public function render(string $template, array $parameters = [], Response $response = null) : Response
	{
		
		$content = $this->container->get('twig')->render($template, $parameters);

		$response ??= new Response();
		
		$response->setContent($content);

		return $response;

	}
}
